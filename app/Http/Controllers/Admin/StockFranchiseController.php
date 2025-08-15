<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Franchise;
use App\Models\Produit;
use App\Models\FranchiseProduitStock;
use App\Services\StockService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class StockFranchiseController extends Controller
{
    protected $stockService;

    public function __construct(StockService $stockService)
    {
        $this->stockService = $stockService;
    }

    /**
     * Affiche la liste des stocks d'une franchise
     */
    public function index($franchiseId)
    {
        $franchise = Franchise::findOrFail($franchiseId);
        $stocks = $franchise->stocksProduits()->with('produit')->get();
        
        return view('admin.franchises.stocks.index', compact('franchise', 'stocks'));
    }

    /**
     * Affiche le formulaire pour ajouter/modifier un stock
     */
    public function create($franchiseId)
    {
        $franchise = Franchise::findOrFail($franchiseId);
        $produits = Produit::all();
        
        return view('admin.franchises.stocks.create', compact('franchise', 'produits'));
    }

    /**
     * Enregistre un nouveau stock
     */
    public function store(Request $request, $franchiseId)
    {
        $request->validate([
            'produit_id' => 'required|exists:produits,id',
            'quantite_stock' => 'required|numeric|min:0',
            'stock_minimum' => 'required|numeric|min:0',
        ]);

        try {
            $this->stockService->ajouterStockFranchise(
                $franchiseId,
                $request->produit_id,
                $request->quantite_stock,
                $request->stock_minimum
            );

            return redirect()->route('admin.franchises.stocks.index', $franchiseId)
                            ->with('success', 'Stock ajouté avec succès.');
        } catch (\Exception $e) {
            return back()->withErrors(['error' => 'Erreur lors de l\'ajout du stock: ' . $e->getMessage()]);
        }
    }

    /**
     * Affiche le formulaire d'édition d'un stock
     */
    public function edit($franchiseId, $stockId)
    {
        $franchise = Franchise::findOrFail($franchiseId);
        $stock = FranchiseProduitStock::where('franchise_id', $franchiseId)
                                     ->where('id', $stockId)
                                     ->with('produit')
                                     ->firstOrFail();
        
        return view('admin.franchises.stocks.edit', compact('franchise', 'stock'));
    }

    /**
     * Met à jour un stock
     */
    public function update(Request $request, $franchiseId, $stockId)
    {
        $request->validate([
            'quantite_stock' => 'required|numeric|min:0',
            'stock_minimum' => 'required|numeric|min:0',
        ]);

        try {
            $stock = FranchiseProduitStock::where('franchise_id', $franchiseId)
                                         ->where('id', $stockId)
                                         ->firstOrFail();
            
            $stock->update([
                'quantite_stock' => $request->quantite_stock,
                'stock_minimum' => $request->stock_minimum,
            ]);

            return redirect()->route('admin.franchises.stocks.index', $franchiseId)
                            ->with('success', 'Stock mis à jour avec succès.');
        } catch (\Exception $e) {
            return back()->withErrors(['error' => 'Erreur lors de la mise à jour du stock: ' . $e->getMessage()]);
        }
    }

    /**
     * Affiche les alertes de stock pour toutes les franchises
     */
    public function alertes()
    {
        $alertes = $this->stockService->verifierStocksFranchises();
        
        return view('admin.stocks.alertes-franchises', compact('alertes'));
    }

    /**
     * Affiche le stock d'un produit spécifique dans une franchise
     */
    public function show($franchiseId, $produitId)
    {
        $franchise = Franchise::findOrFail($franchiseId);
        $produit = Produit::findOrFail($produitId);
        $stock = $franchise->getStockProduit($produitId);
        
        return view('admin.franchises.stocks.show', compact('franchise', 'produit', 'stock'));
    }

    /**
     * Ajoute du stock à un produit existant
     */
    public function ajouterStock(Request $request, $franchiseId, $produitId)
    {
        $request->validate([
            'quantite' => 'required|numeric|min:0.01',
        ]);

        try {
            $franchise = Franchise::findOrFail($franchiseId);
            $franchise->ajouterStockProduit($produitId, $request->quantite);

            return back()->with('success', 'Stock ajouté avec succès.');
        } catch (\Exception $e) {
            return back()->withErrors(['error' => 'Erreur lors de l\'ajout du stock: ' . $e->getMessage()]);
        }
    }

    /**
     * Retire du stock d'un produit
     */
    public function retirerStock(Request $request, $franchiseId, $produitId)
    {
        $request->validate([
            'quantite' => 'required|numeric|min:0.01',
        ]);

        try {
            $franchise = Franchise::findOrFail($franchiseId);
            $success = $franchise->retirerStockProduit($produitId, $request->quantite);

            if ($success) {
                return back()->with('success', 'Stock retiré avec succès.');
            } else {
                return back()->withErrors(['error' => 'Stock insuffisant pour cette opération.']);
            }
        } catch (\Exception $e) {
            return back()->withErrors(['error' => 'Erreur lors du retrait du stock: ' . $e->getMessage()]);
        }
    }
}
