<?php

namespace Database\Seeders;

use App\Models\Vente;
use App\Models\Franchise;
use App\Models\Camion;
use Illuminate\Database\Seeder;

class VenteSeeder extends Seeder
{
    public function run(): void
    {
        $franchises = Franchise::all();
        $camions = Camion::where('statut', 'en_utilisation')->get();

        // Créer des ventes de test pour les 30 derniers jours
        for ($i = 0; $i < 50; $i++) {
            $franchise = $franchises->random();
            $camion = $camions->random();
            
            $montant_total = rand(150, 800); // Montant entre 150€ et 800€
            $montant_reverse = ($montant_total * $franchise->pourcentage_ventes) / 100;
            
            Vente::create([
                'franchise_id' => $franchise->id,
                'camion_id' => $camion->id,
                'date_vente' => now()->subDays(rand(0, 30)),
                'montant_total' => $montant_total,
                'montant_reverse' => $montant_reverse,
                'nombre_commandes' => rand(10, 50),
                'notes' => 'Vente de test ' . ($i + 1),
            ]);
        }
    }
} 