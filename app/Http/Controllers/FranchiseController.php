<?php

namespace App\Http\Controllers;

use App\Models\Franchise;
use App\Models\Entrepot;
use App\Models\Camion;
use App\Models\Vente;
use App\Models\Commande;
use App\Models\Produit;
use App\Models\DemandeCamion;
use App\Models\NotificationPanne;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;
use Barryvdh\DomPDF\Facade\Pdf;

class FranchiseController extends Controller
{

    public function dashboard()
    {
        $franchise = Auth::user();
        
        $stats = [
            'total_ventes_mois' => $franchise->getTotalVentesMensuel(now()->month, now()->year),
            'total_reverse_mois' => $franchise->getTotalReverseMensuel(now()->month, now()->year),
            'nombre_camions' => $franchise->camionsActifs->count(),
            'commandes_en_attente' => $franchise->commandes()->where('statut', 'en_attente')->count(),
            'commandes_clients_en_attente' => \App\Models\CommandeClient::where('franchise_id', $franchise->id)
                ->whereIn('statut', ['en_attente', 'en_attente_paiement', 'confirmee', 'en_preparation'])
                ->count(),
            'commandes_clients_total' => \App\Models\CommandeClient::where('franchise_id', $franchise->id)->count(),
        ];

        $ventes_recentes = $franchise->ventes()->latest()->take(5)->get();
        $commandes_recentes = $franchise->commandes()->with('entrepot')->latest()->take(5)->get();
        $commandes_clients_recentes = \App\Models\CommandeClient::where('franchise_id', $franchise->id)
            ->with('client')
            ->latest()
            ->take(5)
            ->get();
        $camions = $franchise->camionsActifs;

        return view('franchise.dashboard', compact('stats', 'ventes_recentes', 'commandes_recentes', 'commandes_clients_recentes', 'camions'));
    }

    public function profile()
    {
        $franchise = Auth::user();
        return view('franchise.profile', compact('franchise'));
    }

    public function profileUpdate(Request $request)
    {
        $franchise = Auth::user();
        
        $request->validate([
            'nom' => 'required|string|max:255',
            'prenom' => 'required|string|max:255',
            'telephone' => 'required|string|max:20',
            'adresse' => 'required|string',
            'ville' => 'required|string|max:255',
            'code_postal' => 'required|string|max:10',
        ]);

        $franchise->update($request->all());

        return redirect()->route('franchise.profile')->with('success', 'Profil mis à jour avec succès');
    }

    public function camions()
    {
        $franchise = Auth::user();
        $camions = $franchise->camions()->with('ventes')->get();
        
        return view('franchise.camions.index', compact('camions'));
    }

    public function camionsCreate()
    {
        $franchise = Auth::user();
        
        // Récupérer les statistiques des demandes
        $demandes_en_cours = DemandeCamion::where('franchise_id', $franchise->id)
            ->where('statut', 'en_attente')
            ->count();
            
        $demandes_approuvees = DemandeCamion::where('franchise_id', $franchise->id)
            ->where('statut', 'approuvee')
            ->count();
        
        return view('franchise.camions.create', compact('demandes_en_cours', 'demandes_approuvees'));
    }

    public function camionsStore(Request $request)
    {
        $franchise = Auth::user();
        
        $request->validate([
            'type_camion' => 'required|string',
            'localisation_souhaitee' => 'required|string',
            'date_debut_souhaitee' => 'required|date|after:today',
            'duree_attribution' => 'required|string',
            'motif' => 'required|string|min:10',
            'urgent' => 'boolean',
        ]);

        // Créer la demande de camion
        DemandeCamion::create([
            'franchise_id' => $franchise->id,
            'camion_id' => null, // Pas de camion spécifique pour une nouvelle demande
            'type_demande' => 'nouveau',
            'type_camion_souhaite' => $request->type_camion,
            'localisation_souhaitee' => $request->localisation_souhaitee,
            'date_debut_souhaitee' => $request->date_debut_souhaitee,
            'duree_attribution' => $request->duree_attribution,
            'motif' => $request->motif,
            'urgent' => $request->has('urgent'),
            'statut' => 'en_attente',
        ]);

        return redirect()->route('franchise.camions.index')->with('success', 'Demande de camion envoyée avec succès. L\'administrateur a été notifié.');
    }

    public function camionsShow(Camion $camion)
    {
        // Vérifier que le camion appartient au franchisé
        $franchise = Auth::user();
        if (!$franchise->camions->contains($camion->id)) {
            abort(403, 'Accès non autorisé à ce camion');
        }

        // Charger le camion avec les données pivot de la franchise
        $camion = $franchise->camions()->where('camion_id', $camion->id)->first();

        return view('franchise.camions.show', compact('camion'));
    }

