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

class AdminController extends Controller
{
    public function dashboard()
    {
        $stats = [
            'total_franchises' => Franchise::count(),
            'franchises_actifs' => Franchise::where('statut', 'actif')->count(),
            'total_camions' => Camion::count(),
            'camions_disponibles' => Camion::where('statut', 'disponible')->count(),
            'total_ventes_mois' => Vente::whereMonth('date_vente', now()->month)->sum('montant_total'),
            'total_reverse_mois' => Vente::whereMonth('date_vente', now()->month)->sum('montant_reverse'),
            'commandes_en_attente' => Commande::where('statut', 'en_attente')->count(),
        ];

        $franchises_recentes = Franchise::latest()->take(5)->get();
        $ventes_recentes = Vente::with('franchise')->latest()->take(10)->get();
        $commandes_recentes = Commande::with(['franchise', 'entrepot'])->latest()->take(10)->get();

        return view('admin.dashboard', compact('stats', 'franchises_recentes', 'ventes_recentes', 'commandes_recentes'));
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

    public function franchisesActivate(Franchise $franchise)
    {
        $franchise->update(['statut' => 'actif']);
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

        // Assigner le camion au franchisé
        $franchise->camions()->attach($camion->id, [
            'date_attribution' => now(),
            'statut' => 'actif'
        ]);

        // Mettre à jour le statut du camion
        $camion->update(['statut' => 'en_utilisation']);

        return redirect()->route('admin.franchises.show', $franchise)->with('success', 'Camion assigné avec succès');
    }

    public function franchisesRemoveCamion(Franchise $franchise, Camion $camion)
    {
        // Retirer l'assignation
        $franchise->camions()->detach($camion->id);

        // Remettre le camion en statut disponible
        $camion->update(['statut' => 'disponible']);

        return redirect()->route('admin.franchises.show', $franchise)->with('success', 'Camion retiré avec succès');
    }

    public function entrepots()
    {
        $entrepots = Entrepot::with('commandes')->get();
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
            'capacite' => 'required|numeric|min:0',
            'cuisine' => 'boolean',
        ]);

