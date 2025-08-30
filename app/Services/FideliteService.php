<?php

namespace App\Services;

use App\Models\Client;
use App\Models\CommandeClient;
use Illuminate\Support\Facades\Log;

class FideliteService
{
    /**
     * Attribuer des points de fidélité à un client après une commande
     */
    public function attribuerPoints(CommandeClient $commande)
    {
        if (!$commande->client) {
            Log::warning("Commande {$commande->id} sans client associé");
            return;
        }

        $client = $commande->client;
        $montantCommande = $commande->montant_final;
        
        // 1€ dépensé = 1 point gagné (sur le montant final, pas sur la réduction)
        $pointsGagnes = (int) $montantCommande;
        
        // Mettre à jour les points du client
        $client->points_fidelite += $pointsGagnes;
        
        // Calculer le nouveau niveau de fidélité
        $client->niveau_fidelite = $this->calculerNiveau($client->points_fidelite);
        
        $client->save();
        
        Log::info("Points de fidélité attribués : {$pointsGagnes} points à {$client->email} (Total: {$client->points_fidelite})");
        
        return $pointsGagnes;
    }
    
    /**
     * Calculer le niveau de fidélité basé sur les points
     */
    public function calculerNiveau($points)
    {
        if ($points >= 1000) return 5; // VIP
        if ($points >= 500) return 4;  // Gold
        if ($points >= 200) return 3;  // Silver
        if ($points >= 50) return 2;   // Bronze
        return 1; // Nouveau
    }
    
    /**
     * Calculer la réduction disponible
     */
    public function calculerReduction($points)
    {
        // 100 points = 5€ de réduction
        return floor($points / 100) * 5;
    }
    
    /**
     * Appliquer une réduction à une commande
     */
    public function appliquerReduction(CommandeClient $commande, $reductionDemandee)
    {
        if (!$commande->client) {
            return false;
        }
        
        $client = $commande->client;
        $reductionDisponible = $this->calculerReduction($client->points_fidelite);
        
        // Vérifier que la réduction demandée est disponible
        if ($reductionDemandee > $reductionDisponible) {
            return false;
        }
        
        // Vérifier que la réduction ne dépasse pas 50% du montant
        $reductionMaximale = $commande->montant_final * 0.5;
        if ($reductionDemandee > $reductionMaximale) {
            $reductionDemandee = $reductionMaximale;
        }
        
        // Appliquer la réduction
        $commande->reduction_fidelite = $reductionDemandee;
        $commande->montant_final = $commande->montant_final - $reductionDemandee;
        $commande->save();
        
        // Déduire les points utilisés
        $pointsUtilises = $reductionDemandee * 20; // 5€ = 100 points, donc 1€ = 20 points
        $client->points_fidelite -= $pointsUtilises;
        $client->reduction_cumulee += $reductionDemandee;
        $client->niveau_fidelite = $this->calculerNiveau($client->points_fidelite);
        $client->save();
        
        Log::info("Réduction appliquée : {$reductionDemandee}€ sur commande {$commande->id} pour {$client->email}");
        
        return $reductionDemandee;
    }
    
    /**
     * Obtenir les informations de fidélité d'un client
     */
    public function getInfosFidelite(Client $client)
    {
        return [
            'points' => $client->points_fidelite,
            'niveau' => $client->niveau_fidelite,
            'reduction_disponible' => $this->calculerReduction($client->points_fidelite),
            'reduction_cumulee' => $client->reduction_cumulee,
            'prochain_palier' => $this->getProchainPalier($client->points_fidelite),
            'points_pour_prochain_niveau' => $this->getPointsPourProchainNiveau($client->points_fidelite),
            'niveau_nom' => $this->getNomNiveau($client->niveau_fidelite),
            'avantages_niveau' => $this->getAvantagesNiveau($client->niveau_fidelite)
        ];
    }
    
    /**
     * Obtenir le prochain palier de points
     */
    private function getProchainPalier($points)
    {
        if ($points < 50) return 50;
        if ($points < 200) return 200;
        if ($points < 500) return 500;
        if ($points < 1000) return 1000;
        return null; // Niveau maximum atteint
    }
    
    /**
     * Obtenir les points nécessaires pour le prochain niveau
     */
    private function getPointsPourProchainNiveau($points)
    {
        $prochainPalier = $this->getProchainPalier($points);
        return $prochainPalier ? $prochainPalier - $points : 0;
    }
    
    /**
     * Obtenir le nom du niveau
     */
    private function getNomNiveau($niveau)
    {
        $niveaux = [
            1 => 'Nouveau',
            2 => 'Bronze',
            3 => 'Silver',
            4 => 'Gold',
            5 => 'VIP'
        ];
        
        return $niveaux[$niveau] ?? 'Nouveau';
    }
    
    /**
     * Obtenir les avantages du niveau
     */
    private function getAvantagesNiveau($niveau)
    {
        $avantages = [
            1 => ['Réduction de base : 100 points = 5€'],
            2 => ['Réduction de base : 100 points = 5€', 'Livraison gratuite à partir de 30€'],
            3 => ['Réduction de base : 100 points = 5€', 'Livraison gratuite à partir de 25€', 'Offres exclusives'],
            4 => ['Réduction de base : 100 points = 5€', 'Livraison gratuite à partir de 20€', 'Offres exclusives', 'Support prioritaire'],
            5 => ['Réduction de base : 100 points = 5€', 'Livraison gratuite', 'Offres exclusives', 'Support prioritaire', 'Accès VIP']
        ];
        
        return $avantages[$niveau] ?? $avantages[1];
    }
}
