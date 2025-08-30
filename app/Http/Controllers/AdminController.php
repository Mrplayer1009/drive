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
use App\Models\EntrepotProduitStock;
use App\Models\FranchiseProduitStock;
use App\Services\StockService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Facades\Storage;
use App\Models\Menu;
use App\Models\CommandeClient;

class AdminController extends Controller
{
    public function dashboard()
    {
        $stockService = app(StockService::class);
        
        // Statistiques des ventes
        $totalVentes = Vente::count();
        $ventesCommandesClients = Vente::whereNotNull('commande_client_id')->count();
        $ventesManuelles = $totalVentes - $ventesCommandesClients;
        $totalCA = Vente::sum('montant_total');
        $totalReverse = Vente::sum('montant_reverse');
        
        $stats = [
            'total_franchises' => Franchise::count(),
            'total_camions' => Camion::count(),
            'total_entrepots' => Entrepot::count(),
            'total_produits' => Produit::count(),
            'total_commandes' => Commande::count(),
            'total_ventes' => $totalVentes,
            'ventes_commandes_clients' => $ventesCommandesClients,
            'ventes_manuelles' => $ventesManuelles,
            'total_ca' => $totalCA,
            'total_reverse' => $totalReverse,
            'franchises_actifs' => Franchise::where('statut', 'actif')->count(),
            'camions_disponibles' => Camion::where('statut', 'disponible')->count(),
            'camions_en_utilisation' => Camion::where('statut', 'en_utilisation')->count(),
            'camions_maintenance' => Camion::where('statut', 'en_maintenance')->count(),
            'commandes_en_attente' => Commande::where('statut', 'en_attente')->count(),
            'commandes_validees' => Commande::where('statut', 'validee')->count(),
            'ventes_aujourd_hui' => Vente::whereDate('date_vente', today())->count(),
            'total_ventes_mois' => Vente::whereMonth('date_vente', now()->month)->sum('montant_total'),
            'total_reverse_mois' => Vente::whereMonth('date_vente', now()->month)->sum('montant_reverse'),
            'notifications_pannes' => NotificationPanne::where('statut', 'signalee')->count(),
            'demandes_camions' => DemandeCamion::where('statut', 'en_attente')->count(),
            // Statistiques de stock
            'produits_en_rupture_entrepots' => EntrepotProduitStock::where('quantite_stock', '<=', 0)->count(),
            'produits_stock_insuffisant_entrepots' => EntrepotProduitStock::whereRaw('quantite_stock <= stock_minimum')->where('quantite_stock', '>', 0)->count(),
            'produits_en_rupture_franchises' => FranchiseProduitStock::where('quantite_stock', '<=', 0)->count(),
            'produits_stock_insuffisant_franchises' => FranchiseProduitStock::whereRaw('quantite_stock <= stock_minimum')->where('quantite_stock', '>', 0)->count(),
        ];

        $ventes_recentes = Vente::with('franchise')->latest()->take(5)->get();
        $commandes_recentes = Commande::with(['franchise', 'entrepot'])->latest()->take(5)->get();
        $franchises_recentes = Franchise::latest()->take(5)->get();

        return view('admin.dashboard', compact('stats', 'ventes_recentes', 'commandes_recentes', 'franchises_recentes'));
    }

    public function franchises()
    {
        $franchises = Franchise::with(['camions', 'ventes'])->paginate(15);
        return view('admin.franchises.index', compact('franchises'));
    }

    public function franchisesCreate()
    {
        return view('admin.franchises.create');
    }

    public function franchisesStore(Request $request)
    {
        $request->validate([
            'nom' => 'required|string|max:255',
            'prenom' => 'required|string|max:255',
            'email' => 'required|email|unique:franchises',
            'telephone' => 'required|string|max:20',
            'adresse' => 'required|string',
            'ville' => 'required|string|max:255',
            'code_postal' => 'required|string|max:10',
            'date_entree' => 'required|date',
            'password' => 'required|string|min:8',
        ]);

        Franchise::create([
            'nom' => $request->nom,
            'prenom' => $request->prenom,
            'email' => $request->email,
            'telephone' => $request->telephone,
            'adresse' => $request->adresse,
            'ville' => $request->ville,
            'code_postal' => $request->code_postal,
            'date_entree' => $request->date_entree,
            'password' => bcrypt($request->password),
        ]);

        return redirect()->route('admin.franchises.index')->with('success', 'Franchisé créé avec succès');
    }

    public function franchisesEdit(Franchise $franchise)
    {
        return view('admin.franchises.edit', compact('franchise'));
    }

    public function franchisesShow(Franchise $franchise)
    {
        $camions_disponibles = Camion::where('statut', 'disponible')->get();
        return view('admin.franchises.show', compact('franchise', 'camions_disponibles'));
    }

    public function franchisesUpdate(Request $request, Franchise $franchise)
    {
        $request->validate([
            'nom' => 'required|string|max:255',
            'prenom' => 'required|string|max:255',
            'email' => 'required|email|unique:franchises,email,' . $franchise->id,
            'telephone' => 'required|string|max:20',
            'adresse' => 'required|string',
            'ville' => 'required|string|max:255',
            'code_postal' => 'required|string|max:10',
            'date_entree' => 'required|date',
            'statut' => 'required|in:actif,inactif,suspendu',
            'droits_entree' => 'required|numeric|min:0',
            'pourcentage_ventes' => 'required|numeric|min:0|max:100',
        ]);

        $franchise->update($request->all());

        return redirect()->route('admin.franchises.index')->with('success', 'Franchisé mis à jour avec succès');
    }

    public function franchisesActivate(Request $request, Franchise $franchise)
    {
        // Activer le franchisé
        $franchise->update(['statut' => 'actif']);
        
        // Attribuer les camions sélectionnés
        if ($request->has('camions') && is_array($request->camions)) {
            foreach ($request->camions as $camionId) {
                $camion = Camion::find($camionId);
                if ($camion && $camion->statut === 'disponible') {
                    // Attribuer le camion au franchisé
                    $franchise->camions()->attach($camion->id, [
                        'date_attribution' => now(),
                        'statut' => 'actif'
                    ]);
                    
                    // Mettre à jour le statut du camion
                    $camion->update(['statut' => 'en_utilisation']);
                }
            }
        }
        
        return redirect()->route('admin.franchises.index')->with('success', 'Franchisé activé avec succès');
    }

