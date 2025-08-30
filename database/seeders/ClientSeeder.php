<?php

namespace Database\Seeders;

use App\Models\Client;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class ClientSeeder extends Seeder
{
    public function run(): void
    {
        $clients = [
            [
                'nom' => 'Dupont',
                'prenom' => 'Marie',
                'email' => 'marie.dupont@email.com',
                'telephone' => '01 23 45 67 89',
                'adresse' => '123 Rue de la Paix',
                'ville' => 'Paris',
                'code_postal' => '75001',
                'password' => Hash::make('password123'),
                'langue' => 'fr',
                'newsletter_active' => true,
                'points_fidelite' => 150,
                'statut' => 'actif',
            ],
            [
                'nom' => 'Martin',
                'prenom' => 'Jean',
                'email' => 'jean.martin@email.com',
                'telephone' => '01 98 76 54 32',
                'adresse' => '456 Avenue des Champs',
                'ville' => 'Paris',
                'code_postal' => '75008',
                'password' => Hash::make('password123'),
                'langue' => 'fr',
                'newsletter_active' => true,
                'points_fidelite' => 75,
                'statut' => 'actif',
            ],
            [
                'nom' => 'Smith',
                'prenom' => 'John',
                'email' => 'john.smith@email.com',
                'telephone' => '01 11 22 33 44',
                'adresse' => '789 Boulevard Saint-Germain',
                'ville' => 'Paris',
                'code_postal' => '75006',
                'password' => Hash::make('password123'),
                'langue' => 'en',
                'newsletter_active' => false,
                'points_fidelite' => 25,
                'statut' => 'actif',
            ],
            [
                'nom' => 'Garcia',
                'prenom' => 'Maria',
                'email' => 'maria.garcia@email.com',
                'telephone' => '01 55 66 77 88',
                'adresse' => '321 Rue de Rivoli',
                'ville' => 'Paris',
                'code_postal' => '75001',
                'password' => Hash::make('password123'),
                'langue' => 'es',
                'newsletter_active' => true,
                'points_fidelite' => 200,
                'statut' => 'actif',
            ],
        ];

        foreach ($clients as $client) {
            Client::create($client);
        }
    }
}
