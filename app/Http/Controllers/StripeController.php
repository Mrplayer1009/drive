<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Stripe\Stripe;
use Stripe\PaymentIntent;
use App\Models\CommandeClient;
use App\Models\Client;
use App\Services\FideliteService;

class StripeController extends Controller
{
    public function __construct()
    {
        Stripe::setApiKey(config('services.stripe.secret'));
        
        // Désactiver la vérification SSL en développement
        if (app()->environment('local')) {
            \Stripe\Stripe::setVerifySslCerts(false);
        }
    }

    public function checkout(Request $request)
    {
        try {
            // Récupérer les données du panier depuis la session
            $panierData = $request->session()->get('panier', []);
            
            if (empty($panierData)) {
                return redirect()->route('client.panier')->with('error', 'Votre panier est vide');
            }

            // Récupérer la réduction fidélité depuis la session
            $reductionFidelite = $request->session()->get('reduction_fidelite', 0);

            // Convertir le tableau associatif en tableau indexé pour la vue
            $panierArray = array_values($panierData);

            // Calculer le montant total
            $sousTotal = 0;
            foreach ($panierArray as $item) {
                $sousTotal += $item['prix'] * $item['quantite'];
            }
            
            // Appliquer la réduction fidélité
            $total = $sousTotal - $reductionFidelite;
            
            // Vérifier que le total ne soit pas négatif
            if ($total < 0) {
                $total = 0;
            }

            // Créer l'intention de paiement Stripe
            $paymentIntent = PaymentIntent::create([
                'amount' => $total * 100, // Stripe utilise les centimes
                'currency' => 'eur',
                'metadata' => [
                    'client_id' => auth('client')->id(),
                    'panier_count' => count($panierArray),
                    'sous_total' => $sousTotal
                ]
            ]);

            \Log::info('Checkout - Client Secret: ' . $paymentIntent->client_secret);
            \Log::info('Checkout - Total: ' . $total);
            
            return view('client.stripe.checkout', [
                'clientSecret' => $paymentIntent->client_secret,
                'total' => $total,
                'sousTotal' => $sousTotal,
                'reductionFidelite' => $reductionFidelite,
                'panierData' => $panierArray
            ]);

        } catch (\Exception $e) {
            Log::error('Erreur Stripe checkout: ' . $e->getMessage());
            return redirect()->route('client.panier')->with('error', 'Erreur lors de l\'initialisation du paiement');
        }
    }

