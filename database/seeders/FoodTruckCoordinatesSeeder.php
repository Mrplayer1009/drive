<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Franchise;

class FoodTruckCoordinatesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Coordonnées pour différents emplacements à Paris et Île-de-France
        $locations = [
            [
                'latitude' => 48.8566,
                'longitude' => 2.3522,
                'adresse_emplacement' => 'Place de la République, 75003 Paris',
                'disponible' => true
            ],
            [
                'latitude' => 48.8584,
                'longitude' => 2.2945,
                'adresse_emplacement' => 'Champs-Élysées, 75008 Paris',
                'disponible' => true
            ],
            [
                'latitude' => 48.8606,
                'longitude' => 2.3376,
                'adresse_emplacement' => 'Place Vendôme, 75001 Paris',
                'disponible' => true
            ],
            [
                'latitude' => 48.8534,
                'longitude' => 2.3488,
                'adresse_emplacement' => 'Place de la Bastille, 75011 Paris',
                'disponible' => true
            ],
            [
                'latitude' => 48.8867,
                'longitude' => 2.3431,
                'adresse_emplacement' => 'Place du Tertre, 75018 Paris',
                'disponible' => true
            ],
            [
                'latitude' => 48.8566,
                'longitude' => 2.3522,
                'adresse_emplacement' => 'Le Marais, 75004 Paris',
                'disponible' => true
            ],
            [
                'latitude' => 48.8584,
                'longitude' => 2.2945,
                'adresse_emplacement' => 'Quartier Latin, 75005 Paris',
                'disponible' => true
            ],
            [
                'latitude' => 48.8606,
                'longitude' => 2.3376,
                'adresse_emplacement' => 'Saint-Germain-des-Prés, 75006 Paris',
                'disponible' => true
            ]
        ];

        // Mettre à jour les franchises existantes avec des coordonnées
        $franchises = Franchise::all();
        
        foreach ($franchises as $index => $franchise) {
            if (isset($locations[$index])) {
                $location = $locations[$index];
                $franchise->update([
                    'latitude' => $location['latitude'],
                    'longitude' => $location['longitude'],
                    'adresse_emplacement' => $location['adresse_emplacement'],
                    'disponible' => $location['disponible']
                ]);
            }
        }

        $this->command->info('Coordonnées GPS ajoutées aux food trucks avec succès !');
    }
}