    public function camionsEdit($demande)
    {
        // Placeholder - à implémenter avec un modèle DemandeCamion
        $demande = (object) [
            'id' => $demande,
            'type_camion' => 'moyen',
            'localisation_souhaitee' => 'Paris',
            'date_debut_souhaitee' => '2024-01-15',
            'duree_attribution' => 'mois',
            'motif' => 'Besoin pour livraisons',
            'urgent' => false,
            'statut' => 'en_attente',
            'created_at' => now(),
        ];

        return view('franchise.camions.edit', compact('demande'));
    }

    public function camionsUpdate(Request $request, $demande)
    {
        $request->validate([
            'type_camion' => 'required|string',
            'localisation_souhaitee' => 'required|string',
            'date_debut_souhaitee' => 'required|date',
            'duree_attribution' => 'required|string',
            'motif' => 'required|string|min:10',
            'urgent' => 'boolean',
        ]);

        // Mettre à jour la demande (placeholder)
        return redirect()->route('franchise.camions.index')->with('success', 'Demande de camion mise à jour avec succès');
    }

    public function commandes(Request $request)
    {
        $franchise = Auth::user();
        
        // Récupérer les entrepôts pour les filtres
        $entrepots = Entrepot::all();
        
        // Construire la requête avec les filtres
        $query = $franchise->commandes()->with(['entrepot', 'produits']);
        
        // Filtre par statut
        if ($request->filled('statut')) {
            $query->where('statut', $request->statut);
        }
        
        // Filtre par entrepôt
        if ($request->filled('entrepot')) {
            $query->where('entrepot_id', $request->entrepot);
        }
        
        // Filtre par date de début
        if ($request->filled('date_debut')) {
            $query->where('date_commande', '>=', $request->date_debut);
        }
        
        // Filtre par date de fin
        if ($request->filled('date_fin')) {
            $query->where('date_commande', '<=', $request->date_fin);
        }
        
        // Trier par date de commande (plus récent en premier)
        $query->orderBy('date_commande', 'desc');
        
        $commandes = $query->paginate(15)->withQueryString();
        
        return view('franchise.commandes.index', compact('commandes', 'entrepots'));
    }

    public function commandesCreate()
    {
        $franchise = Auth::user();
        $entrepots = Entrepot::where('statut', 'actif')->get();
        $produits = Produit::all();
        
        return view('franchise.commandes.create', compact('entrepots', 'produits'));
    }

    public function commandesStore(Request $request)
    {
        $franchise = Auth::user();
        
        $request->validate([
            'entrepot_id' => 'required|exists:entrepots,id',
            'produits' => 'required|array|min:1',
            'produits.*.produit_id' => 'required|exists:produits,id',
            'produits.*.quantite' => 'required|integer|min:1',
            'notes' => 'nullable|string',
        ]);

        // Vérifier la règle 80/20
        $total_obligatoire = 0;
        $total_libre = 0;
        $produits_data = [];

        foreach ($request->produits as $produit_data) {
            $produit = Produit::find($produit_data['produit_id']);
            $prix_total = $produit->prix_unitaire * $produit_data['quantite'];
            
            if ($produit->obligatoire) {
                $total_obligatoire += $prix_total;
            } else {
                $total_libre += $prix_total;
            }

            $produits_data[$produit->id] = [
                'quantite' => $produit_data['quantite'],
                'prix_unitaire' => $produit->prix_unitaire,
                'prix_total' => $prix_total,
            ];
        }

        $total_commande = $total_obligatoire + $total_libre;
        
        // Vérifier que la règle 80/20 est respectée
        if ($total_commande > 0) {
            $pourcentage_obligatoire = ($total_obligatoire / $total_commande) * 100;
            
            if ($pourcentage_obligatoire < 80) {
                return back()
                    ->withInput()
                    ->withErrors([
                        'regle_8020' => 'La règle 80/20 n\'est pas respectée. Vous devez avoir au moins 80% de produits obligatoires. Actuellement : ' . number_format($pourcentage_obligatoire, 1) . '%'
                    ]);
            }
        } else {
            return back()
                ->withInput()
                ->withErrors([
                    'regle_8020' => 'La commande doit contenir au moins un produit.'
                ]);
        }

        DB::beginTransaction();
        
        try {
            $commande = Commande::create([
                'franchise_id' => $franchise->id,
                'entrepot_id' => $request->entrepot_id,
                'date_commande' => now(),
                'notes' => $request->notes,
            ]);

            // Attacher les produits à la commande via la relation many-to-many
            $commande->produits()->attach($produits_data);
            
            $commande->update([
                'total_commande' => $total_commande,
                'total_obligatoire' => $total_obligatoire,
                'total_libre' => $total_libre,
            ]);

            DB::commit();

            return redirect()->route('franchise.commandes.index')->with('success', 'Commande créée avec succès');
        } catch (\Exception $e) {
            DB::rollback();
            return back()->with('error', 'Erreur lors de la création de la commande');
        }
    }