    public function success(Request $request)
    {
        try {
            $paymentIntentId = $request->get('payment_intent');
            $commandeId = $request->get('commande_id');
            $token = $request->get('token');
            
            \Log::info('=== SUCCESS STRIPE ===');
            \Log::info('Paramètres reçus - PaymentIntent: ' . $paymentIntentId . ', Commande ID: ' . $commandeId . ', Token: ' . $token);
            \Log::info('URL complète: ' . $request->fullUrl());
            \Log::info('Méthode: ' . $request->method());
            
            if (!$paymentIntentId) {
                \Log::error('PaymentIntentId manquant');
                return redirect()->route('client.panier')->with('error', 'Paiement invalide');
            }

            \Log::info('PaymentIntentId reçu: ' . $paymentIntentId);

            // Récupérer l'intention de paiement depuis Stripe
            try {
                $paymentIntent = PaymentIntent::retrieve($paymentIntentId);
                \Log::info('PaymentIntent récupéré - Status: ' . $paymentIntent->status);
            } catch (\Exception $e) {
                \Log::error('Erreur lors de la récupération du PaymentIntent: ' . $e->getMessage());
                return redirect()->route('client.panier')->with('error', 'Erreur lors de la récupération du paiement');
            }
            
            if ($paymentIntent->status !== 'succeeded') {
                return redirect()->route('client.panier')->with('error', 'Le paiement n\'a pas été validé');
            }

            // Si c'est un paiement sur place
            if ($commandeId && $token) {
                \Log::info('Tentative de paiement sur place - Commande ID: ' . $commandeId . ', Token: ' . $token);
                
                $commande = \App\Models\CommandeClient::where('id', $commandeId)
                    ->where('token_paiement', $token)
                    ->where('statut', 'en_attente_paiement')
                    ->with(['client', 'menus', 'franchise'])
                    ->first();

                if (!$commande) {
                    \Log::warning('Commande sur place introuvable avec statut en_attente_paiement - ID: ' . $commandeId . ', Token: ' . $token);
                    
                    // Essayer de trouver la commande sans condition de statut
                    $commande = \App\Models\CommandeClient::where('id', $commandeId)
                        ->where('token_paiement', $token)
                        ->with(['client', 'menus', 'franchise'])
                        ->first();
                        
                    if (!$commande) {
                        \Log::error('Commande sur place introuvable - ID: ' . $commandeId . ', Token: ' . $token);
                        return redirect()->route('client.index')->with('error', 'Commande introuvable');
                    }
                    
                    \Log::warning('Commande trouvée mais avec statut: ' . $commande->statut . ' - ID: ' . $commande->id);
                }
                
                \Log::info('Commande sur place trouvée - ID: ' . $commande->id . ', Statut actuel: ' . $commande->statut);

                // Mettre à jour la commande
                $commande->update([
                    'statut' => 'en_attente',
                    'reference_paiement' => $paymentIntent->id,
                    'token_paiement' => null, // Invalider le token
                ]);

                \Log::info('Commande sur place #' . $commande->id . ' payée - Statut mis à jour de "en_attente_paiement" à "en_attente"');

                // Vérifier si le client a un compte (email existe dans la table clients)
                $clientExiste = \App\Models\Client::where('email', $commande->client->email)->exists();
                \Log::info('Vérification client - Email: ' . $commande->client->email . ', Client existe: ' . ($clientExiste ? 'OUI' : 'NON'));
                
                // Attribuer les points de fidélité si le client a un compte
                if ($clientExiste) {
                    try {
                        $fideliteService = new FideliteService();
                        $pointsGagnes = $fideliteService->attribuerPoints($commande);
                        \Log::info('Points de fidélité attribués pour la commande #' . $commande->id . ': ' . $pointsGagnes . ' points');
                    } catch (\Exception $e) {
                        \Log::error('Erreur lors de l\'attribution des points de fidélité pour la commande #' . $commande->id . ': ' . $e->getMessage());
                    }
                }

                // Envoyer l'email de confirmation pour tous les clients
                try {
                    \Mail::to($commande->client->email)->send(new \App\Mail\ConfirmationCommande($commande));
                } catch (\Exception $e) {
                    \Log::error('Erreur lors de l\'envoi de l\'email de confirmation pour la commande #' . $commande->id . ': ' . $e->getMessage());
                }

                // Toujours utiliser la vue success, mais passer l'info si le client a un compte
                return view('client.stripe.success', [
                    'commande' => $commande,
                    'paymentIntent' => $paymentIntent,
                    'redirection' => $clientExiste
                ]);
            }

            // Paiement normal (depuis le panier)
            $panierData = $request->session()->get('panier', []);
            
            if (empty($panierData)) {
                return redirect()->route('client.panier')->with('error', 'Données du panier manquantes');
            }

            // Convertir le tableau associatif en tableau indexé
            $panierArray = array_values($panierData);

            // Récupérer le food truck sélectionné depuis la session
            $foodTruckId = session('selected_food_truck_id');
            
            // Créer la commande avec statut confirmé
            $commande = $this->createCommande($panierArray, $paymentIntent, $foodTruckId, 'confirmee');

            // Envoyer l'email de confirmation
            try {
                \Mail::to($commande->client->email)->send(new \App\Mail\ConfirmationCommande($commande));
            } catch (\Exception $e) {
                \Log::error('Erreur lors de l\'envoi de l\'email de confirmation pour la commande #' . $commande->id . ': ' . $e->getMessage());
            }

            // Vider le panier et la réduction fidélité
            $request->session()->forget('panier');
            $request->session()->forget('reduction_fidelite');

            // Pour les paiements normaux, le client est toujours connecté (avec compte)
            return view('client.stripe.success', [
                'commande' => $commande,
                'paymentIntent' => $paymentIntent,
                'redirection' => true
            ]);

        } catch (\Exception $e) {
            Log::error('Erreur Stripe success: ' . $e->getMessage());
            return redirect()->route('client.panier')->with('error', 'Erreur lors de la finalisation de la commande');
        }
    }

    public function cancel()
    {
        return redirect()->route('client.panier')->with('info', 'Paiement annulé');
    }

    public function paiementSurPlace($token)
    {
        try {
            // Trouver la commande avec le token
            $commande = \App\Models\CommandeClient::where('token_paiement', $token)
                ->where('statut', 'en_attente_paiement')
                ->with(['client', 'menus', 'franchise'])
                ->first();

            if (!$commande) {
                return redirect()->route('client.index')->with('error', 'Commande introuvable ou déjà payée');
            }

            // Vérifier que le token n'a pas expiré (24h)
            if ($commande->created_at->diffInHours(now()) > 24) {
                return redirect()->route('client.index')->with('error', 'Le lien de paiement a expiré');
            }

            // Créer l'intention de paiement Stripe
            \Stripe\Stripe::setApiKey(config('services.stripe.secret'));

            $paymentIntent = \Stripe\PaymentIntent::create([
                'amount' => (int)($commande->montant_final * 100), // Stripe utilise les centimes
                'currency' => 'eur',
                'metadata' => [
                    'commande_id' => $commande->id,
                    'client_email' => $commande->client->email,
                    'franchise_id' => $commande->franchise_id,
                ],
            ]);

            return view('client.stripe.paiement-sur-place', [
                'commande' => $commande,
                'clientSecret' => $paymentIntent->client_secret,
                'stripeKey' => config('services.stripe.key'),
                'token' => $token,
            ]);

        } catch (\Exception $e) {
            \Log::error('Erreur paiement sur place: ' . $e->getMessage());
            return redirect()->route('client.index')->with('error', 'Erreur lors du traitement du paiement');
        }
    }

