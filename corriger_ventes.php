<?php

require_once 'vendor/autoload.php';

// Charger Laravel
$app = require_once 'bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

use App\Models\CommandeClient;
use App\Models\Vente;

echo "=== CORRECTION DES VENTES MANQUANTES ===\n\n";

// Trouver toutes les commandes prêtes sans vente
$commandesSansVente = CommandeClient::where('statut', 'prete')
    ->whereDoesntHave('vente')
    ->get();

echo "Commandes prêtes sans vente trouvées: " . $commandesSansVente->count() . "\n\n";

if ($commandesSansVente->count() > 0) {
    echo "Création des ventes manquantes...\n";
    
    foreach ($commandesSansVente as $commande) {
        try {
            echo "  - Commande #{$commande->id} (Client: {$commande->client->nom_complet}, Montant: {$commande->montant_final}€)... ";
            
            // Créer la vente
            $vente = $commande->creerVente();
            
            echo "✅ Vente créée (ID: {$vente->id})\n";
            
        } catch (Exception $e) {
            echo "❌ Erreur: " . $e->getMessage() . "\n";
        }
    }
    
    echo "\nCorrection terminée!\n";
} else {
    echo "Aucune correction nécessaire.\n";
}

echo "\n=== VÉRIFICATION FINALE ===\n";

// Vérifier le résultat
$commandesPrete = CommandeClient::where('statut', 'prete')->get();
$commandesAvecVente = CommandeClient::where('statut', 'prete')->whereHas('vente')->get();

echo "Commandes prêtes totales: " . $commandesPrete->count() . "\n";
echo "Commandes prêtes avec vente: " . $commandesAvecVente->count() . "\n";
echo "Commandes prêtes sans vente: " . ($commandesPrete->count() - $commandesAvecVente->count()) . "\n";

if ($commandesPrete->count() === $commandesAvecVente->count()) {
    echo "\n🎉 Toutes les commandes prêtes ont maintenant une vente!\n";
} else {
    echo "\n⚠️  Il reste des commandes sans vente.\n";
}

echo "\n=== FIN ===\n";