    public function franchisesAssignCamion(Request $request, Franchise $franchise)
    {
        $request->validate([
            'camion_id' => 'required|exists:camions,id',
        ]);

        $camion = Camion::find($request->camion_id);
        
        // Vérifier que le camion est disponible
        if ($camion->statut !== 'disponible') {
            return back()->withErrors(['camion_id' => 'Ce camion n\'est pas disponible.']);
        }

        // Vérifier que le franchisé est actif
        if ($franchise->statut !== 'actif') {
            return back()->withErrors(['franchise' => 'Seuls les franchisés actifs peuvent recevoir des camions.']);
        }

        // Vérifier si le franchisé a déjà un camion et le retirer
        $camionExistant = $franchise->camions()->where('franchise_camion.statut', 'actif')->first();
        if ($camionExistant) {
            // Désactiver l'ancien camion
            $franchise->camions()->updateExistingPivot($camionExistant->id, ['statut' => 'inactif']);
            // Remettre l'ancien camion en statut disponible
            $camionExistant->update(['statut' => 'disponible']);
        }

        // Assigner le nouveau camion au franchisé
        $franchise->camions()->attach($camion->id, [
            'date_attribution' => now(),
            'statut' => 'actif'
        ]);

        // Mettre à jour le statut du camion
        $camion->update(['statut' => 'en_utilisation']);

        return redirect()->route('admin.franchises.index')->with('success', 'Camion assigné avec succès au franchisé ' . $franchise->nom_complet);
    }

    public function franchisesRemoveCamion(Franchise $franchise, Camion $camion)
    {
        // Retirer l'assignation
        $franchise->camions()->detach($camion->id);

        // Remettre le camion en statut disponible
        $camion->update(['statut' => 'disponible']);

        return redirect()->route('admin.franchises.show', $franchise)->with('success', 'Camion retiré avec succès');
    }

    public function entrepots(Request $request)
    {
        $query = Entrepot::with('commandes');
        
        // Recherche par nom d'entrepôt
        if ($request->filled('search')) {
            $search = $request->get('search');
            $query->where('nom', 'LIKE', "%{$search}%");
        }
        
        $entrepots = $query->get();
        return view('admin.entrepots.index', compact('entrepots'));
    }

    public function entrepotsCreate()
    {
        return view('admin.entrepots.create');
    }

    public function entrepotsStore(Request $request)
    {
        $request->validate([
            'nom' => 'required|string|max:255',
            'adresse' => 'required|string',
            'ville' => 'required|string|max:255',
            'code_postal' => 'required|string|max:10',
            'telephone' => 'required|string|max:20',
            'capacite_stockage' => 'required|numeric|min:0',
            'cuisine' => 'boolean',
        ]);

        Entrepot::create($request->all());
        return redirect()->route('admin.entrepots.index')->with('success', 'Entrepôt créé avec succès');
    }

    public function entrepotsShow(Entrepot $entrepot)
    {
        // Charger les stocks de produits de l'entrepôt
        $stocks = $entrepot->stocksProduits()->with('produit')->get();
        
        return view('admin.entrepots.show', compact('entrepot', 'stocks'));
    }

    public function entrepotsEdit(Entrepot $entrepot)
    {
        return view('admin.entrepots.edit', compact('entrepot'));
    }

    public function entrepotsUpdate(Request $request, Entrepot $entrepot)
    {
        $request->validate([
            'nom' => 'required|string|max:255',
            'adresse' => 'required|string',
            'ville' => 'required|string|max:255',
            'code_postal' => 'required|string|max:10',
            'telephone' => 'required|string|max:20',
            'capacite_stockage' => 'required|numeric|min:0',
            'cuisine' => 'boolean',
        ]);

        $entrepot->update($request->all());
        return redirect()->route('admin.entrepots.index')->with('success', 'Entrepôt mis à jour avec succès');
    }

    public function entrepotsDestroy(Entrepot $entrepot)
    {
        $entrepot->delete();
        return redirect()->route('admin.entrepots.index')->with('success', 'Entrepôt supprimé avec succès');
    }

    public function camions()
    {
        $camions = Camion::with(['franchises'])->paginate(15);
        
        // Ne récupérer que les franchisés qui n'ont pas de camion actif
        $franchises = Franchise::whereDoesntHave('camions', function($query) {
            $query->where('franchise_camion.statut', 'actif');
        })->where('statut', 'actif')->get();
        
        // Statistiques
        $stats = [
            'total_camions' => Camion::count(),
            'disponibles' => Camion::where('statut', 'disponible')->count(),
            'en_utilisation' => Camion::where('statut', 'en_utilisation')->count(),
            'en_maintenance' => Camion::where('statut', 'en_maintenance')->count(),
            'assignes' => Camion::whereHas('franchises')->count(),
        ];
        
        return view('admin.camions.index', compact('camions', 'franchises', 'stats'));
    }

    public function camionsAssignFranchise(Request $request, Camion $camion)
    {
        $request->validate([
            'franchise_id' => 'required|exists:franchises,id',
        ]);

        $franchise = Franchise::find($request->franchise_id);
        
        // Vérifier si le franchisé a déjà un camion actif
        $camionExistant = $franchise->camions()->where('franchise_camion.statut', 'actif')->first();
        if ($camionExistant) {
            return back()->withErrors(['franchise_id' => 'Ce franchisé a déjà un camion assigné.']);
        }
        
        // Retirer l'assignation précédente si elle existe
        $camion->franchises()->detach();
        
        // Assigner le nouveau franchisé
        $camion->franchises()->attach($franchise->id, [
            'date_attribution' => now(),
            'statut' => 'actif'
        ]);

        // Mettre à jour le statut du camion
        $camion->update(['statut' => 'en_utilisation']);

        return redirect()->route('admin.camions.index')->with('success', 'Franchisé assigné au camion avec succès');
    }

    public function camionsRemoveFranchise(Camion $camion)
    {
        // Retirer tous les franchisés assignés à ce camion
        $camion->franchises()->detach();
        
        // Mettre le camion en statut disponible
        $camion->update(['statut' => 'disponible']);

        return redirect()->route('admin.camions.index')->with('success', 'Franchisé retiré du camion avec succès');
    }

    public function camionsCreate()
    {
        $franchises = Franchise::where('statut', 'actif')->get();
        return view('admin.camions.create', compact('franchises'));
    }

    public function camionsStore(Request $request)
    {
        $request->validate([
            'immatriculation' => 'required|string|max:20|unique:camions',
            'marque' => 'required|string|max:50',
            'modele' => 'required|string|max:50',
            'annee' => 'required|integer|min:1900|max:' . (date('Y') + 1),
            'statut' => 'required|in:disponible,en_utilisation,en_maintenance,hors_service',
            'ville_localisation' => 'nullable|string|max:100',
            'latitude' => 'nullable|numeric',
            'longitude' => 'nullable|numeric',
            'notes' => 'nullable|string',
            'derniere_maintenance' => 'nullable|date',
            'prochaine_maintenance' => 'nullable|date|after:derniere_maintenance',
        ]);

        Camion::create($request->all());

        return redirect()->route('admin.camions.index')->with('success', 'Camion créé avec succès.');
    }

