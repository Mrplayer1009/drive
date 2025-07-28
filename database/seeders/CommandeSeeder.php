<?php

namespace Database\Seeders;

use App\Models\Commande;
use App\Models\Franchise;
use App\Models\Entrepot;
use App\Models\Produit;
use Illuminate\Database\Seeder;

class CommandeSeeder extends Seeder
{
    public function run(): void
    {
        $franchises = Franchise::all();
        $entrepots = Entrepot::all();
        $produits = Produit::all();

        // Créer quelques commandes de test
        for ($i = 0; $i < 10; $i++) {
            $franchise = $franchises->random();
            $entrepot = $entrepots->random();
            
            $commande = Commande::create([
                'franchise_id' => $franchise->id,
                'entrepot_id' => $entrepot->id,
                'statut' => ['en_attente', 'validee', 'livree'][array_rand(['en_attente', 'validee', 'livree'])],
                'date_commande' => now()->subDays(rand(1, 30)),
                'notes' => 'Commande de test ' . ($i + 1),
            ]);

            $total_obligatoire = 0;
            $total_libre = 0;
            $produits_commande = [];

            // Ajouter des produits obligatoires (80%)
            $produits_obligatoires = $produits->where('obligatoire', true)->take(5);
            foreach ($produits_obligatoires as $produit) {
                $quantite = rand(10, 50);
                $prix_total = $produit->prix_unitaire * $quantite;
                $total_obligatoire += $prix_total;

                $produits_commande[$produit->id] = [
                    'quantite' => $quantite,
                    'prix_unitaire' => $produit->prix_unitaire,
                    'prix_total' => $prix_total,
                ];
            }

            // Ajouter des produits libres (20%)
            $produits_libres = $produits->where('obligatoire', false)->take(3);
            foreach ($produits_libres as $produit) {
                $quantite = rand(5, 20);
                $prix_total = $produit->prix_unitaire * $quantite;
                $total_libre += $prix_total;

                $produits_commande[$produit->id] = [
                    'quantite' => $quantite,
                    'prix_unitaire' => $produit->prix_unitaire,
                    'prix_total' => $prix_total,
                ];
            }

            // Attacher les produits à la commande
            $commande->produits()->attach($produits_commande);

            $total_commande = $total_obligatoire + $total_libre;
            
            $commande->update([
                'total_commande' => $total_commande,
                'total_obligatoire' => $total_obligatoire,
                'total_libre' => $total_libre,
            ]);
        }
    }
} 