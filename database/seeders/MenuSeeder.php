<?php

namespace Database\Seeders;

use App\Models\Menu;
use Illuminate\Database\Seeder;

class MenuSeeder extends Seeder
{
    public function run(): void
    {
        $menus = [
            // Burgers
            [
                'nom' => 'Classic Burger',
                'description' => 'Burger classique avec steak de bœuf, salade, tomate et fromage',
                'prix' => 12.50,
                'categorie' => 'burger',
                'disponible' => true,
                'special' => false,
                'ordre_affichage' => 1,
            ],
            [
                'nom' => 'Cheese Burger',
                'description' => 'Burger avec double fromage cheddar et sauce spéciale',
                'prix' => 14.00,
                'categorie' => 'burger',
                'disponible' => true,
                'special' => false,
                'ordre_affichage' => 2,
            ],
            [
                'nom' => 'Bacon Burger',
                'description' => 'Burger avec bacon croustillant et sauce barbecue',
                'prix' => 15.50,
                'categorie' => 'burger',
                'disponible' => true,
                'special' => true,
                'ordre_affichage' => 3,
            ],
            [
                'nom' => 'Veggie Burger',
                'description' => 'Burger végétarien avec galette de légumes et fromage',
                'prix' => 13.00,
                'categorie' => 'burger',
                'disponible' => true,
                'special' => false,
                'ordre_affichage' => 4,
            ],
            
            // Boissons
            [
                'nom' => 'Coca-Cola',
                'description' => 'Soda Coca-Cola 33cl',
                'prix' => 3.50,
                'categorie' => 'boisson',
                'disponible' => true,
                'special' => false,
                'ordre_affichage' => 5,
            ],
            [
                'nom' => 'Fanta',
                'description' => 'Soda Fanta Orange 33cl',
                'prix' => 3.50,
                'categorie' => 'boisson',
                'disponible' => true,
                'special' => false,
                'ordre_affichage' => 6,
            ],
            [
                'nom' => 'Eau minérale',
                'description' => 'Eau minérale naturelle 50cl',
                'prix' => 2.00,
                'categorie' => 'boisson',
                'disponible' => true,
                'special' => false,
                'ordre_affichage' => 7,
            ],
            
            // Desserts
            [
                'nom' => 'Cheesecake',
                'description' => 'Cheesecake aux fruits rouges',
                'prix' => 5.50,
                'categorie' => 'dessert',
                'disponible' => true,
                'special' => false,
                'ordre_affichage' => 8,
            ],
            [
                'nom' => 'Brownie',
                'description' => 'Brownie au chocolat avec noix',
                'prix' => 4.50,
                'categorie' => 'dessert',
                'disponible' => true,
                'special' => false,
                'ordre_affichage' => 9,
            ],
            
            // Accompagnements
            [
                'nom' => 'Frites',
                'description' => 'Frites maison avec sel',
                'prix' => 4.00,
                'categorie' => 'accompagnement',
                'disponible' => true,
                'special' => false,
                'ordre_affichage' => 10,
            ],
            [
                'nom' => 'Onion Rings',
                'description' => 'Anneaux d\'oignon panés et frits',
                'prix' => 4.50,
                'categorie' => 'accompagnement',
                'disponible' => true,
                'special' => false,
                'ordre_affichage' => 11,
            ],
        ];

        foreach ($menus as $menu) {
            Menu::create($menu);
        }
    }
}