    public function ventes(Request $request)
    {
        $franchise = Auth::user();
        
        // Récupérer les camions pour les filtres
        $camions = $franchise->camions;
        
        // Construire la requête avec les filtres
        $query = $franchise->ventes()->with('camion');
        
        // Filtre par période
        if ($request->filled('periode')) {
            switch ($request->periode) {
                case 'aujourdhui':
                    $query->whereDate('date_vente', today());
                    break;
                case 'semaine':
                    $query->whereBetween('date_vente', [now()->startOfWeek(), now()->endOfWeek()]);
                    break;
                case 'mois':
                    $query->whereMonth('date_vente', now()->month)->whereYear('date_vente', now()->year);
                    break;
                case 'trimestre':
                    $query->whereBetween('date_vente', [now()->startOfQuarter(), now()->endOfQuarter()]);
                    break;
                case 'annee':
                    $query->whereYear('date_vente', now()->year);
                    break;
            }
        }
        
        // Filtre par date de début
        if ($request->filled('date_debut')) {
            $query->where('date_vente', '>=', $request->date_debut);
        }
        
        // Filtre par date de fin
        if ($request->filled('date_fin')) {
            $query->where('date_vente', '<=', $request->date_fin);
        }
        
        // Filtre par camion
        if ($request->filled('camion')) {
            $query->where('camion_id', $request->camion);
        }
        
        // Trier par date de vente (plus récent en premier)
        $query->orderBy('date_vente', 'desc');
        
        $ventes = $query->paginate(15)->withQueryString();
        
        return view('franchise.ventes.index', compact('ventes', 'camions'));
    }

    public function ventesCreate()
    {
        $franchise = Auth::user();
        $camions = $franchise->camionsActifs;
        
        return view('franchise.ventes.create', compact('camions'));
    }

    public function ventesStore(Request $request)
    {
        $franchise = Auth::user();
        
        $request->validate([
            'date_vente' => 'required|date',
            'montant_total' => 'required|numeric|min:0',
            'nombre_commandes' => 'required|integer|min:0',
            'camion_id' => 'nullable|exists:camions,id',
            'notes' => 'nullable|string',
        ]);

        $montant_reverse = ($request->montant_total * $franchise->pourcentage_ventes) / 100;

        Vente::create([
            'franchise_id' => $franchise->id,
            'camion_id' => $request->camion_id,
            'date_vente' => $request->date_vente,
            'montant_total' => $request->montant_total,
            'montant_reverse' => $montant_reverse,
            'nombre_commandes' => $request->nombre_commandes,
            'notes' => $request->notes,
        ]);

        return redirect()->route('franchise.ventes.index')->with('success', 'Vente enregistrée avec succès');
    }

    public function ventesDownload(Vente $vente)
    {
        // Vérifier que la vente appartient au franchisé connecté
        if ($vente->franchise_id !== Auth::id()) {
            abort(403, 'Accès non autorisé');
        }

        try {
            // Générer le PDF si pas encore généré
            if (!$vente->pdf_path) {
                $vente = $this->generateVentePdf($vente);
            }

            $filePath = storage_path('app/public/' . $vente->pdf_path);
            
            if (!file_exists($filePath)) {
                // Régénérer le PDF si le fichier n'existe pas
                $vente = $this->generateVentePdf($vente);
                $filePath = storage_path('app/public/' . $vente->pdf_path);
            }

            return response()->download($filePath, 'vente_' . $vente->id . '.pdf');
        } catch (\Exception $e) {
            return redirect()->route('franchise.ventes.index')->with('error', 'Erreur lors de la génération du PDF: ' . $e->getMessage());
        }
    }

    private function generateCommandePdf(Commande $commande)
    {
        try {
            // Charger les relations nécessaires
            $commande->load(['franchise', 'entrepot', 'produits']);
            
            // Vérifier que les données nécessaires existent
            if (!$commande->franchise || !$commande->entrepot) {
                throw new \Exception('Données manquantes pour générer le PDF');
            }
            
            // Générer le PDF avec DomPDF
            $pdf = Pdf::loadView('pdf.commande-minimal', compact('commande'));
            
            // Configurer les options du PDF
            $pdf->setPaper('A4', 'portrait');
            
            // Créer le nom de fichier
            $filename = 'commandes/commande_' . $commande->id . '_' . \Carbon\Carbon::parse($commande->date_commande)->format('Y-m-d') . '.pdf';
            $path = storage_path('app/public/' . $filename);
            $dir = dirname($path);
            
            // Créer le dossier s'il n'existe pas
            if (!is_dir($dir)) {
                if (!mkdir($dir, 0755, true)) {
                    throw new \Exception('Impossible de créer le dossier de stockage');
                }
            }
            
            // Sauvegarder le PDF
            $pdf->save($path);
            
            // Vérifier que le fichier a été créé
            if (!file_exists($path)) {
                throw new \Exception('Erreur lors de la sauvegarde du PDF');
            }
            
            $commande->update(['pdf_path' => $filename]);
            
            return $commande;
        } catch (\Exception $e) {
            \Log::error('Erreur génération PDF commande ' . $commande->id . ': ' . $e->getMessage());
            throw new \Exception('Erreur lors de la génération du PDF: ' . $e->getMessage());
        }
    }

