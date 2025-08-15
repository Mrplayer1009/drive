<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Entrepot;
use App\Models\Produit;
use App\Models\EntrepotProduitStock;
use App\Services\StockService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class StockEntrepotController extends Controller
{
    protected $stockService;

    public function __construct(StockService $stockService)
    {
        $this->stockService = $stockService;
    }

    /**
     * Affiche la liste des stocks d'un entrepôt
     */
    public function index($entrepotId)
    {
        $entrepot = Entrepot::findOrFail($entrepotId);
        $stocks = $entrepot->stocksProduits()->with('produit')->get();
        
        return view('admin.entrepots.stocks.index', compact('entrepot', 'stocks'));
    }

    /**
     * Affiche le formulaire pour ajouter/modifier un stock
     */
    public function create($entrepotId)
    {
        $entrepot = Entrepot::findOrFail($entrepotId);
        
        // Récupérer les IDs des produits déjà en stock dans cet entrepôt
        $produitsEnStock = $entrepot->stocksProduits()->pluck('produit_id')->toArray();
        
        // Récupérer tous les produits sauf ceux déjà en stock
        $produits = Produit::whereNotIn('id', $produitsEnStock)->get();
        
        return view('admin.entrepots.stocks.create', compact('entrepot', 'produits'));
    }

    /**
     * Enregistre un nouveau stock
     */
    public function store(Request $request, $entrepotId)
    {
        $request->validate([
            'produit_id' => 'required|exists:produits,id',
            'quantite_stock' => 'required|numeric|min:0',
            'stock_minimum' => 'required|numeric|min:0',
        ]);

        try {
            $this->stockService->ajouterStockEntrepot(
                $entrepotId,
                $request->produit_id,
                $request->quantite_stock,
                $request->stock_minimum
            );

            return redirect()->route('admin.entrepots.stocks.index', $entrepotId)
                            ->with('success', 'Stock ajouté avec succès.');
        } catch (\Exception $e) {
            return back()->withErrors(['error' => 'Erreur lors de l\'ajout du stock: ' . $e->getMessage()]);
        }
    }

    /**
     * Affiche le formulaire d'édition d'un stock
     */
    public function edit($entrepotId, $stockId)
    {
        $entrepot = Entrepot::findOrFail($entrepotId);
        $stock = EntrepotProduitStock::where('entrepot_id', $entrepotId)
                                    ->where('id', $stockId)
                                    ->with('produit')
                                    ->firstOrFail();
        
        return view('admin.entrepots.stocks.edit', compact('entrepot', 'stock'));
    }

    /**
     * Met à jour un stock
     */
    public function update(Request $request, $entrepotId, $stockId)
    {
        $request->validate([
            'quantite_stock' => 'required|numeric|min:0',
            'stock_minimum' => 'required|numeric|min:0',
        ]);

        try {
            $stock = EntrepotProduitStock::where('entrepot_id', $entrepotId)
                                        ->where('id', $stockId)
                                        ->firstOrFail();
            
            $stock->update([
                'quantite_stock' => $request->quantite_stock,
                'stock_minimum' => $request->stock_minimum,
            ]);

            return redirect()->route('admin.entrepots.stocks.index', $entrepotId)
                            ->with('success', 'Stock mis à jour avec succès.');
        } catch (\Exception $e) {
            return back()->withErrors(['error' => 'Erreur lors de la mise à jour du stock: ' . $e->getMessage()]);
        }
    }

    /**
     * Affiche les alertes de stock pour tous les entrepôts
     */
    public function alertes()
    {
        $alertes = $this->stockService->verifierStocksEntrepots();
        
        return view('admin.stocks.alertes-entrepots', compact('alertes'));
    }

    /**
     * Affiche le stock d'un produit spécifique dans un entrepôt
     */
    public function show($entrepotId, $produitId)
    {
        $entrepot = Entrepot::findOrFail($entrepotId);
        $produit = Produit::findOrFail($produitId);
        $stock = $entrepot->getStockProduit($produitId);
        
        return view('admin.entrepots.stocks.show', compact('entrepot', 'produit', 'stock'));
    }

    /**
     * Ajoute du stock à un produit existant
     */
    public function ajouterStock(Request $request, $entrepotId, $produitId)
    {
        $request->validate([
            'quantite' => 'required|numeric|min:0.01',
        ]);

        try {
            $entrepot = Entrepot::findOrFail($entrepotId);
            
            // Debug: afficher les valeurs
            \Log::info('Ajout de stock', [
                'entrepot_id' => $entrepotId,
                'produit_id' => $produitId,
                'quantite' => $request->quantite,
                'stock_avant' => $entrepot->getStockProduit($produitId)?->quantite_stock ?? 0
            ]);
            
            $entrepot->ajouterStockProduit($produitId, $request->quantite);
            
            // Debug: afficher le stock après
            $stock_apres = $entrepot->getStockProduit($produitId);
            \Log::info('Stock après ajout', [
                'stock_apres' => $stock_apres?->quantite_stock ?? 0
            ]);

            return back()->with('success', 'Stock ajouté avec succès.');
        } catch (\Exception $e) {
            \Log::error('Erreur ajout stock', ['error' => $e->getMessage()]);
            return back()->withErrors(['error' => 'Erreur lors de l\'ajout du stock: ' . $e->getMessage()]);
        }
    }

    /**
     * Retire du stock d'un produit
     */
    public function retirerStock(Request $request, $entrepotId, $produitId)
    {
        $request->validate([
            'quantite' => 'required|numeric|min:0.01',
        ]);

        try {
            $entrepot = Entrepot::findOrFail($entrepotId);
            
            // Debug: afficher les valeurs
            \Log::info('Retrait de stock', [
                'entrepot_id' => $entrepotId,
                'produit_id' => $produitId,
                'quantite' => $request->quantite,
                'stock_avant' => $entrepot->getStockProduit($produitId)?->quantite_stock ?? 0
            ]);
            
            $success = $entrepot->retirerStockProduit($produitId, $request->quantite);
            
            // Debug: afficher le stock après
            $stock_apres = $entrepot->getStockProduit($produitId);
            \Log::info('Stock après retrait', [
                'success' => $success,
                'stock_apres' => $stock_apres?->quantite_stock ?? 0
            ]);

            if ($success) {
                return back()->with('success', 'Stock retiré avec succès.');
            } else {
                return back()->withErrors(['error' => 'Stock insuffisant pour cette opération.']);
            }
        } catch (\Exception $e) {
            \Log::error('Erreur retrait stock', ['error' => $e->getMessage()]);
            return back()->withErrors(['error' => 'Erreur lors du retrait du stock: ' . $e->getMessage()]);
        }
    }
}
