<?php

namespace Database\Seeders;

use App\Models\Entrepot;
use Illuminate\Database\Seeder;

class EntrepotSeeder extends Seeder
{
    public function run(): void
    {
        $entrepots = [
            [
                'nom' => 'Entrepôt Central Paris',
                'adresse' => '123 Rue de la Logistique',
                'ville' => 'Paris',
                'code_postal' => '75001',
                'capacite_stockage' => 5000,
                'cuisine' => true,
                'statut' => 'actif',
            ],
            [
                'nom' => 'Entrepôt Paris Nord',
                'adresse' => '456 Avenue des Entreprises',
                'ville' => 'Paris',
                'code_postal' => '75018',
                'capacite_stockage' => 3500,
                'cuisine' => true,
                'statut' => 'actif',
            ],
            [
                'nom' => 'Entrepôt Paris Sud',
                'adresse' => '789 Boulevard du Port',
                'ville' => 'Paris',
                'code_postal' => '75014',
                'capacite_stockage' => 4000,
                'cuisine' => false,
                'statut' => 'actif',
            ],
            [
                'nom' => 'Entrepôt Paris Est',
                'adresse' => '321 Rue de l\'Aéronautique',
                'ville' => 'Paris',
                'code_postal' => '75020',
                'capacite_stockage' => 3000,
                'cuisine' => true,
                'statut' => 'actif',
            ],
        ];

        foreach ($entrepots as $entrepot) {
            Entrepot::create($entrepot);
        }
    }
} 