    private function generateVentePdf(Vente $vente)
    {
        try {
            // Charger les relations nécessaires
            $vente->load(['franchise', 'camion']);
            
            // Vérifier que les données nécessaires existent
            if (!$vente->franchise) {
                throw new \Exception('Données manquantes pour générer le PDF');
            }
            
            // Générer le PDF avec DomPDF
            $pdf = Pdf::loadView('pdf.vente', compact('vente'));
            
            // Configurer les options du PDF
            $pdf->setPaper('A4', 'portrait');
            
            // Créer le nom de fichier
            $filename = 'ventes/vente_' . $vente->id . '_' . \Carbon\Carbon::parse($vente->date_vente)->format('Y-m-d') . '.pdf';
            $path = storage_path('app/public/' . $filename);
            $dir = dirname($path);
            
            // Créer le dossier s'il n'existe pas
            if (!is_dir($dir)) {
                if (!mkdir($dir, 0755, true)) {
                    throw new \Exception('Impossible de créer le dossier de stockage');
                }
            }
            
            // Sauvegarder le PDF
            $pdf->save($path);
            
            // Vérifier que le fichier a été créé
            if (!file_exists($path)) {
                throw new \Exception('Erreur lors de la sauvegarde du PDF');
            }
            
            $vente->update(['pdf_path' => $filename]);
            
            return $vente;
        } catch (\Exception $e) {
            \Log::error('Erreur génération PDF vente ' . $vente->id . ': ' . $e->getMessage());
            throw new \Exception('Erreur lors de la génération du PDF: ' . $e->getMessage());
        }
    }

    public function commandesShow(Commande $commande)
    {
        // Vérifier que la commande appartient au franchisé connecté
        if ($commande->franchise_id !== Auth::id()) {
            abort(403, 'Accès non autorisé');
        }

        $commande->load(['entrepot', 'produits']);
        return view('franchise.commandes.show', compact('commande'));
    }

    public function commandesEdit(Commande $commande)
    {
        // Vérifier que la commande appartient au franchisé connecté
        if ($commande->franchise_id !== Auth::id()) {
            abort(403, 'Accès non autorisé');
        }

        // Vérifier que la commande peut être modifiée
        if ($commande->statut !== 'en_attente') {
            return redirect()->route('franchise.commandes.index')->with('error', 'Seules les commandes en attente peuvent être modifiées');
        }

        $entrepots = Entrepot::where('statut', 'actif')->get();
        $commande->load(['entrepot', 'produits']);
        
        return view('franchise.commandes.edit', compact('commande', 'entrepots'));
    }

    public function commandesUpdate(Request $request, Commande $commande)
    {
        // Vérifier que la commande appartient au franchisé connecté
        if ($commande->franchise_id !== Auth::id()) {
            abort(403, 'Accès non autorisé');
        }

        // Vérifier que la commande peut être modifiée
        if ($commande->statut !== 'en_attente') {
            return redirect()->route('franchise.commandes.index')->with('error', 'Seules les commandes en attente peuvent être modifiées');
        }

        $request->validate([
            'entrepot_id' => 'required|exists:entrepots,id',
            'notes' => 'nullable|string',
        ]);

        $commande->update([
            'entrepot_id' => $request->entrepot_id,
            'notes' => $request->notes,
        ]);

        return redirect()->route('franchise.commandes.show', $commande)->with('success', 'Commande mise à jour avec succès');
    }

    public function commandesDestroy(Commande $commande)
    {
        // Vérifier que la commande appartient au franchisé connecté
        if ($commande->franchise_id !== Auth::id()) {
            abort(403, 'Accès non autorisé');
        }

        // Vérifier que la commande peut être annulée
        if ($commande->statut !== 'en_attente') {
            return redirect()->route('franchise.commandes.index')->with('error', 'Seules les commandes en attente peuvent être annulées');
        }

        // Supprimer les produits associés
        $commande->produits()->detach();
        
        // Supprimer la commande
        $commande->delete();

        return redirect()->route('franchise.commandes.index')->with('success', 'Commande annulée avec succès');
    }

