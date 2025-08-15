<?php

namespace App\Services;

use App\Models\Commande;
use App\Models\Vente;
use App\Models\Entrepot;
use App\Models\Franchise;
use App\Models\Produit;
use App\Models\EntrepotProduitStock;
use App\Models\FranchiseProduitStock;
use Illuminate\Support\Facades\DB;
use Exception;

class StockService
{
    /**
     * Gère la réduction des stocks d'entrepôt lors d'une commande
     */
    public function traiterCommande(Commande $commande)
    {
        DB::beginTransaction();
        
        try {
            $entrepot = $commande->entrepot;
            $franchise = $commande->franchise;
            
            foreach ($commande->produits as $produit) {
                $quantite = $produit->pivot->quantite;
                
                // Vérifier si l'entrepôt a suffisamment de stock
                $stockEntrepot = $entrepot->getStockProduit($produit->id);
                
                if (!$stockEntrepot || $stockEntrepot->quantite_stock < $quantite) {
                    throw new Exception("Stock insuffisant pour le produit {$produit->nom} dans l'entrepôt {$entrepot->nom}");
                }
                
                // Retirer le stock de l'entrepôt
                $entrepot->retirerStockProduit($produit->id, $quantite);
                
                // Ajouter le stock à la franchise
                $franchise->ajouterStockProduit($produit->id, $quantite);
            }
            
            DB::commit();
            return true;
            
        } catch (Exception $e) {
            DB::rollBack();
            throw $e;
        }
    }
    
    /**
     * Gère la réduction des stocks de franchise lors d'une vente
     */
    public function traiterVente(Vente $vente)
    {
        DB::beginTransaction();
        
        try {
            $franchise = $vente->franchise;
            
            // Pour l'instant, on simule une réduction de stock basée sur le montant
            // Dans un vrai système, vous auriez une table pivot vente_produits
            // avec les produits vendus et leurs quantités
            
            // Exemple de réduction basée sur le montant (à adapter selon vos besoins)
            $montantMoyenParProduit = 10; // Prix moyen par produit
            $nombreProduitsVendus = round($vente->montant_total / $montantMoyenParProduit);
            
            // Réduire le stock des produits les plus vendus de la franchise
            $produitsFranchise = $franchise->stocksProduits()
                                          ->where('quantite_stock', '>', 0)
                                          ->orderBy('quantite_stock', 'desc')
                                          ->limit($nombreProduitsVendus)
                                          ->get();
            
            foreach ($produitsFranchise as $stockProduit) {
                $quantiteARetirer = min(1, $stockProduit->quantite_stock); // Retirer 1 unité par produit
                $franchise->retirerStockProduit($stockProduit->produit_id, $quantiteARetirer);
            }
            
            DB::commit();
            return true;
            
        } catch (Exception $e) {
            DB::rollBack();
            throw $e;
        }
    }
    
    /**
     * Vérifie les stocks insuffisants dans tous les entrepôts
     */
    public function verifierStocksEntrepots()
    {
        $entrepots = Entrepot::where('statut', 'actif')->get();
        $alertes = [];
        
        foreach ($entrepots as $entrepot) {
            $produitsEnRupture = $entrepot->getProduitsEnRupture();
            $produitsStockInsuffisant = $entrepot->getProduitsStockInsuffisant();
            
            if ($produitsEnRupture->count() > 0 || $produitsStockInsuffisant->count() > 0) {
                $alertes[] = [
                    'entrepot' => $entrepot,
                    'produits_en_rupture' => $produitsEnRupture,
                    'produits_stock_insuffisant' => $produitsStockInsuffisant,
                ];
            }
        }
        
        return $alertes;
    }
    
    /**
     * Vérifie les stocks insuffisants dans toutes les franchises
     */
    public function verifierStocksFranchises()
    {
        $franchises = Franchise::where('statut', 'actif')->get();
        $alertes = [];
        
        foreach ($franchises as $franchise) {
            $produitsEnRupture = $franchise->getProduitsEnRupture();
            $produitsStockInsuffisant = $franchise->getProduitsStockInsuffisant();
            
            if ($produitsEnRupture->count() > 0 || $produitsStockInsuffisant->count() > 0) {
                $alertes[] = [
                    'franchise' => $franchise,
                    'produits_en_rupture' => $produitsEnRupture,
                    'produits_stock_insuffisant' => $produitsStockInsuffisant,
                ];
            }
        }
        
        return $alertes;
    }
    
    /**
     * Ajoute du stock à un entrepôt
     */
    public function ajouterStockEntrepot($entrepotId, $produitId, $quantite, $stockMinimum = 0)
    {
        $entrepot = Entrepot::findOrFail($entrepotId);
        return $entrepot->ajouterStockProduit($produitId, $quantite, $stockMinimum);
    }
    
    /**
     * Ajoute du stock à une franchise
     */
    public function ajouterStockFranchise($franchiseId, $produitId, $quantite, $stockMinimum = 0)
    {
        $franchise = Franchise::findOrFail($franchiseId);
        return $franchise->ajouterStockProduit($produitId, $quantite, $stockMinimum);
    }
    
    /**
     * Obtient le stock total d'un produit dans tous les entrepôts
     */
    public function getStockTotalEntrepots($produitId)
    {
        return EntrepotProduitStock::where('produit_id', $produitId)->sum('quantite_stock');
    }
    
    /**
     * Obtient le stock total d'un produit dans toutes les franchises
     */
    public function getStockTotalFranchises($produitId)
    {
        return FranchiseProduitStock::where('produit_id', $produitId)->sum('quantite_stock');
    }
}