    private function createCommande($panierData, $paymentIntent, $foodTruckId, $statut = 'en_attente')
    {
        $client = auth('client')->user();
        
        // Calculer le sous-total
        $sousTotal = 0;
        foreach ($panierData as $item) {
            $sousTotal += $item['prix'] * $item['quantite'];
        }
        
        // Récupérer la réduction fidélité depuis la session
        $reductionFidelite = session('reduction_fidelite', 0);
        
        // Calculer le total avec réduction
        $total = $sousTotal - $reductionFidelite;
        
        // Vérifier que le total ne soit pas négatif
        if ($total < 0) {
            $total = 0;
        }

        // Récupérer le food truck sélectionné par le client
        if (!$foodTruckId) {
            throw new \Exception('Aucun food truck sélectionné. Veuillez choisir un food truck.');
        }

        $foodTruck = \App\Models\Franchise::find($foodTruckId);
        if (!$foodTruck) {
            throw new \Exception('Food truck introuvable');
        }

        if (!$foodTruck->disponible) {
            throw new \Exception('Ce food truck n\'est plus disponible');
        }

        if (!$foodTruck->hasCamionsActifs()) {
            throw new \Exception('Ce food truck n\'a pas de camion disponible pour le moment. Veuillez choisir un autre food truck.');
        }

        // Créer la commande
        $commande = CommandeClient::create([
            'client_id' => $client->id,
            'franchise_id' => $foodTruck->id, // Le food truck devient le franchisé responsable
            'food_truck_id' => $foodTruck->id, // Référence explicite au food truck
            'statut' => $statut, // Utilise le statut passé en paramètre
            'montant_total' => $sousTotal,
            'reduction_fidelite' => $reductionFidelite,
            'montant_final' => $total,
            'mode_paiement' => 'en_ligne',
            'reference_paiement' => $paymentIntent->id,
            'adresse_livraison' => $foodTruck->adresse_emplacement ?: $foodTruck->adresse, // Adresse du food truck
            'telephone_contact' => $client->telephone,
            'notes' => 'Paiement Stripe - ' . $paymentIntent->id . ' (Réservation Food Truck: ' . $foodTruck->nom_complet . ')',
            'date_commande' => now()
        ]);

        // Ajouter les menus à la commande
        foreach ($panierData as $item) {
            $commande->menus()->attach($item['menu_id'], [
                'quantite' => $item['quantite'],
                'prix_unitaire' => $item['prix'],
                'prix_total' => $item['prix'] * $item['quantite']
            ]);
        }

        // Attribuer les points de fidélité
        try {
            $fideliteService = new FideliteService();
            $pointsGagnes = $fideliteService->attribuerPoints($commande);
            \Log::info('Points de fidélité attribués pour la commande #' . $commande->id . ': ' . $pointsGagnes . ' points');
        } catch (\Exception $e) {
            \Log::error('Erreur lors de l\'attribution des points de fidélité pour la commande #' . $commande->id . ': ' . $e->getMessage());
        }

        // Note: La vente sera créée automatiquement quand la commande passera au statut "livrée"
        // via l'observer dans le modèle CommandeClient

        return $commande;
    }

    public function webhook(Request $request)
    {
        $payload = $request->getContent();
        $sigHeader = $request->header('Stripe-Signature');
        $endpointSecret = config('services.stripe.webhook_secret');

        try {
            $event = \Stripe\Webhook::constructEvent(
                $payload, $sigHeader, $endpointSecret
            );
        } catch (\UnexpectedValueException $e) {
            Log::error('Webhook Stripe - Invalid payload: ' . $e->getMessage());
            return response('Invalid payload', 400);
        } catch (\Stripe\Exception\SignatureVerificationException $e) {
            Log::error('Webhook Stripe - Invalid signature: ' . $e->getMessage());
            return response('Invalid signature', 400);
        }

        // Gérer les événements
        switch ($event->type) {
            case 'payment_intent.succeeded':
                $this->handlePaymentSucceeded($event->data->object);
                break;
            case 'payment_intent.payment_failed':
                $this->handlePaymentFailed($event->data->object);
                break;
            default:
                Log::info('Webhook Stripe - Event non géré: ' . $event->type);
        }

        return response('Webhook handled', 200);
    }

    private function handlePaymentSucceeded($paymentIntent)
    {
        Log::info('Paiement Stripe réussi: ' . $paymentIntent->id);
        // Ici vous pouvez ajouter une logique supplémentaire si nécessaire
    }

    private function handlePaymentFailed($paymentIntent)
    {
        Log::warning('Paiement Stripe échoué: ' . $paymentIntent->id);
        // Ici vous pouvez ajouter une logique pour gérer les échecs de paiement
    }
}