    public function camionsShow(Camion $camion)
    {
        return view('admin.camions.show', compact('camion'));
    }

    public function camionsEdit(Camion $camion)
    {
        $franchises = Franchise::where('statut', 'actif')->get();
        return view('admin.camions.edit', compact('camion', 'franchises'));
    }

    public function camionsUpdate(Request $request, Camion $camion)
    {
        $request->validate([
            'immatriculation' => 'required|string|max:20|unique:camions,immatriculation,' . $camion->id,
            'marque' => 'required|string|max:50',
            'modele' => 'required|string|max:50',
            'annee' => 'required|integer|min:1900|max:' . (date('Y') + 1),
            'statut' => 'required|in:disponible,en_utilisation,en_maintenance,hors_service',
            'ville_localisation' => 'nullable|string|max:100',
            'latitude' => 'nullable|numeric',
            'longitude' => 'nullable|numeric',
            'notes' => 'nullable|string',
            'derniere_maintenance' => 'nullable|date',
            'prochaine_maintenance' => 'nullable|date|after:derniere_maintenance',
        ]);

        $camion->update($request->all());

        return redirect()->route('admin.camions.index')->with('success', 'Camion mis à jour avec succès.');
    }

    public function camionsDestroy(Camion $camion)
    {
        $camion->delete();
        return redirect()->route('admin.camions.index')->with('success', 'Camion supprimé avec succès');
    }

    public function ventes()
    {
        $ventes = Vente::with(['franchise', 'camion'])->paginate(15);
        $franchises = Franchise::all();
        return view('admin.ventes.index', compact('ventes', 'franchises'));
    }

    public function ventesCreate()
    {
        $franchises = Franchise::where('statut', 'actif')->get();
        $camions = Camion::where('statut', 'en_utilisation')->get();
        return view('admin.ventes.create', compact('franchises', 'camions'));
    }

    public function ventesStore(Request $request)
    {
        $request->validate([
            'franchise_id' => 'required|exists:franchises,id',
            'camion_id' => 'nullable|exists:camions,id',
            'date_vente' => 'required|date',
            'montant_total' => 'required|numeric|min:0',
            'nombre_commandes' => 'required|integer|min:1',
            'notes' => 'nullable|string',
        ]);

        $franchise = Franchise::find($request->franchise_id);
        $montant_reverse = $request->montant_total * ($franchise->pourcentage_ventes / 100);

        Vente::create([
            'franchise_id' => $request->franchise_id,
            'camion_id' => $request->camion_id,
            'date_vente' => $request->date_vente,
            'montant_total' => $request->montant_total,
            'montant_reverse' => $montant_reverse,
            'nombre_commandes' => $request->nombre_commandes,
            'notes' => $request->notes,
        ]);

        return redirect()->route('admin.ventes.index')->with('success', 'Vente créée avec succès');
    }

    public function ventesShow(Vente $vente)
    {
        return view('admin.ventes.show', compact('vente'));
    }

    public function ventesEdit(Vente $vente)
    {
        $franchises = Franchise::where('statut', 'actif')->get();
        $camions = Camion::where('statut', 'en_utilisation')->get();
        return view('admin.ventes.edit', compact('vente', 'franchises', 'camions'));
    }

    public function ventesUpdate(Request $request, Vente $vente)
    {
        $request->validate([
            'franchise_id' => 'required|exists:franchises,id',
            'camion_id' => 'nullable|exists:camions,id',
            'date_vente' => 'required|date',
            'montant_total' => 'required|numeric|min:0',
            'nombre_commandes' => 'required|integer|min:1',
            'notes' => 'nullable|string',
        ]);

        $franchise = Franchise::find($request->franchise_id);
        $montant_reverse = $request->montant_total * ($franchise->pourcentage_ventes / 100);

        $vente->update([
            'franchise_id' => $request->franchise_id,
            'camion_id' => $request->camion_id,
            'date_vente' => $request->date_vente,
            'montant_total' => $request->montant_total,
            'montant_reverse' => $montant_reverse,
            'nombre_commandes' => $request->nombre_commandes,
            'notes' => $request->notes,
        ]);

        return redirect()->route('admin.ventes.index')->with('success', 'Vente mise à jour avec succès');
    }

    public function ventesDestroy(Vente $vente)
    {
        $vente->delete();
        return redirect()->route('admin.ventes.index')->with('success', 'Vente supprimée avec succès');
    }

    public function ventesDownload(Vente $vente)
    {
        try {
            // Charger les relations nécessaires
            $vente->load(['franchise', 'camion']);
            
            // Générer le PDF avec DomPDF
            $pdf = Pdf::loadView('pdf.vente', compact('vente'));
            
            // Configurer les options du PDF
            $pdf->setPaper('A4', 'portrait');
            $pdf->setOptions([
                'isHtml5ParserEnabled' => true,
                'isRemoteEnabled' => true,
                'defaultFont' => 'Arial'
            ]);
            
            // Retourner le PDF pour téléchargement
            return $pdf->download('vente_' . $vente->id . '.pdf');
        } catch (\Exception $e) {
            return response()->json(['error' => 'Erreur lors de la génération du PDF: ' . $e->getMessage()], 500);
        }
    }

    public function commandes()
    {
        $commandes = Commande::with(['franchise', 'entrepot'])->paginate(15);
        $franchises = Franchise::all();
        $entrepots = Entrepot::all();
        return view('admin.commandes.index', compact('commandes', 'franchises', 'entrepots'));
    }

    public function commandesCreate()
    {
        $franchises = Franchise::where('statut', 'actif')->get();
        $entrepots = Entrepot::all();
        $produits = Produit::all();
        return view('admin.commandes.create', compact('franchises', 'entrepots', 'produits'));
    }

    public function commandesStore(Request $request)
    {
        $request->validate([
            'franchise_id' => 'required|exists:franchises,id',
            'entrepot_id' => 'required|exists:entrepots,id',
            'date_commande' => 'required|date',
            'statut' => 'required|in:en_attente,validee,livree,annulee',
            'notes' => 'nullable|string',
        ]);

        Commande::create($request->all());
        return redirect()->route('admin.commandes.index')->with('success', 'Commande créée avec succès');
    }

    public function commandesShow(Commande $commande)
    {
        $commande->load(['franchise', 'entrepot', 'produits']);
        return view('admin.commandes.show', compact('commande'));
    }

    public function commandesEdit(Commande $commande)
    {
        $commande->load(['franchise', 'entrepot', 'produits']);
        $franchises = Franchise::where('statut', 'actif')->get();
        $entrepots = Entrepot::all();
        return view('admin.commandes.edit', compact('commande', 'franchises', 'entrepots'));
    }

