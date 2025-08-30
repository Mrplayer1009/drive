<?php

require_once 'vendor/autoload.php';

// Charger Laravel
$app = require_once 'bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

use App\Models\CommandeClient;
use App\Models\Vente;

echo "=== TEST DU SYSTÈME DE VENTES ===\n\n";

// 1. Vérifier les commandes prêtes
echo "1. Commandes avec statut 'prete':\n";
$commandesPrete = CommandeClient::where('statut', 'prete')->get();
echo "   Nombre de commandes prêtes: " . $commandesPrete->count() . "\n";

foreach ($commandesPrete as $commande) {
    echo "   - Commande #{$commande->id} (Client: {$commande->client->nom_complet}, Montant: {$commande->montant_final}€)\n";
    echo "     Vente associée: " . ($commande->vente ? "OUI (ID: {$commande->vente->id})" : "NON") . "\n";
}

echo "\n";

// 2. Vérifier toutes les ventes
echo "2. Toutes les ventes:\n";
$ventes = Vente::with(['franchise', 'commandeClient'])->get();
echo "   Nombre total de ventes: " . $ventes->count() . "\n";

foreach ($ventes as $vente) {
    echo "   - Vente #{$vente->id} (Franchise: {$vente->franchise->nom_complet}, Montant: {$vente->montant_total}€)\n";
    if ($vente->commandeClient) {
        echo "     Commande associée: #{$vente->commandeClient->id} (Statut: {$vente->commandeClient->statut})\n";
    } else {
        echo "     Aucune commande associée\n";
    }
}

echo "\n";

// 3. Vérifier les commandes sans vente
echo "3. Commandes prêtes sans vente:\n";
$commandesSansVente = CommandeClient::where('statut', 'prete')
    ->whereDoesntHave('vente')
    ->get();
echo "   Nombre de commandes prêtes sans vente: " . $commandesSansVente->count() . "\n";

foreach ($commandesSansVente as $commande) {
    echo "   - Commande #{$commande->id} (Client: {$commande->client->nom_complet})\n";
}

echo "\n";

// 4. Tester la création manuelle d'une vente
if ($commandesSansVente->count() > 0) {
    echo "4. Test de création manuelle de vente:\n";
    $commandeTest = $commandesSansVente->first();
    echo "   Tentative de création de vente pour la commande #{$commandeTest->id}...\n";
    
    try {
        $vente = $commandeTest->creerVente();
        echo "   ✅ Vente créée avec succès! ID: {$vente->id}\n";
    } catch (Exception $e) {
        echo "   ❌ Erreur lors de la création: " . $e->getMessage() . "\n";
    }
}

echo "\n=== FIN DU TEST ===\n";