    public function commandesDownload(Commande $commande)
    {
        // Vérifier que la commande appartient au franchisé connecté
        if ($commande->franchise_id !== Auth::id()) {
            abort(403, 'Accès non autorisé');
        }

        // Vérifier que la commande peut être affichée
        if (!in_array($commande->statut, ['validee', 'livree'])) {
            return redirect()->route('franchise.commandes.show', $commande)->with('error', 'Seules les commandes validées ou livrées peuvent être affichées');
        }

        try {
            // Charger les relations nécessaires
            $commande->load(['franchise', 'entrepot', 'produits']);
            
            // Vérifier que les données nécessaires existent
            if (!$commande->franchise || !$commande->entrepot) {
                throw new \Exception('Données manquantes pour générer le PDF');
            }
            
            // Générer le PDF pour affichage dans le navigateur
            $pdf = Pdf::loadView('pdf.commande-minimal', compact('commande'));
            $pdf->setPaper('A4', 'portrait');
            
            return $pdf->stream('commande_' . $commande->id . '.pdf');
        } catch (\Exception $e) {
            \Log::error('Erreur génération PDF commande ' . $commande->id . ': ' . $e->getMessage());
            return redirect()->route('franchise.commandes.show', $commande)->with('error', 'Erreur lors de la génération du PDF: ' . $e->getMessage());
        }
    }

    // Notifications de panne
    public function signalerPanne(Camion $camion)
    {
        // Vérifier que le camion appartient au franchisé
        $franchise = Auth::user();
        if (!$franchise->camions->contains($camion->id)) {
            abort(403, 'Accès non autorisé à ce camion');
        }

        // Charger le camion avec les données pivot de la franchise
        $camion = $franchise->camions()->where('camion_id', $camion->id)->first();

        return view('franchise.camions.signaler-panne', compact('camion'));
    }

    public function storePanne(Request $request, Camion $camion)
    {
        // Vérifier que le camion appartient au franchisé
        $franchise = Auth::user();
        if (!$franchise->camions->contains($camion->id)) {
            abort(403, 'Accès non autorisé à ce camion');
        }

        $request->validate([
            'type_panne' => 'required|in:mecanique,electrique,pneumatique,autre',
            'gravite' => 'required|in:legere,moderee,grave,critique',
            'description_panne' => 'required|string|min:10',
            'symptomes' => 'required|string|min:5',
        ]);

        NotificationPanne::create([
            'franchise_id' => $franchise->id,
            'camion_id' => $camion->id,
            'type_panne' => $request->type_panne,
            'gravite' => $request->gravite,
            'description_panne' => $request->description_panne,
            'symptomes' => $request->symptomes,
        ]);

        // Si la panne est critique ou grave, mettre le camion en maintenance
        if (in_array($request->gravite, ['grave', 'critique'])) {
            $camion->update(['statut' => 'en_maintenance']);
            
            // Mettre à jour le statut dans la table pivot
            $franchise->camions()->updateExistingPivot($camion->id, ['statut' => 'inactif']);
        }

        return redirect()->route('franchise.camions.index')->with('success', 'Panne signalée avec succès. L\'administrateur a été notifié.');
    }

    // Demandes de remplacement
    public function demanderRemplacement(Camion $camion)
    {
        // Vérifier que le camion appartient au franchisé
        $franchise = Auth::user();
        if (!$franchise->camions->contains($camion->id)) {
            abort(403, 'Accès non autorisé à ce camion');
        }

        // Charger le camion avec les données pivot de la franchise
        $camion = $franchise->camions()->where('camion_id', $camion->id)->first();

        return view('franchise.camions.demander-remplacement', compact('camion'));
    }

    public function storeRemplacement(Request $request, Camion $camion)
    {
        // Vérifier que le camion appartient au franchisé
        $franchise = Auth::user();
        if (!$franchise->camions->contains($camion->id)) {
            abort(403, 'Accès non autorisé à ce camion');
        }

        $request->validate([
            'type_camion_souhaite' => 'required|string',
            'localisation_souhaitee' => 'required|string',
            'date_debut_souhaitee' => 'required|date|after:today',
            'duree_attribution' => 'required|in:temporaire,semaine,mois,permanent',
            'motif' => 'required|string|min:10',
            'urgent' => 'boolean',
        ]);

        DemandeCamion::create([
            'franchise_id' => $franchise->id,
            'camion_id' => $camion->id,
            'type_demande' => 'remplacement',
            'type_camion_souhaite' => $request->type_camion_souhaite,
            'localisation_souhaitee' => $request->localisation_souhaitee,
            'date_debut_souhaitee' => $request->date_debut_souhaitee,
            'duree_attribution' => $request->duree_attribution,
            'motif' => $request->motif,
            'urgent' => $request->has('urgent'),
        ]);

        return redirect()->route('franchise.camions.index')->with('success', 'Demande de remplacement envoyée avec succès. L\'administrateur a été notifié.');
    }