    public function commandesUpdate(Request $request, Commande $commande)
    {
        $request->validate([
            'franchise_id' => 'required|exists:franchises,id',
            'entrepot_id' => 'required|exists:entrepots,id',
            'date_commande' => 'required|date',
            'statut' => 'required|in:en_attente,validee,livree,annulee',
            'notes' => 'nullable|string',
        ]);

        $commande->update($request->all());
        return redirect()->route('admin.commandes.index')->with('success', 'Commande mise à jour avec succès');
    }

    public function commandesDestroy(Commande $commande)
    {
        $commande->delete();
        return redirect()->route('admin.commandes.index')->with('success', 'Commande supprimée avec succès');
    }

    public function commandesValidate(Commande $commande)
    {
        $commande->update(['statut' => 'validee']);
        return redirect()->route('admin.commandes.index')->with('success', 'Commande validée avec succès');
    }

    public function commandesRefuse(Request $request, Commande $commande)
    {
        $commande->update([
            'statut' => 'refusee',
        ]);

        return redirect()->route('admin.commandes.index')->with('success', 'Commande refusée avec succès');
    }

    public function commandesDeliver(Commande $commande)
    {
        $commande->update(['statut' => 'livree']);
        return redirect()->route('admin.commandes.index')->with('success', 'Commande marquée comme livrée');
    }

    public function commandesDownload(Commande $commande)
    {
        try {
            // Charger les relations nécessaires
            $commande->load(['franchise', 'entrepot', 'produits']);
            
            // Vérifier que les données nécessaires existent
            if (!$commande->franchise || !$commande->entrepot) {
                throw new \Exception('Données manquantes pour générer le PDF');
            }
            
            // Calculer les totaux si pas déjà calculés
            if (!isset($commande->total_obligatoire)) {
                $commande->total_obligatoire = $commande->produits()
                    ->where('produits.obligatoire', true)
                    ->get()
                    ->sum(function($produit) {
                        return ($produit->pivot->prix_unitaire ?? 0) * ($produit->pivot->quantite ?? 0);
                    });
            }
            
            if (!isset($commande->total_libre)) {
                $commande->total_libre = $commande->produits()
                    ->where('produits.obligatoire', false)
                    ->get()
                    ->sum(function($produit) {
                        return ($produit->pivot->prix_unitaire ?? 0) * ($produit->pivot->quantite ?? 0);
                    });
            }
            
            if (!isset($commande->total_commande)) {
                $commande->total_commande = $commande->total_obligatoire + $commande->total_libre;
            }
            
            // Générer le PDF avec le template complet
            $pdf = Pdf::loadView('pdf.commande', compact('commande'));
            $pdf->setPaper('A4', 'portrait');
            $pdf->setOptions([
                'isHtml5ParserEnabled' => true,
                'isRemoteEnabled' => true,
                'defaultFont' => 'Arial'
            ]);
            
            return $pdf->download('commande_' . $commande->id . '_' . now()->format('Y-m-d') . '.pdf');
        } catch (\Exception $e) {
            \Log::error('Erreur génération PDF commande ' . $commande->id . ': ' . $e->getMessage());
            return redirect()->route('admin.commandes.show', $commande)
                ->with('error', 'Erreur lors de la génération du PDF: ' . $e->getMessage());
        }
    }

    public function produits()
    {
        $produits = Produit::paginate(15);
        return view('admin.produits.index', compact('produits'));
    }

    public function produitsCreate()
    {
        return view('admin.produits.create');
    }

    public function produitsStore(Request $request)
    {
        $request->validate([
            'nom' => 'required|string|max:255',
            'description' => 'required|string',
            'prix_unitaire' => 'required|numeric|min:0',
            'categorie' => 'required|in:viande,legumes,boissons,epices,emballages,autres',
            'unite_mesure' => 'required|string|max:50',
            'obligatoire' => 'boolean',
            'image' => 'nullable|file|mimes:jpeg,png,jpg,gif,webp|max:5120',
        ], [
            'image.mimes' => 'Le fichier doit être une image (JPEG, PNG, JPG, GIF, WEBP)',
            'image.max' => 'L\'image ne doit pas dépasser 5MB',
        ]);

        $data = $request->all();
        
        // Gestion de l'upload d'image
        if ($request->hasFile('image')) {
            $image = $request->file('image');
            $imageName = time() . '_' . $image->getClientOriginalName();
            $image->storeAs('public/produits', $imageName);
            $data['image'] = 'produits/' . $imageName;
        }

        Produit::create($data);
        return redirect()->route('admin.produits.index')->with('success', 'Produit créé avec succès');
    }

    public function produitsShow(Produit $produit)
    {
        return view('admin.produits.show', compact('produit'));
    }

    public function produitsEdit(Produit $produit)
    {
        return view('admin.produits.edit', compact('produit'));
    }

    public function produitsUpdate(Request $request, Produit $produit)
    {
        $request->validate([
            'nom' => 'required|string|max:255',
            'description' => 'required|string',
            'prix_unitaire' => 'required|numeric|min:0',
            'categorie' => 'required|in:viande,legumes,boissons,epices,emballages,autres',
            'unite_mesure' => 'required|string|max:50',
            'obligatoire' => 'boolean',
            'image' => 'nullable|file|mimes:jpeg,png,jpg,gif,webp|max:5120',
        ]);

        $data = $request->all();
        
        // Gestion de l'upload d'image
        if ($request->hasFile('image')) {
            // Supprimer l'ancienne image si elle existe
            if ($produit->image && Storage::disk('public')->exists($produit->image)) {
                Storage::disk('public')->delete($produit->image);
            }
            
            $image = $request->file('image');
            $imageName = time() . '_' . $image->getClientOriginalName();
            $image->storeAs('public/produits', $imageName);
            $data['image'] = 'produits/' . $imageName;
        }

        $produit->update($data);
        return redirect()->route('admin.produits.index')->with('success', 'Produit mis à jour avec succès');
    }

    public function produitsDestroy(Produit $produit)
    {
        // Supprimer l'image si elle existe
        if ($produit->image && Storage::disk('public')->exists($produit->image)) {
            Storage::disk('public')->delete($produit->image);
        }
        
        $produit->delete();
        return redirect()->route('admin.produits.index')->with('success', 'Produit supprimé avec succès');
    }

    // Gestion des menus
    public function menus()
    {
        $menus = Menu::orderBy('ordre_affichage')->paginate(15);
        return view('admin.menus.index', compact('menus'));
    }

    public function menusCreate()
    {
        return view('admin.menus.create');
    }

