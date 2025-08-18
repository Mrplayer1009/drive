<?php

namespace Database\Seeders;

use App\Models\Franchise;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class FranchiseSeeder extends Seeder
{
    public function run(): void
    {
        $franchises = [
            [
                'nom' => 'Dupont',
                'prenom' => 'Jean',
                'email' => 'jean.dupont@drivncook.com',
                'telephone' => '01 23 45 67 89',
                'adresse' => '123 Rue de la Paix',
                'ville' => 'Paris',
                'code_postal' => '75001',
                'date_entree' => '2023-01-15',
                'statut' => 'actif',
                'droits_entree' => 50000.00,
                'pourcentage_ventes' => 4.00,
                'password' => Hash::make('password123'),
            ],
            [
                'nom' => 'Martin',
                'prenom' => 'Sophie',
                'email' => 'sophie.martin@drivncook.com',
                'telephone' => '04 78 90 12 34',
                'adresse' => '456 Avenue des Fleurs',
                'ville' => 'Paris',
                'code_postal' => '75002',
                'date_entree' => '2023-02-20',
                'statut' => 'actif',
                'droits_entree' => 50000.00,
                'pourcentage_ventes' => 4.00,
                'password' => Hash::make('password123'),
            ],
            [
                'nom' => 'Bernard',
                'prenom' => 'Pierre',
                'email' => 'pierre.bernard@drivncook.com',
                'telephone' => '04 91 23 45 67',
                'adresse' => '789 Boulevard du Port',
                'ville' => 'Paris',
                'code_postal' => '75003',
                'date_entree' => '2023-03-10',
                'statut' => 'actif',
                'droits_entree' => 50000,
                'pourcentage_ventes' => 4.00,
                'password' => Hash::make('password123'),
            ],
            [
                'nom' => 'Petit',
                'prenom' => 'Marie',
                'email' => 'marie.petit@drivncook.com',
                'telephone' => '05 61 34 56 78',
                'adresse' => '321 Rue de l\'Aéronautique',
                'ville' => 'Paris',
                'code_postal' => '75004',
                'date_entree' => '2023-04-05',
                'statut' => 'actif',
                'droits_entree' => 50000,
                'pourcentage_ventes' => 4.00,
                'password' => Hash::make('password123'),
            ],
            [
                'nom' => 'Robert',
                'prenom' => 'Claude',
                'email' => 'claude.robert@drivncook.com',
                'telephone' => '02 99 45 67 89',
                'adresse' => '654 Rue de la Bretagne',
                'ville' => 'Paris',
                'code_postal' => '75005',
                'date_entree' => '2023-05-12',
                'statut' => 'actif',
                'droits_entree' => 50000,
                'pourcentage_ventes' => 4.00,
                'password' => Hash::make('password123'),
            ],
        ];

        foreach ($franchises as $franchise) {
            Franchise::create($franchise);
        }
    }
} 