    // Gestion des commandes clients
    public function commandesClients(Request $request)
    {
        $franchise = Auth::user();
        
        $query = \App\Models\CommandeClient::where('franchise_id', $franchise->id)
            ->with(['client', 'menus']);
        
        // Recherche par email ou téléphone
        if ($request->filled('recherche')) {
            $recherche = $request->get('recherche');
            $query->whereHas('client', function($q) use ($recherche) {
                $q->where('email', 'LIKE', "%{$recherche}%")
                  ->orWhere('telephone', 'LIKE', "%{$recherche}%");
            });
        }
        
        // Tri
        $tri = $request->get('tri', 'date_desc');
        switch ($tri) {
            case 'email_asc':
                $query->join('clients', 'commande_clients.client_id', '=', 'clients.id')
                      ->orderBy('clients.email', 'asc')
                      ->select('commande_clients.*');
                break;
            case 'email_desc':
                $query->join('clients', 'commande_clients.client_id', '=', 'clients.id')
                      ->orderBy('clients.email', 'desc')
                      ->select('commande_clients.*');
                break;
            case 'prix_asc':
                $query->orderBy('montant_final', 'asc');
                break;
            case 'prix_desc':
                $query->orderBy('montant_final', 'desc');
                break;
            case 'statut_asc':
                $query->orderBy('statut', 'asc');
                break;
            case 'statut_desc':
                $query->orderBy('statut', 'desc');
                break;
            case 'date_asc':
                $query->orderBy('date_commande', 'asc');
                break;
            case 'date_desc':
            default:
                $query->orderBy('date_commande', 'desc');
                break;
        }
        
        $commandes = $query->paginate(15)->withQueryString();
        
        $stats = [
            'total' => \App\Models\CommandeClient::where('franchise_id', $franchise->id)->count(),
            'en_attente' => \App\Models\CommandeClient::where('franchise_id', $franchise->id)->where('statut', 'en_attente')->count(),
            'commandes_clients_en_attente' => \App\Models\CommandeClient::where('franchise_id', $franchise->id)->where('statut', 'en_attente_paiement')->count(),
            'confirmee' => \App\Models\CommandeClient::where('franchise_id', $franchise->id)->where('statut', 'confirmee')->count(),
            'en_preparation' => \App\Models\CommandeClient::where('franchise_id', $franchise->id)->where('statut', 'en_preparation')->count(),
            'prete' => \App\Models\CommandeClient::where('franchise_id', $franchise->id)->where('statut', 'prete')->count(),
            'terminee' => \App\Models\CommandeClient::where('franchise_id', $franchise->id)->where('statut', 'terminee')->count(),
            'annulee' => \App\Models\CommandeClient::where('franchise_id', $franchise->id)->where('statut', 'annulee')->count(),
        ];
        
        return view('franchise.commandes-clients.index', compact('commandes', 'stats'));
    }

    public function commandesClientsShow(\App\Models\CommandeClient $commande)
    {
        $franchise = Auth::user();
        
        // Vérifier que la commande appartient à ce franchisé
        if ($commande->franchise_id !== $franchise->id) {
            abort(403, 'Accès non autorisé à cette commande');
        }
        
        $commande->load(['client', 'menus']);
        return view('franchise.commandes-clients.show', compact('commande'));
    }

    public function commandesClientsValidate(\App\Models\CommandeClient $commande)
    {
        $franchise = Auth::user();
        
        // Vérifier que la commande appartient à ce franchisé
        if ($commande->franchise_id !== $franchise->id) {
            abort(403, 'Accès non autorisé à cette commande');
        }
        
        $commande->update([
            'statut' => 'confirmee',
        ]);

        return redirect()->route('franchise.commandes-clients.show', $commande)
            ->with('success', 'Commande client validée avec succès');
    }

    public function commandesClientsPrepare(\App\Models\CommandeClient $commande)
    {
        $franchise = Auth::user();
        
        // Vérifier que la commande appartient à ce franchisé
        if ($commande->franchise_id !== $franchise->id) {
            abort(403, 'Accès non autorisé à cette commande');
        }
        
        $commande->update([
            'statut' => 'en_preparation',
        ]);

        return redirect()->route('franchise.commandes-clients.show', $commande)
            ->with('success', 'Commande client mise en préparation');
    }

