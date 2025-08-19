<?php

namespace Database\Seeders;

use App\Models\Camion;
use App\Models\Franchise;
use Illuminate\Database\Seeder;

class CamionSeeder extends Seeder
{
    public function run(): void
    {
        $camions = [
            [
                'immatriculation' => 'AB-123-CD',
                'marque' => 'Mercedes',
                'modele' => 'Sprinter',
                'annee' => 2022,
                'statut' => 'disponible',
                'ville_localisation' => 'Paris',
                'latitude' => 48.8566,
                'longitude' => 2.3522,
                'derniere_maintenance' => '2024-01-15',
                'prochaine_maintenance' => '2024-04-15',
                'notes' => 'Camion en excellent état',
            ],
            [
                'immatriculation' => 'EF-456-GH',
                'marque' => 'Renault',
                'modele' => 'Master',
                'annee' => 2021,
                'statut' => 'disponible',
                'ville_localisation' => 'Paris',
                'latitude' => 48.8566,
                'longitude' => 2.3522,
                'derniere_maintenance' => '2024-02-10',
                'prochaine_maintenance' => '2024-05-10',
                'notes' => 'Camion attribué à Sophie Martin',
            ],
            [
                'immatriculation' => 'IJ-789-KL',
                'marque' => 'Ford',
                'modele' => 'Transit',
                'annee' => 2023,
                'statut' => 'disponible',
                'ville_localisation' => 'Paris',
                'latitude' => 48.8566,
                'longitude' => 2.3522,
                'derniere_maintenance' => '2024-01-20',
                'prochaine_maintenance' => '2024-04-20',
                'notes' => 'Nouveau camion',
            ],
            [
                'immatriculation' => 'MN-012-OP',
                'marque' => 'Peugeot',
                'modele' => 'Boxer',
                'annee' => 2022,
                'statut' => 'en_maintenance',
                'ville_localisation' => 'Paris',
                'latitude' => 48.8566,
                'longitude' => 2.3522,
                'derniere_maintenance' => '2024-03-01',
                'prochaine_maintenance' => '2024-06-01',
                'notes' => 'Maintenance préventive',
            ],
            [
                'immatriculation' => 'QR-345-ST',
                'marque' => 'Citroën',
                'modele' => 'Jumper',
                'annee' => 2021,
                'statut' => 'disponible',
                'ville_localisation' => 'Paris',
                'latitude' => 48.8566,
                'longitude' => 2.3522,
                'derniere_maintenance' => '2024-02-15',
                'prochaine_maintenance' => '2024-05-15',
                'notes' => 'Camion disponible pour attribution',
            ],
            [
                'immatriculation' => 'UV-678-WX',
                'marque' => 'Mercedes',
                'modele' => 'Vito',
                'annee' => 2023,
                'statut' => 'disponible',
                'ville_localisation' => 'Paris',
                'latitude' => 48.8566,
                'longitude' => 2.3522,
                'derniere_maintenance' => '2024-01-30',
                'prochaine_maintenance' => '2024-04-30',
                'notes' => 'Camion attribué à Jean Dupont',
            ],
        ];

        foreach ($camions as $camionData) {
            // Créer le camion d'abord
            $camion = Camion::create($camionData);
            
            // Vérifier si le camion doit être attribué à un franchisé
            if (str_contains($camionData['notes'], 'Camion attribué à')) {
                // Extraire le nom du franchisé des notes
                preg_match('/Camion attribué à (.+)$/', $camionData['notes'], $matches);
                if (isset($matches[1])) {
                    $nomFranchise = trim($matches[1]);
                    
                    // Chercher le franchisé par nom complet
                    $franchise = Franchise::whereRaw("CONCAT(prenom, ' ', nom) = ?", [$nomFranchise])->first();
                    
                    if ($franchise) {
                        // Attribuer le camion au franchisé via la table pivot
                        $camion->franchises()->attach($franchise->id, [
                            'date_attribution' => now(),
                            'statut' => 'actif'
                        ]);
                        
                        // Mettre à jour le statut du camion
                        $camion->update(['statut' => 'en_utilisation']);
                    }
                }
            }
        }
    }
}