    public function menusStore(Request $request)
    {
        $request->validate([
            'nom' => 'required|string|max:255',
            'description' => 'required|string',
            'prix' => 'required|numeric|min:0',
            'categorie' => 'required|in:burger,boisson,dessert,accompagnement,entree',
            'image' => 'nullable|file|mimes:jpeg,png,jpg,gif,webp|max:5120',
            'disponible' => 'boolean',
            'special' => 'boolean',
            'ordre_affichage' => 'nullable|integer|min:0',
        ], [
            'image.mimes' => 'Le fichier doit être une image (JPEG, PNG, JPG, GIF, WEBP)',
            'image.max' => 'L\'image ne doit pas dépasser 5MB',
        ]);

        $data = $request->all();
        
        // Gestion de l'upload d'image
        if ($request->hasFile('image')) {
            $image = $request->file('image');
            $imageName = time() . '_' . $image->getClientOriginalName();
            $image->storeAs('public/menus', $imageName);
            $data['image'] = 'menus/' . $imageName;
        }

        Menu::create($data);
        return redirect()->route('admin.menus.index')->with('success', 'Menu créé avec succès');
    }

    public function menusShow(Menu $menu)
    {
        return view('admin.menus.show', compact('menu'));
    }

    public function menusEdit(Menu $menu)
    {
        return view('admin.menus.edit', compact('menu'));
    }

    public function menusUpdate(Request $request, Menu $menu)
    {
        $request->validate([
            'nom' => 'required|string|max:255',
            'description' => 'required|string',
            'prix' => 'required|numeric|min:0',
            'categorie' => 'required|in:burger,boisson,dessert,accompagnement,entree',
            'image' => 'nullable|file|mimes:jpeg,png,jpg,gif,webp|max:5120',
            'disponible' => 'boolean',
            'special' => 'boolean',
            'ordre_affichage' => 'nullable|integer|min:0',
        ], [
            'image.mimes' => 'Le fichier doit être une image (JPEG, PNG, JPG, GIF, WEBP)',
            'image.max' => 'L\'image ne doit pas dépasser 5MB',
        ]);

        $data = $request->all();
        
        // Gestion de l'upload d'image
        if ($request->hasFile('image')) {
            // Supprimer l'ancienne image si elle existe
            if ($menu->image && Storage::disk('public')->exists($menu->image)) {
                Storage::disk('public')->delete($menu->image);
            }
            
            $image = $request->file('image');
            $imageName = time() . '_' . $image->getClientOriginalName();
            $image->storeAs('public/menus', $imageName);
            $data['image'] = 'menus/' . $imageName;
        }

        $menu->update($data);
        return redirect()->route('admin.menus.index')->with('success', 'Menu mis à jour avec succès');
    }

    public function menusDestroy(Menu $menu)
    {
        // Supprimer l'image si elle existe
        if ($menu->image && Storage::disk('public')->exists($menu->image)) {
            Storage::disk('public')->delete($menu->image);
        }
        
        $menu->delete();
        return redirect()->route('admin.menus.index')->with('success', 'Menu supprimé avec succès');
    }

    public function statistiques(Request $request)
    {
        $mois_courant = now()->month;
        $annee_courante = now()->year;
        $mois_precedent = now()->subMonth()->month;

        // Filtres
        $periode = $request->get('periode', 'tout');
        $franchise_nom = $request->get('franchise_nom', '');

        // Query builder pour les ventes avec filtres
        $ventesQuery = Vente::query();

        // Appliquer les filtres de période
        if ($periode !== 'tout') {
            switch ($periode) {
                case 'mois':
                    $ventesQuery->whereMonth('date_vente', $mois_courant)
                                ->whereYear('date_vente', $annee_courante);
                    break;
                case 'trimestre':
                    $ventesQuery->whereBetween('date_vente', [
                        now()->startOfQuarter(),
                        now()->endOfQuarter()
                    ]);
                    break;
                case 'annee':
                    $ventesQuery->whereYear('date_vente', $annee_courante);
                    break;
            }
        }

        // Appliquer le filtre par nom de franchisé
        if (!empty($franchise_nom)) {
            $ventesQuery->whereHas('franchise', function ($query) use ($franchise_nom) {
                $query->where('nom_complet', 'LIKE', "%{$franchise_nom}%");
            });
        }

        // Statistiques générales
        $ca_total = $ventesQuery->sum('montant_total');
        $ca_mois_courant = Vente::whereMonth('date_vente', $mois_courant)
                                ->whereYear('date_vente', $annee_courante)
                                ->sum('montant_total');
        $ca_mois_precedent = Vente::whereMonth('date_vente', $mois_precedent)
                                  ->whereYear('date_vente', $annee_courante)
                                  ->sum('montant_total');
        
        $croissance_ca = $ca_mois_precedent > 0 ? 
            round((($ca_mois_courant - $ca_mois_precedent) / $ca_mois_precedent) * 100, 1) : 0;

        // Calcul de la moyenne sur 6 mois
        $ca_moyenne_6mois = 0;
        $total_6mois = 0;
        for ($i = 5; $i >= 0; $i--) {
            $date = now()->subMonths($i);
            $ca_mois = Vente::whereMonth('date_vente', $date->month)
                           ->whereYear('date_vente', $date->year)
                           ->sum('montant_total');
            $total_6mois += $ca_mois;
        }
        $ca_moyenne_6mois = round($total_6mois / 6, 0);

        // Reversements
        $total_reverse = $ventesQuery->sum('montant_reverse');
        $reverse_mois_courant = Vente::whereMonth('date_vente', $mois_courant)
                                    ->whereYear('date_vente', $annee_courante)
                                    ->sum('montant_reverse');
        $reverse_mois_precedent = Vente::whereMonth('date_vente', $mois_precedent)
                                      ->whereYear('date_vente', $annee_courante)
                                      ->sum('montant_reverse');
        
        $evolution_reverse = $reverse_mois_precedent > 0 ? 
            round((($reverse_mois_courant - $reverse_mois_precedent) / $reverse_mois_precedent) * 100, 1) : 0;
        
        $pourcentage_reverse = $ca_total > 0 ? round(($total_reverse / $ca_total) * 100, 1) : 0;

        // Commandes
        $total_commandes = Commande::count();
        $commandes_mois_courant = Commande::whereMonth('created_at', $mois_courant)
                                         ->whereYear('created_at', $annee_courante)
                                         ->count();
        $commandes_mois_precedent = Commande::whereMonth('created_at', $mois_precedent)
                                           ->whereYear('created_at', $annee_courante)
                                           ->count();
        
        $evolution_commandes = $commandes_mois_precedent > 0 ? 
            round((($commandes_mois_courant - $commandes_mois_precedent) / $commandes_mois_precedent) * 100, 1) : 0;

        $stats = [
            'total_franchises' => Franchise::count(),
            'franchises_actifs' => Franchise::where('statut', 'actif')->count(),
            'ca_total' => $ca_total,
            'ca_mois_courant' => $ca_mois_courant,
            'ca_mois_precedent' => $ca_mois_precedent,
            'ca_moyenne_6mois' => $ca_moyenne_6mois,
            'croissance_ca' => $croissance_ca,
            'total_reverse' => $total_reverse,
            'evolution_reverse' => $evolution_reverse,
            'pourcentage_reverse' => $pourcentage_reverse,
            'total_commandes' => $total_commandes,
            'commandes_en_attente' => Commande::where('statut', 'en_attente')->count(),
            'evolution_commandes' => $evolution_commandes,
        ];

        // Franchisés actifs pour la sélection
        $active_franchises = Franchise::where('statut', 'actif')->with('ventes')->get();

        // Tous les franchisés pour les statistiques générales
        $all_franchises = Franchise::with('ventes')->get();

        // Top 10 des franchisés avec leurs ventes
        $top_franchises = $all_franchises
            ->map(function ($franchise) use ($ventesQuery) {
                $franchise->ventes_sum_montant_total = $franchise->ventes->sum('montant_total');
                $franchise->ventes_sum_montant_reverse = $franchise->ventes->sum('montant_reverse');
                $franchise->ventes_count = $franchise->ventes->count();
                return $franchise;
            })
            ->sortByDesc('ventes_sum_montant_total')
            ->values()
            ->take(10);

        // Statistiques par région (basées sur les villes) - seulement franchisés actifs
        $stats_par_region = $active_franchises
            ->groupBy('ville')
            ->map(function ($franchises, $ville) {
                $ca_region = $franchises->sum(function ($franchise) {
                    return $franchise->ventes->sum('montant_total');
                });
                
                return [
                    'franchises' => $franchises->count(),
                    'ca' => $ca_region,
                    'croissance' => rand(5, 25), // Placeholder pour la croissance
                ];
            })
            ->sortByDesc('ca')
            ->take(5);

        // Produits les plus commandés
        $produits_populaires = Produit::withCount('commandes')
            ->orderByDesc('commandes_count')
            ->take(5)
            ->get()
            ->map(function ($produit) {
                $produit->prix_formate = number_format($produit->prix_unitaire, 2, ',', ' ') . ' €';
                return $produit;
            });

        return view('admin.statistiques', compact(
            'stats', 
            'top_franchises', 
            'stats_par_region', 
            'produits_populaires',
            'active_franchises'
        ));
    }