    public function commandesClientsPrete(\App\Models\CommandeClient $commande)
    {
        $franchise = Auth::user();
        
        // Vérifier que la commande appartient à ce franchisé
        if ($commande->franchise_id !== $franchise->id) {
            abort(403, 'Accès non autorisé à cette commande');
        }
        
        try {
            // Générer un code de récupération à 4 chiffres
            $codeRecuperation = str_pad(rand(0, 9999), 4, '0', STR_PAD_LEFT);
            
            $commande->update([
                'statut' => 'prete',
                'date_livraison_effective' => now(),
                'code_recuperation' => $codeRecuperation,
            ]);

            // Envoyer l'email avec le code de récupération de manière asynchrone
            $emailEnvoye = false;
            try {
                // Envoyer l'email en arrière-plan pour éviter les timeouts
                dispatch(function() use ($commande, $codeRecuperation) {
                    try {
                        \Mail::to($commande->client->email)->send(new \App\Mail\CommandePrete($commande, $codeRecuperation));
                        \Log::info('Email avec code de récupération envoyé pour la commande #' . $commande->id . ' à ' . $commande->client->email);
                    } catch (\Exception $e) {
                        \Log::error('Erreur lors de l\'envoi de l\'email avec code de récupération pour la commande #' . $commande->id . ': ' . $e->getMessage());
                    }
                })->afterResponse();
                
                $emailEnvoye = true;
            } catch (\Exception $e) {
                \Log::error('Erreur lors de la mise en queue de l\'email pour la commande #' . $commande->id . ': ' . $e->getMessage());
                // Ne pas relancer l'exception pour éviter la déconnexion
            }

            $message = $emailEnvoye 
                ? 'Commande marquée comme prête avec succès. Un email avec le code de récupération a été envoyé au client.'
                : 'Commande marquée comme prête avec succès. Erreur lors de l\'envoi de l\'email, mais le code de récupération est : ' . $codeRecuperation;
                
            return redirect()->route('franchise.commandes-clients.show', $commande)
                ->with('success', $message);
                
        } catch (\Exception $e) {
            // Log l'erreur mais ne pas faire échouer la requête
            \Log::error('Erreur lors du passage en prête pour la commande #' . $commande->id . ': ' . $e->getMessage());
            
            return redirect()->route('franchise.commandes-clients.show', $commande)
                ->with('success', 'Commande marquée comme prête avec succès');
        }
    }

    public function commandesClientsTerminer(\App\Models\CommandeClient $commande, Request $request)
    {
        $franchise = Auth::user();
        
        // Vérifier que la commande appartient à ce franchisé
        if ($commande->franchise_id !== $franchise->id) {
            abort(403, 'Accès non autorisé à cette commande');
        }
        
        // Vérifier que la commande est en statut "prête"
        if ($commande->statut !== 'prete') {
            return redirect()->route('franchise.commandes-clients.show', $commande)
                ->with('error', 'Seules les commandes prêtes peuvent être terminées');
        }
        
        // Valider le code
        $request->validate([
            'code_recuperation' => 'required|string|size:4',
        ]);
        
        $codeSaisi = $request->input('code_recuperation');
        
        // Vérifier le code
        if (!$commande->verifierCodeRecuperation($codeSaisi)) {
            return redirect()->route('franchise.commandes-clients.show', $commande)
                ->with('error', 'Code de récupération incorrect');
        }
        
        // Marquer comme terminée
        $commande->recupererCommande();

        return redirect()->route('franchise.commandes-clients.show', $commande)
            ->with('success', 'Commande terminée avec succès ! Le client a récupéré sa commande.');
    }

    public function commandesClientsCancel(\App\Models\CommandeClient $commande)
    {
        $franchise = Auth::user();
        
        // Vérifier que la commande appartient à ce franchisé
        if ($commande->franchise_id !== $franchise->id) {
            abort(403, 'Accès non autorisé à cette commande');
        }
        
        $commande->update([
            'statut' => 'annulee',
        ]);

        return redirect()->route('franchise.commandes-clients.show', $commande)
            ->with('success', 'Commande client annulée');
    }

    public function commandeSurPlace()
    {
        $franchise = Auth::user();
        
        // Récupérer tous les menus disponibles
        $menus = \App\Models\Menu::where('disponible', true)->get();
        
        return view('franchise.commande-sur-place', compact('menus'));
    }

