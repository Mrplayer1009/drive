<?php

require_once 'vendor/autoload.php';

use Illuminate\Support\Facades\DB;
use App\Models\CommandeClient;
use App\Models\Client;
use App\Models\Menu;

// Configuration de base
$app = require_once 'bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

echo "=== TEST COMMANDE SUR PLACE ===\n\n";

try {
    // 1. Vérifier que les menus existent
    echo "1. Vérification des menus...\n";
    $menus = Menu::where('disponible', true)->get();
    echo "   Menus disponibles: " . $menus->count() . "\n";
    foreach ($menus as $menu) {
        echo "   - {$menu->nom}: {$menu->prix}€\n";
    }
    echo "\n";

    // 2. Vérifier la structure de la table commande_clients
    echo "2. Vérification de la structure de la table...\n";
    $columns = DB::select("SHOW COLUMNS FROM commande_clients");
    $hasTokenPaiement = false;
    foreach ($columns as $column) {
        if ($column->Field === 'token_paiement') {
            $hasTokenPaiement = true;
            break;
        }
    }
    echo "   Colonne token_paiement: " . ($hasTokenPaiement ? "PRÉSENTE" : "MANQUANTE") . "\n";
    echo "\n";

    // 3. Vérifier les dernières commandes
    echo "3. Dernières commandes créées...\n";
    $commandes = CommandeClient::latest()->take(5)->get();
    foreach ($commandes as $commande) {
        echo "   Commande #{$commande->id}: {$commande->statut} - {$commande->montant_final}€ - Token: " . ($commande->token_paiement ? "PRÉSENT" : "ABSENT") . "\n";
    }
    echo "\n";

    // 4. Vérifier les clients
    echo "4. Clients existants...\n";
    $clients = Client::latest()->take(3)->get();
    foreach ($clients as $client) {
        echo "   Client: {$client->email} - {$client->nom} {$client->prenom}\n";
    }
    echo "\n";

    // 5. Test de création d'une commande simulée
    echo "5. Test de création d'une commande simulée...\n";
    
    // Trouver un client existant ou en créer un
    $client = Client::first();
    if (!$client) {
        echo "   Aucun client trouvé, création d'un client de test...\n";
        $client = Client::create([
            'nom' => 'Test',
            'prenom' => 'Client',
            'email' => 'test@example.com',
            'telephone' => '0123456789',
            'password' => bcrypt('password123')
        ]);
    }
    
    // Trouver un menu
    $menu = Menu::where('disponible', true)->first();
    if (!$menu) {
        echo "   ERREUR: Aucun menu disponible trouvé!\n";
        exit(1);
    }
    
    // Créer une commande de test
    $commande = CommandeClient::create([
        'client_id' => $client->id,
        'franchise_id' => 1, // Premier franchise
        'food_truck_id' => 1,
        'statut' => 'en_attente_paiement',
        'montant_total' => $menu->prix * 2,
        'reduction_fidelite' => 0,
        'montant_final' => $menu->prix * 2,
        'mode_paiement' => 'sur_place',
        'adresse_livraison' => 'Adresse de test',
        'telephone_contact' => '0123456789',
        'notes' => 'Commande de test',
        'date_commande' => now(),
        'token_paiement' => \Str::random(32)
    ]);
    
    // Attacher le menu
    $commande->menus()->attach($menu->id, [
        'quantite' => 2,
        'prix_unitaire' => $menu->prix,
        'prix_total' => $menu->prix * 2
    ]);
    
    echo "   Commande de test créée: #{$commande->id}\n";
    echo "   Token: {$commande->token_paiement}\n";
    echo "   Montant: {$commande->montant_final}€\n";
    
    // Nettoyer la commande de test
    $commande->menus()->detach();
    $commande->delete();
    echo "   Commande de test supprimée\n";
    
    echo "\n=== TEST TERMINÉ AVEC SUCCÈS ===\n";
    
} catch (Exception $e) {
    echo "ERREUR: " . $e->getMessage() . "\n";
    echo "Fichier: " . $e->getFile() . "\n";
    echo "Ligne: " . $e->getLine() . "\n";
}
