<?php

require_once 'vendor/autoload.php';

$app = require_once 'bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

use App\Models\CommandeClient;
use App\Models\Client;
use App\Models\Menu;

echo "=== TEST SIMPLE ===\n\n";

try {
    // Test 1: Vérifier les clients
    echo "1. Test des clients...\n";
    $client = Client::first();
    if ($client) {
        echo "   Client trouvé: {$client->email}\n";
    } else {
        echo "   Aucun client trouvé\n";
    }
    
    // Test 2: Vérifier les menus
    echo "\n2. Test des menus...\n";
    $menu = Menu::where('disponible', true)->first();
    if ($menu) {
        echo "   Menu trouvé: {$menu->nom} ({$menu->prix}€)\n";
    } else {
        echo "   Aucun menu trouvé\n";
    }
    
    // Test 3: Créer une commande simple
    if ($client && $menu) {
        echo "\n3. Test de création de commande...\n";
        
        $commande = CommandeClient::create([
            'client_id' => $client->id,
            'franchise_id' => 1,
            'food_truck_id' => 1,
            'statut' => 'en_attente_paiement',
            'montant_total' => $menu->prix * 2,
            'reduction_fidelite' => 0,
            'montant_final' => $menu->prix * 2,
            'mode_paiement' => 'en_ligne',
            'adresse_livraison' => 'Test',
            'telephone_contact' => '0123456789',
            'notes' => 'Test',
            'date_commande' => now(),
            'token_paiement' => \Str::random(32)
        ]);
        
        echo "   Commande créée: #{$commande->id}\n";
        echo "   Statut: {$commande->statut}\n";
        echo "   Token: {$commande->token_paiement}\n";
        
        // Nettoyer
        $commande->delete();
        echo "   Commande supprimée\n";
    }
    
    echo "\n=== TEST TERMINÉ ===\n";
    
} catch (Exception $e) {
    echo "ERREUR: " . $e->getMessage() . "\n";
    echo "Fichier: " . $e->getFile() . "\n";
    echo "Ligne: " . $e->getLine() . "\n";
}