    public function exportStatistiquesPDF(Request $request)
    {
        // Récupérer les mêmes données que pour la page statistiques
        $mois_courant = now()->month;
        $annee_courante = now()->year;
        $mois_precedent = $mois_courant - 1;
        if ($mois_precedent < 1) {
            $mois_precedent = 12;
            $annee_courante--;
        }

        // Statistiques générales
        $total_franchises = Franchise::count();
        $franchises_actifs = Franchise::where('statut', 'actif')->count();
        
        // CA et croissance
        $ca_total = Vente::sum('montant_total');
        $ca_mois_courant = Vente::whereMonth('date_vente', $mois_courant)->whereYear('date_vente', $annee_courante)->sum('montant_total');
        $ca_mois_precedent = Vente::whereMonth('date_vente', $mois_precedent)->whereYear('date_vente', $annee_courante)->sum('montant_total');
        $croissance_ca = $ca_mois_precedent > 0 ? round((($ca_mois_courant - $ca_mois_precedent) / $ca_mois_precedent) * 100, 1) : 0;
        
        // CA moyenne 6 mois
        $ca_moyenne_6mois = Vente::where('date_vente', '>=', now()->subMonths(6))->avg('montant_total') * Vente::where('date_vente', '>=', now()->subMonths(6))->count();
        
        // Reversements
        $total_reverse = Vente::sum('montant_reverse');
        $pourcentage_reverse = $ca_total > 0 ? round(($total_reverse / $ca_total) * 100, 1) : 0;
        $reverse_mois_courant = Vente::whereMonth('date_vente', $mois_courant)->whereYear('date_vente', $annee_courante)->sum('montant_reverse');
        $reverse_mois_precedent = Vente::whereMonth('date_vente', $mois_precedent)->whereYear('date_vente', $annee_courante)->sum('montant_reverse');
        $evolution_reverse = $reverse_mois_precedent > 0 ? round((($reverse_mois_courant - $reverse_mois_precedent) / $reverse_mois_precedent) * 100, 1) : 0;
        
        // Commandes
        $total_commandes = Commande::count();
        $commandes_en_attente = Commande::where('statut', 'en_attente')->count();
        $commandes_mois_courant = Commande::whereMonth('date_commande', $mois_courant)->whereYear('date_commande', $annee_courante)->count();
        $commandes_mois_precedent = Commande::whereMonth('date_commande', $mois_precedent)->whereYear('date_commande', $annee_courante)->count();
        $evolution_commandes = $commandes_mois_precedent > 0 ? round((($commandes_mois_courant - $commandes_mois_precedent) / $commandes_mois_precedent) * 100, 1) : 0;

        // Top 10 franchises
        $all_franchises = Franchise::with('ventes')->get();
        $top_franchises = $all_franchises
            ->map(function ($franchise) {
                $franchise->ventes_sum_montant_total = $franchise->ventes->sum('montant_total');
                $franchise->ventes_sum_montant_reverse = $franchise->ventes->sum('montant_reverse');
                $franchise->ventes_count = $franchise->ventes->count();
                return $franchise;
            })
            ->sortByDesc('ventes_sum_montant_total')
            ->values()
            ->take(10);

        // Produits populaires
        $produits_populaires = Produit::withCount('commandes')
            ->orderBy('commandes_count', 'desc')
            ->take(10)
            ->get()
            ->map(function ($produit) {
                $produit->prix_formate = number_format($produit->prix, 2, ',', ' ') . ' €';
                $produit->categorie_label = ucfirst($produit->categorie);
                return $produit;
            });

        $data = [
            'date_generation' => now()->format('d/m/Y à H:i'),
            'total_franchises' => $total_franchises,
            'franchises_actifs' => $franchises_actifs,
            'ca_total' => $ca_total,
            'croissance_ca' => $croissance_ca,
            'ca_mois_courant' => $ca_mois_courant,
            'ca_mois_precedent' => $ca_mois_precedent,
            'ca_moyenne_6mois' => $ca_moyenne_6mois,
            'total_reverse' => $total_reverse,
            'pourcentage_reverse' => $pourcentage_reverse,
            'evolution_reverse' => $evolution_reverse,
            'total_commandes' => $total_commandes,
            'commandes_en_attente' => $commandes_en_attente,
            'evolution_commandes' => $evolution_commandes,
            'top_franchises' => $top_franchises,
            'produits_populaires' => $produits_populaires,
        ];

        $pdf = PDF::loadView('pdf.statistiques', $data);
        
        return $pdf->download('statistiques-drivncook-' . now()->format('Y-m-d') . '.pdf');
    }