        Entrepot::create($request->all());
        return redirect()->route('admin.entrepots.index')->with('success', 'Entrepôt créé avec succès');
    }

    public function entrepotsShow(Entrepot $entrepot)
    {
        return view('admin.entrepots.show', compact('entrepot'));
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
            'capacite' => 'required|numeric|min:0',
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
        $franchises = Franchise::all();
        
        // Statistiques
        $stats = [
            'total_camions' => Camion::count(),
            'en_service' => Camion::where('statut', 'en_utilisation')->count(),
            'en_maintenance' => Camion::where('statut', 'en_maintenance')->count(),
            'assignes' => Camion::whereHas('franchises', function($query) {
                $query->where('franchise_camion.statut', 'actif');
            })->count(),
        ];
        
        return view('admin.camions.index', compact('camions', 'franchises', 'stats'));
    }

    public function camionsAssignFranchise(Request $request, Camion $camion)
    {
        $request->validate([
            'franchise_id' => 'required|exists:franchises,id',
        ]);

        $franchise = Franchise::find($request->franchise_id);
        
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

    public function camionsCreate()
    {
        $franchises = Franchise::where('statut', 'actif')->get();
        return view('admin.camions.create', compact('franchises'));
    }

    public function camionsStore(Request $request)
    {
        $request->validate([
            'immatriculation' => 'required|string|unique:camions',
            'marque' => 'required|string|max:255',
            'modele' => 'required|string|max:255',
            'annee' => 'required|integer|min:1900|max:' . (date('Y') + 1),
            'statut' => 'required|in:disponible,en_service,maintenance,hors_service',
            'ville_localisation' => 'required|string|max:255',
            'notes' => 'nullable|string',
        ]);

        Camion::create($request->all());
        return redirect()->route('admin.camions.index')->with('success', 'Camion créé avec succès');
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
            'immatriculation' => 'required|string|unique:camions,immatriculation,' . $camion->id,
            'marque' => 'required|string|max:255',
            'modele' => 'required|string|max:255',
            'annee' => 'required|integer|min:1900|max:' . (date('Y') + 1),
            'statut' => 'required|in:disponible,en_service,maintenance,hors_service',
            'ville_localisation' => 'required|string|max:255',
            'notes' => 'nullable|string',
        ]);

        $camion->update($request->all());
        return redirect()->route('admin.camions.index')->with('success', 'Camion mis à jour avec succès');
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
        $camions = Camion::where('statut', 'en_service')->get();
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
        $camions = Camion::where('statut', 'en_service')->get();
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
            'description' => 'nullable|string',
            'categorie' => 'required|in:ingredients,plats,boissons',
            'prix_unitaire' => 'required|numeric|min:0',
            'unite_mesure' => 'required|string|max:50',
            'obligatoire' => 'boolean',
        ]);

        Produit::create($request->all());
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
            'description' => 'nullable|string',
            'categorie' => 'required|in:ingredients,plats,boissons',
            'prix_unitaire' => 'required|numeric|min:0',
            'unite_mesure' => 'required|string|max:50',
            'obligatoire' => 'boolean',
        ]);

        $produit->update($request->all());
        return redirect()->route('admin.produits.index')->with('success', 'Produit mis à jour avec succès');
    }

    public function produitsDestroy(Produit $produit)
    {
        $produit->delete();
        return redirect()->route('admin.produits.index')->with('success', 'Produit supprimé avec succès');
    }

    public function statistiques()
    {
        $mois_courant = now()->month;
        $annee_courante = now()->year;

        $stats_ventes = Vente::selectRaw('
            MONTH(date_vente) as mois,
            SUM(montant_total) as total_ventes,
            SUM(montant_reverse) as total_reverse,
            COUNT(*) as nombre_ventes
        ')
        ->whereYear('date_vente', $annee_courante)
        ->groupBy('mois')
        ->orderBy('mois')
        ->get();

        $top_franchises = Franchise::withSum('ventes', 'montant_total')
            ->orderByDesc('ventes_sum_montant_total')
            ->take(10)
            ->get();

        return view('admin.statistiques', compact('stats_ventes', 'top_franchises'));
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
        $request->validate([
            'statut' => 'required|in:signalee,en_maintenance,resolue,ignoree',
            'commentaire_admin' => 'nullable|string',
            'camion_remplacement' => 'required_if:attribuer_remplacement,1|exists:camions,id',
        ]);

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
            $notification->camion->update(['statut' => 'en_service']);
        }

        // Attribuer un camion de remplacement si demandé
        if ($request->has('attribuer_remplacement') && $request->filled('camion_remplacement')) {
            $camionRemplacement = Camion::find($request->camion_remplacement);
            
            if ($camionRemplacement && $camionRemplacement->statut === 'disponible') {
                // Retirer l'ancien camion du franchisé
                $notification->franchise->camions()->detach($notification->camion_id);
                
                // Attribuer le nouveau camion
                $notification->franchise->camions()->attach($camionRemplacement->id, [
                    'date_attribution' => now(),
                    'statut' => 'actif'
                ]);
                
                // Mettre le nouveau camion en service
                $camionRemplacement->update(['statut' => 'en_service']);
                
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
        $request->validate([
            'statut' => 'required|in:en_attente,approuvee,refusee',
            'camion_attribue' => 'required_if:statut,approuvee',
            'commentaire_admin' => 'nullable|string',
        ]);

        $data = [
            'statut' => $request->statut,
            'commentaire_admin' => $request->commentaire_admin,
        ];

        // Si approuvé, ajouter la date de réponse
        if ($request->statut === 'approuvee') {
            $data['date_reponse'] = now();
            
            // Attribuer le camion sélectionné
            if ($request->camion_attribue) {
                $camion = Camion::find($request->camion_attribue);
                if ($camion) {
                    // Retirer l'ancien camion si c'est un remplacement
                    if ($demande->camion_id) {
                        $demande->franchise->camions()->detach($demande->camion_id);
                    }
                    
                    // Attribuer le nouveau camion
                    $demande->franchise->camions()->attach($camion->id, [
                        'date_attribution' => now(),
                        'statut' => 'actif'
                    ]);
                    
                    // Mettre le nouveau camion en service
                    $camion->update(['statut' => 'en_service']);
                }
            }
        } elseif ($request->statut === 'refusee') {
            $data['date_reponse'] = now();
        }

        $demande->update($data);

        return redirect()->route('admin.demandes-camions.show', $demande)
                        ->with('success', 'Demande traitée avec succès.');
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
} 