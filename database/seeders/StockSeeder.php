<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Entrepot;
use App\Models\Franchise;
use App\Models\Produit;
use App\Models\EntrepotProduitStock;
use App\Models\FranchiseProduitStock;

class StockSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Récupérer les entrepôts, franchises et produits existants
        $entrepots = Entrepot::all();
        $franchises = Franchise::all();
        $produits = Produit::all();

        // ajout rand de stock
        foreach ($entrepots as $entrepot) {
            foreach ($produits as $produit) {
                $quantiteStock = rand(50, 200);
                $stockMinimum = rand(10, 30);

                EntrepotProduitStock::create([
                    'entrepot_id' => $entrepot->id,
                    'produit_id' => $produit->id,
                    'quantite_stock' => $quantiteStock,
                    'stock_minimum' => $stockMinimum,
                ]);
            }
        }

        foreach ($franchises as $franchise) {
            foreach ($produits as $produit) {
                $quantiteStock = rand(10, 50);
                $stockMinimum = rand(5, 15);

                FranchiseProduitStock::create([
                    'franchise_id' => $franchise->id,
                    'produit_id' => $produit->id,
                    'quantite_stock' => $quantiteStock,
                    'stock_minimum' => $stockMinimum,
                ]);
            }
        }

        $this->command->info('Stocks ajoutés avec succès !');
    }
}