    // Notifications de panne
    public function notificationsPannes(Request $request)
    {
        $query = NotificationPanne::with(['franchise', 'camion']);

        if ($request->filled('statut')) {
            $query->where('statut', $request->statut);
        }
        if ($request->filled('gravite')) {
            $query->where('gravite', $request->gravite);
        }
        if ($request->filled('type_panne')) {
            $query->where('type_panne', $request->type_panne);
        }
        if ($request->filled('franchise')) {
            $query->where('franchise_id', $request->franchise);
        }

        $notifications = $query->latest()->paginate(15)->withQueryString();
        $franchises = Franchise::all();

        return view('admin.notifications-pannes.index', compact('notifications', 'franchises'));
    }

    public function notificationsPannesShow(NotificationPanne $notification)
    {
        return view('admin.notifications-pannes.show', compact('notification'));
    }

    public function notificationsPannesEdit(NotificationPanne $notification)
    {
        return view('admin.notifications-pannes.edit', compact('notification'));
    }

    public function notificationsPannesUpdate(Request $request, NotificationPanne $notification)
    {
        $rules = [
            'statut' => 'required|in:signalee,en_maintenance,resolue,ignoree',
            'commentaire_admin' => 'nullable|string',
        ];

        // Ajouter la validation du camion de remplacement seulement si la checkbox est cochée
        if ($request->has('attribuer_remplacement') && $request->attribuer_remplacement == '1') {
            $rules['camion_remplacement'] = 'required|exists:camions,id';
        }

        $request->validate($rules);

        $data = [
            'statut' => $request->statut,
            'commentaire_admin' => $request->commentaire_admin,
        ];

        // Si la panne est résolue, ajouter la date de résolution
        if ($request->statut === 'resolue') {
            $data['date_resolution'] = now();
        }

        $notification->update($data);

        // Gérer le statut du camion
        if ($request->has('mettre_camion_maintenance')) {
            $notification->camion->update(['statut' => 'en_maintenance']);
        } elseif ($request->statut === 'resolue') {
            $notification->camion->update(['statut' => 'en_utilisation']);
        }

        // Attribuer un camion de remplacement si demandé
        if ($request->has('attribuer_remplacement') && $request->attribuer_remplacement == '1' && $request->filled('camion_remplacement')) {
            $camionRemplacement = Camion::find($request->camion_remplacement);
            
            if ($camionRemplacement && $camionRemplacement->statut === 'disponible') {
                // Retirer l'ancien camion du franchisé
                $notification->franchise->camions()->detach($notification->camion_id);
                
                // Attribuer le nouveau camion
                $notification->franchise->camions()->attach($camionRemplacement->id, [
                    'date_attribution' => now(),
                    'statut' => 'actif'
                ]);
                
                // Mettre le nouveau camion en utilisation
                $camionRemplacement->update(['statut' => 'en_utilisation']);
                
                // Ajouter un commentaire sur l'attribution
                $data['commentaire_admin'] = ($data['commentaire_admin'] ?? '') . "\n\nCamion de remplacement attribué : " . $camionRemplacement->immatriculation;
                $notification->update($data);
            }
        }

        return redirect()->route('admin.notifications-pannes.show', $notification)
                        ->with('success', 'Panne traitée avec succès.');
    }

    // Demandes de camion
    public function demandesCamions(Request $request)
    {
        $query = DemandeCamion::with(['franchise', 'camion']);

        if ($request->filled('statut')) {
            $query->where('statut', $request->statut);
        }
        if ($request->filled('type_demande')) {
            $query->where('type_demande', $request->type_demande);
        }
        if ($request->filled('urgent')) {
            $query->where('urgent', $request->urgent);
        }
        if ($request->filled('franchise')) {
            $query->where('franchise_id', $request->franchise);
        }

        $demandes = $query->latest()->paginate(15)->withQueryString();
        $franchises = Franchise::all();

        return view('admin.demandes-camions.index', compact('demandes', 'franchises'));
    }

    public function demandesCamionsShow(DemandeCamion $demande)
    {
        return view('admin.demandes-camions.show', compact('demande'));
    }

    public function demandesCamionsEdit(DemandeCamion $demande)
    {
        $camionsDisponibles = Camion::where('statut', 'disponible')->get();
        return view('admin.demandes-camions.edit', compact('demande', 'camionsDisponibles'));
    }

    public function demandesCamionsUpdate(Request $request, DemandeCamion $demande)
    {
        $rules = [
            'statut' => 'required|in:en_attente,approuvee,refusee',
            'commentaire_admin' => 'nullable|string',
            'camion_attribue' => 'nullable|exists:camions,id',
        ];

        if ($request->statut === 'approuvee') {
            $rules['camion_attribue'] = 'required|exists:camions,id';
        }

        $request->validate($rules);

        DB::beginTransaction();
        try {
            $demande->update([
                'statut' => $request->statut,
                'commentaire_admin' => $request->commentaire_admin,
                'date_reponse' => now(),
            ]);

            if ($request->statut === 'approuvee' && $request->camion_attribue) {
                $camion = Camion::find($request->camion_attribue);

                // Detach old truck if it was a replacement request
                if ($demande->type_demande === 'remplacement' && $demande->camion_id) {
                    $demande->franchise->camions()->updateExistingPivot($demande->camion_id, ['statut' => 'inactif']);
                    $demande->camion->update(['statut' => 'en_maintenance']); // Old truck goes to maintenance
                }

                // Attach new truck to franchisee
                $demande->franchise->camions()->attach($camion->id, [
                    'date_attribution' => now(),
                    'statut' => 'actif' // Corrected to 'actif' for franchise_camion pivot
                ]);
                $camion->update(['statut' => 'en_utilisation']); // New truck goes to 'en_utilisation'
            }

            DB::commit();
            return redirect()->route('admin.demandes-camions.show', $demande)->with('success', 'Demande de camion mise à jour avec succès.');
        } catch (\Exception $e) {
            DB::rollback();
            return back()->with('error', 'Erreur lors de la mise à jour de la demande : ' . $e->getMessage());
        }
    }

    public function demandesCamionsCreate(Request $request)
    {
        $franchise = null;
        $urgence = $request->get('urgence');
        
        if ($request->filled('franchise')) {
            $franchise = Franchise::find($request->franchise);
        }
        
        $franchises = Franchise::all();
        $camionsDisponibles = Camion::where('statut', 'disponible')->get();
        
        return view('admin.demandes-camions.create', compact('franchises', 'camionsDisponibles', 'franchise', 'urgence'));
    }

