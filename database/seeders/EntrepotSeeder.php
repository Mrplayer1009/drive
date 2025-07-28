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
                'capacite_stockage' => 5000.00,
                'cuisine' => true,
                'statut' => 'actif',
            ],
            [
                'nom' => 'Entrepôt Lyon',
                'adresse' => '456 Avenue des Entreprises',
                'ville' => 'Lyon',
                'code_postal' => '69001',
                'capacite_stockage' => 3500.00,
                'cuisine' => true,
                'statut' => 'actif',
            ],
            [
                'nom' => 'Entrepôt Marseille',
                'adresse' => '789 Boulevard du Port',
                'ville' => 'Marseille',
                'code_postal' => '13001',
                'capacite_stockage' => 4000.00,
                'cuisine' => false,
                'statut' => 'actif',
            ],
            [
                'nom' => 'Entrepôt Toulouse',
                'adresse' => '321 Rue de l\'Aéronautique',
                'ville' => 'Toulouse',
                'code_postal' => '31000',
                'capacite_stockage' => 3000.00,
                'cuisine' => true,
                'statut' => 'actif',
            ],
        ];

        foreach ($entrepots as $entrepot) {
            Entrepot::create($entrepot);
        }
    }
} 