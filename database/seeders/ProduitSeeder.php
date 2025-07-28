<?php

namespace Database\Seeders;

use App\Models\Produit;
use Illuminate\Database\Seeder;

class ProduitSeeder extends Seeder
{
    public function run(): void
    {
        $produits = [
            // Ingrédients obligatoires (80%)
            [
                'nom' => 'Pain Burger',
                'description' => 'Pain spécial pour burgers',
                'categorie' => 'ingredient',
                'prix_unitaire' => 0.50,
                'unite_mesure' => 'unité',
                'stock_minimum' => 100,
                'obligatoire' => true,
            ],
            [
                'nom' => 'Steak de Bœuf',
                'description' => 'Steak de bœuf 150g',
                'categorie' => 'ingredient',
                'prix_unitaire' => 2.50,
                'unite_mesure' => 'unité',
                'stock_minimum' => 50,
                'obligatoire' => true,
            ],
            [
                'nom' => 'Fromage Cheddar',
                'description' => 'Fromage cheddar en tranches',
                'categorie' => 'ingredient',
                'prix_unitaire' => 0.80,
                'unite_mesure' => 'unité',
                'stock_minimum' => 80,
                'obligatoire' => true,
            ],
            [
                'nom' => 'Salade',
                'description' => 'Salade fraîche',
                'categorie' => 'ingredient',
                'prix_unitaire' => 0.30,
                'unite_mesure' => 'unité',
                'stock_minimum' => 100,
                'obligatoire' => true,
            ],
            [
                'nom' => 'Tomates',
                'description' => 'Tomates fraîches',
                'categorie' => 'ingredient',
                'prix_unitaire' => 0.20,
                'unite_mesure' => 'unité',
                'stock_minimum' => 100,
                'obligatoire' => true,
            ],
            [
                'nom' => 'Oignons',
                'description' => 'Oignons frais',
                'categorie' => 'ingredient',
                'prix_unitaire' => 0.15,
                'unite_mesure' => 'unité',
                'stock_minimum' => 100,
                'obligatoire' => true,
            ],
            [
                'nom' => 'Sauce Burger',
                'description' => 'Sauce spéciale burger',
                'categorie' => 'ingredient',
                'prix_unitaire' => 0.40,
                'unite_mesure' => 'unité',
                'stock_minimum' => 80,
                'obligatoire' => true,
            ],
            [
                'nom' => 'Frites',
                'description' => 'Frites surgelées',
                'categorie' => 'ingredient',
                'prix_unitaire' => 1.20,
                'unite_mesure' => 'kg',
                'stock_minimum' => 20,
                'obligatoire' => true,
            ],

            // Plats
            [
                'nom' => 'Burger Classique',
                'description' => 'Burger avec steak, fromage, salade, tomate, oignon',
                'categorie' => 'plat',
                'prix_unitaire' => 8.50,
                'unite_mesure' => 'unité',
                'stock_minimum' => 0,
                'obligatoire' => false,
            ],
            [
                'nom' => 'Burger Bacon',
                'description' => 'Burger avec bacon, steak, fromage',
                'categorie' => 'plat',
                'prix_unitaire' => 10.50,
                'unite_mesure' => 'unité',
                'stock_minimum' => 0,
                'obligatoire' => false,
            ],
            [
                'nom' => 'Burger Végétarien',
                'description' => 'Burger végétarien avec steak de légumes',
                'categorie' => 'plat',
                'prix_unitaire' => 9.00,
                'unite_mesure' => 'unité',
                'stock_minimum' => 0,
                'obligatoire' => false,
            ],

            // Boissons
            [
                'nom' => 'Coca-Cola',
                'description' => 'Coca-Cola 33cl',
                'categorie' => 'boisson',
                'prix_unitaire' => 1.50,
                'unite_mesure' => 'unité',
                'stock_minimum' => 50,
                'obligatoire' => false,
            ],
            [
                'nom' => 'Fanta',
                'description' => 'Fanta Orange 33cl',
                'categorie' => 'boisson',
                'prix_unitaire' => 1.50,
                'unite_mesure' => 'unité',
                'stock_minimum' => 30,
                'obligatoire' => false,
            ],
            [
                'nom' => 'Sprite',
                'description' => 'Sprite 33cl',
                'categorie' => 'boisson',
                'prix_unitaire' => 1.50,
                'unite_mesure' => 'unité',
                'stock_minimum' => 30,
                'obligatoire' => false,
            ],
            [
                'nom' => 'Eau Minérale',
                'description' => 'Eau minérale 50cl',
                'categorie' => 'boisson',
                'prix_unitaire' => 1.00,
                'unite_mesure' => 'unité',
                'stock_minimum' => 40,
                'obligatoire' => false,
            ],
        ];

        foreach ($produits as $produit) {
            Produit::create($produit);
        }
    }
} 