    public function demandesCamionsStore(Request $request)
    {
        $request->validate([
            'franchise_id' => 'required|exists:franchises,id',
            'type_demande' => 'required|in:nouveau,remplacement',
            'type_camion_souhaite' => 'required|string',
            'localisation_souhaitee' => 'required|string',
            'date_debut_souhaitee' => 'required|date',
            'duree_attribution' => 'required|in:temporaire,semaine,mois,permanent',
            'motif' => 'required|string|min:10',
            'urgent' => 'boolean',
        ]);

        DemandeCamion::create([
            'franchise_id' => $request->franchise_id,
            'camion_id' => $request->camion_id,
            'type_demande' => $request->type_demande,
            'type_camion_souhaite' => $request->type_camion_souhaite,
            'localisation_souhaitee' => $request->localisation_souhaitee,
            'date_debut_souhaitee' => $request->date_debut_souhaitee,
            'duree_attribution' => $request->duree_attribution,
            'motif' => $request->motif,
            'urgent' => $request->has('urgent'),
        ]);

        return redirect()->route('admin.demandes-camions.index')
                        ->with('success', 'Demande de camion créée avec succès.');
    }

    public function franchisesCamionsDisponibles(Franchise $franchise)
    {
        $camions = Camion::where('statut', 'disponible')
            ->select('id', 'immatriculation', 'marque', 'modele', 'annee', 'ville_localisation')
            ->get();
        
        return response()->json(['camions' => $camions]);
    }

    public function franchisesDestroy(Franchise $franchise)
    {
        try {
            // Retirer tous les camions attribués
            $franchise->camions()->detach();
            
            // Supprimer le franchisé
            $franchise->delete();
            
            return redirect()->route('admin.franchises.index')
                ->with('success', 'Franchisé supprimé avec succès');
        } catch (\Exception $e) {
            return redirect()->route('admin.franchises.index')
                ->with('error', 'Erreur lors de la suppression du franchisé : ' . $e->getMessage());
        }
    }

    // Gestion des commandes clients (Admin)
    public function commandesClients(Request $request)
    {
        $query = \App\Models\CommandeClient::with(['client', 'franchise', 'menus']);
        
        // Recherche par email ou téléphone
        if ($request->filled('recherche')) {
            $recherche = $request->get('recherche');
            $query->whereHas('client', function($q) use ($recherche) {
                $q->where('email', 'LIKE', "%{$recherche}%")
                  ->orWhere('telephone', 'LIKE', "%{$recherche}%");
            });
        }
        
        // Tri par email du client ou statut
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
            case 'statut_asc':
                $query->orderBy('statut', 'asc');
                break;
            case 'statut_desc':
                $query->orderBy('statut', 'desc');
                break;
            case 'date_desc':
            default:
                $query->orderBy('date_commande', 'desc');
                break;
        }
        
        $commandes = $query->paginate(15)->withQueryString();
        
        $stats = [
            'total' => \App\Models\CommandeClient::count(),
            'en_attente' => \App\Models\CommandeClient::where('statut', 'en_attente')->count(),
            'confirmee' => \App\Models\CommandeClient::where('statut', 'confirmee')->count(),
            'en_preparation' => \App\Models\CommandeClient::where('statut', 'en_preparation')->count(),
            'prete' => \App\Models\CommandeClient::where('statut', 'prete')->count(),
            'terminee' => \App\Models\CommandeClient::where('statut', 'terminee')->count(),
            'annulee' => \App\Models\CommandeClient::where('statut', 'annulee')->count(),
        ];
        
        return view('admin.commandes-clients.index', compact('commandes', 'stats'));
    }

    public function commandesClientsShow(\App\Models\CommandeClient $commande)
    {
        $commande->load(['client', 'franchise', 'menus']);
        return view('admin.commandes-clients.show', compact('commande'));
    }

    public function commandesClientsDestroy(\App\Models\CommandeClient $commande)
    {
        try {
            // Vérifier que la commande peut être supprimée (en attente ou confirmée)
            if (!in_array($commande->statut, ['en_attente', 'confirmee'])) {
                return redirect()->route('admin.commandes-clients.show', $commande)
                    ->with('error', 'Seules les commandes en attente ou confirmées peuvent être supprimées');
            }

            // Supprimer les relations avec les menus
            $commande->menus()->detach();
            
            // Supprimer la commande
            $commande->delete();
            
            return redirect()->route('admin.commandes-clients.index')
                ->with('success', 'Commande client supprimée avec succès');
        } catch (\Exception $e) {
            return redirect()->route('admin.commandes-clients.show', $commande)
                ->with('error', 'Erreur lors de la suppression de la commande : ' . $e->getMessage());
        }
    }

    // Gestion de l'assignation de camions aux franchisés
    public function assignerCamion(Franchise $franchise)
    {
        // Récupérer tous les camions disponibles
        $camionsDisponibles = Camion::where('statut', 'disponible')->get();
        
        // Récupérer le camion actuel du franchisé
        $camionActuel = $franchise->getCamionActuel();
        
        return view('admin.franchises.assigner-camion', compact('franchise', 'camionsDisponibles', 'camionActuel'));
    }

    public function assignerCamionStore(Request $request, Franchise $franchise)
    {
        $request->validate([
            'camion_id' => 'required|exists:camions,id'
        ]);

        try {
            // Vérifier que le camion est disponible
            $camion = Camion::findOrFail($request->camion_id);
            
            if ($camion->statut !== 'disponible') {
                return redirect()->route('admin.franchises.assigner-camion', $franchise)
                    ->with('error', 'Ce camion n\'est pas disponible');
            }

            // Assigner le camion au franchisé
            $franchise->assignerCamion($request->camion_id);
            
            // Mettre à jour le statut du camion
            $camion->update(['statut' => 'en_utilisation']);

            return redirect()->route('admin.franchises.show', $franchise)
                ->with('success', 'Camion assigné avec succès au franchisé');
        } catch (\Exception $e) {
            return redirect()->route('admin.franchises.assigner-camion', $franchise)
                ->with('error', 'Erreur lors de l\'assignation du camion : ' . $e->getMessage());
        }
    }

    public function retirerCamion(Franchise $franchise)
    {
        try {
            $camionActuel = $franchise->getCamionActuel();
            
            if (!$camionActuel) {
                return redirect()->route('admin.franchises.show', $franchise)
                    ->with('error', 'Ce franchisé n\'a pas de camion assigné');
            }

            // Retirer le camion du franchisé
            $franchise->retirerCamion();
            
            // Remettre le camion en statut disponible
            $camionActuel->update(['statut' => 'disponible']);

            return redirect()->route('admin.franchises.show', $franchise)
                ->with('success', 'Camion retiré avec succès du franchisé');
        } catch (\Exception $e) {
            return redirect()->route('admin.franchises.show', $franchise)
                ->with('error', 'Erreur lors du retrait du camion : ' . $e->getMessage());
        }
    }
} 