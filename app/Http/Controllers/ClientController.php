<?php

namespace App\Http\Controllers;

use App\Models\Client;
use App\Models\Menu;
use App\Models\CommandeClient;
use App\Models\Franchise;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Carbon\Carbon;

class ClientController extends Controller
{
    public function index()
    {
        $menus = Menu::disponible()->orderBy('ordre_affichage')->get();
        $categories = $menus->groupBy('categorie');
        
        return view('client.index', compact('menus', 'categories'));
    }

    public function selectFoodTruckPage()
    {
        $foodTrucks = Franchise::disponible()
            ->with(['camions' => function($query) {
                $query->where('franchise_camion.statut', 'actif');
            }])
            ->whereHas('camions', function($query) {
                $query->where('franchise_camion.statut', 'actif');
            })
            ->get()
            ->map(function($franchise) {
                $franchise->camions_actifs_count = $franchise->camions->count();
                $franchise->camions_immatriculations = $franchise->camions->pluck('immatriculation')->implode(', ');
                $franchise->nom_complet = $franchise->prenom . ' ' . $franchise->nom;
                return $franchise;
            });
        
        return view('client.select-food-truck', compact('foodTrucks'));
    }

    public function selectFoodTruck(Request $request)
    {
        $request->validate([
            'food_truck_id' => 'required|exists:franchises,id'
        ]);

        $foodTruck = Franchise::findOrFail($request->food_truck_id);
        
        if (!$foodTruck->disponible) {
            return response()->json([
                'success' => false,
                'message' => 'Ce food truck n\'est plus disponible'
            ], 400);
        }

        if (!$foodTruck->hasCamionsActifs()) {
            return response()->json([
                'success' => false,
                'message' => 'Ce food truck n\'a pas de camion disponible pour le moment'
            ], 400);
        }

        // Stocker le food truck sélectionné en session
        session(['selected_food_truck_id' => $foodTruck->id]);

        return response()->json([
            'success' => true,
            'message' => 'Food truck sélectionné avec succès',
            'food_truck' => [
                'id' => $foodTruck->id,
                'nom' => $foodTruck->nom_complet,
                'adresse' => $foodTruck->adresse_emplacement ?: $foodTruck->adresse
            ]
        ]);
    }

    public function showMenu($id)
    {
        $menu = Menu::findOrFail($id);
        return view('client.menu.show', compact('menu'));
    }

    public function panier()
    {
        // Récupérer les données du panier depuis localStorage (via JavaScript)
        return view('client.panier');
    }

    public function ajouterAuPanier(Request $request)
    {
        // Si c'est une requête AJAX avec des données de panier complètes
        if ($request->has('panier_data')) {
            $panierData = $request->input('panier_data');
            $reductionFidelite = $request->input('reduction_fidelite', 0);
            
            // Stocker le panier complet dans la session
            $request->session()->put('panier', $panierData);
            
            // Stocker la réduction fidélité dans la session
            $request->session()->put('reduction_fidelite', $reductionFidelite);
            
            return response()->json([
                'success' => true,
                'message' => 'Panier synchronisé',
                'panier_count' => count($panierData)
            ]);
        }
        
        // Sinon, ajout d'un article individuel
        $request->validate([
            'menu_id' => 'required|exists:menus,id',
            'quantite' => 'required|integer|min:1',
        ]);

        $menu = Menu::findOrFail($request->menu_id);
        
        // Stocker dans la session pour Stripe
        $panier = $request->session()->get('panier', []);
        
        $menuId = $menu->id;
        if (isset($panier[$menuId])) {
            $panier[$menuId]['quantite'] += $request->quantite;
        } else {
            $panier[$menuId] = [
                'id' => $menu->id,
                'nom' => $menu->nom,
                'prix' => $menu->prix,
                'quantite' => $request->quantite,
            ];
        }
        
        $request->session()->put('panier', $panier);
        
        return response()->json([
            'success' => true,
            'message' => 'Article ajouté au panier',
            'panier_count' => count($panier)
        ]);
    }

    public function commandes(Request $request)
    {
        $client = Auth::guard('client')->user();
        
        if (!$client) {
            return redirect()->route('login')->with('error', 'Veuillez vous connecter pour accéder à vos commandes.');
        }
        
        $query = $client->commandes()->with('franchise');
        
        // Tri
        $tri = $request->get('tri', 'date_desc');
        switch ($tri) {
            case 'prix_asc':
                $query->orderBy('montant_final', 'asc');
                break;
            case 'prix_desc':
                $query->orderBy('montant_final', 'desc');
                break;
            case 'date_asc':
                $query->orderBy('date_commande', 'asc');
                break;
            case 'date_desc':
            default:
                $query->orderBy('date_commande', 'desc');
                break;
        }
        
        $commandes = $query->paginate(10)->withQueryString();
        
        return view('client.commandes', compact('commandes'));
    }

    public function showCommande($id)
    {
        $client = Auth::guard('client')->user();
        
        if (!$client) {
            return redirect()->route('login')->with('error', 'Veuillez vous connecter pour accéder à vos commandes.');
        }
        
        $commande = $client->commandes()->with(['franchise', 'menus'])->findOrFail($id);
        
        return view('client.commandes.show', compact('commande'));
    }

    public function profile()
    {
        $client = Auth::guard('client')->user();
        
        if (!$client) {
            return redirect()->route('login')->with('error', 'Veuillez vous connecter pour accéder à votre profil.');
        }
        
        $commandes = $client->commandes()->latest()->get();
        return view('client.profile', compact('client', 'commandes'));
    }

    public function updateProfile(Request $request)
    {
        $client = Auth::guard('client')->user();
        
        if (!$client) {
            return redirect()->route('login')->with('error', 'Veuillez vous connecter pour modifier votre profil.');
        }
        
        if ($request->has('change_password')) {
            // Changement de mot de passe
            $request->validate([
                'current_password' => 'required',
                'new_password' => 'required|string|min:8|confirmed',
            ]);

            if (!Hash::check($request->current_password, $client->password)) {
                return back()->withErrors(['current_password' => 'Le mot de passe actuel est incorrect.']);
            }

            $client->update([
                'password' => Hash::make($request->new_password)
            ]);

            return redirect()->route('client.profile')->with('success', 'Mot de passe modifié avec succès');
        } else {
            // Mise à jour des informations personnelles
            $request->validate([
                'nom' => 'required|string|max:255',
                'prenom' => 'required|string|max:255',
                'email' => 'required|email|unique:clients,email,' . $client->id,
                'telephone' => 'required|string|max:20',
                'adresse' => 'required|string',
                'ville' => 'required|string|max:255',
                'code_postal' => 'required|string|max:10',
                'langue' => 'required|in:fr,en,es',
            ]);

            $data = $request->all();
            $data['newsletter_active'] = $request->has('newsletter_active');
            
            $client->update($data);

            return redirect()->route('client.profile')->with('success', 'Profil mis à jour avec succès');
        }
    }

    public function logout(Request $request)
    {
        Auth::guard('client')->logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        
        return redirect()->route('client.index')->with('success', 'Vous avez été déconnecté avec succès');
    }


}