    public function envoyerCommandeSurPlace(Request $request)
    {
        $franchise = Auth::user();
        
        \Log::info('Début de envoyerCommandeSurPlace', [
            'franchise_id' => $franchise->id,
            'request_data' => $request->all()
        ]);
        
        try {
            // Validation simplifiée
            if (!$request->email_client || !$request->nom_client || !$request->telephone_client) {
                return redirect()->route('franchise.commande-sur-place')
                    ->with('error', 'Veuillez remplir toutes les informations client.');
            }

            // Créer ou récupérer le client
            $client = \App\Models\Client::firstOrCreate(
                ['email' => $request->email_client],
                [
                    'nom' => $request->nom_client,
                    'prenom' => '', // Prénom vide pour éviter la duplication
                    'telephone' => $request->telephone_client,
                    'adresse' => 'Adresse temporaire',
                    'code_postal' => '75001',
                    'ville' => 'Paris',
                    'password' => bcrypt('password123'),
                ]
            );

            // Calculer le total
            $total = 0;
            $articles = [];
            
            if ($request->articles) {
                foreach ($request->articles as $article) {
                    if (isset($article['quantite']) && $article['quantite'] > 0) {
                        $menu = \App\Models\Menu::find($article['menu_id']);
                        if ($menu) {
                            $prixTotal = $menu->prix * $article['quantite'];
                            $total += $prixTotal;
                            
                            $articles[] = [
                                'menu' => $menu,
                                'quantite' => $article['quantite'],
                                'prix_unitaire' => $menu->prix,
                                'prix_total' => $prixTotal,
                            ];
                        }
                    }
                }
            }

            // Vérifier qu'il y a au moins un article
            if (empty($articles)) {
                return redirect()->route('franchise.commande-sur-place')
                    ->with('error', 'Veuillez sélectionner au moins un article.');
            }

            // Créer la commande
            $commande = \App\Models\CommandeClient::create([
                'client_id' => $client->id,
                'franchise_id' => $franchise->id,
                'food_truck_id' => $franchise->id,
                'statut' => 'en_attente_paiement',
                'montant_total' => $total,
                'reduction_fidelite' => 0,
                'montant_final' => $total,
                'mode_paiement' => 'en_ligne',
                'reference_paiement' => null,
                'adresse_livraison' => $franchise->adresse_emplacement ?: $franchise->adresse,
                'telephone_contact' => $request->telephone_client,
                'notes' => 'Commande sur place - En attente de paiement',
                'date_commande' => now()
            ]);

            // Ajouter les menus à la commande
            foreach ($request->articles as $article) {
                if (isset($article['quantite']) && $article['quantite'] > 0) {
                    $menu = \App\Models\Menu::find($article['menu_id']);
                    if ($menu) {
                        $commande->menus()->attach($article['menu_id'], [
                            'quantite' => $article['quantite'],
                            'prix_unitaire' => $menu->prix,
                            'prix_total' => $menu->prix * $article['quantite']
                        ]);
                    }
                }
            }

            // Générer un token unique pour le paiement
            $token = \Str::random(32);
            $commande->update(['token_paiement' => $token]);

            // Envoyer l'email avec gestion d'erreur robuste de manière asynchrone
            $emailStatus = 'en cours';
            try {
                // Capturer les variables nécessaires pour la closure
                $emailClient = $request->email_client;
                $commandeId = $commande->id;
                $tokenData = $token;
                
                // Envoyer l'email en arrière-plan pour éviter les timeouts et déconnexions
                dispatch(function() use ($emailClient, $commandeId, $tokenData) {
                    try {
                        // Recréer la commande et les articles à partir de l'ID
                        $commande = \App\Models\CommandeClient::with(['menus', 'client', 'franchise'])->find($commandeId);
                        if ($commande) {
                            // Recréer les articles
                            $articles = [];
                            foreach ($commande->menus as $menu) {
                                $articles[] = [
                                    'menu' => $menu,
                                    'quantite' => $menu->pivot->quantite,
                                    'prix_unitaire' => $menu->pivot->prix_unitaire,
                                    'prix_total' => $menu->pivot->prix_total,
                                ];
                            }
                            
                            \Mail::to($emailClient)->send(new \App\Mail\CommandeSurPlace($commande, $articles, $tokenData));
                            \Log::info('Email commande sur place envoyé pour la commande #' . $commandeId . ' à ' . $emailClient);
                        } else {
                            \Log::error('Commande #' . $commandeId . ' introuvable lors de l\'envoi d\'email');
                        }
                    } catch (\Exception $e) {
                        \Log::error('Erreur envoi email commande sur place: ' . $e->getMessage());
                    }
                })->afterResponse();
                
                $emailStatus = 'en cours d\'envoi';
            } catch (\Exception $e) {
                \Log::error('Erreur lors de la mise en queue de l\'email commande sur place: ' . $e->getMessage());
                $emailStatus = 'erreur de mise en queue';
                // Ne pas relancer l'exception pour éviter la déconnexion
            }

            return redirect()->route('franchise.commandes-clients.index')
                ->with('success', '✅ Commande #' . $commande->id . ' créée avec succès ! Email ' . $emailStatus . '.');

        } catch (\Exception $e) {
            \Log::error('Erreur lors de la création de la commande sur place: ' . $e->getMessage());
            return redirect()->route('franchise.commande-sur-place')
                ->with('error', 'Erreur lors de la création de la commande: ' . $e->getMessage());
        }
    }
} 