<?php

require_once 'vendor/autoload.php';

// Charger Laravel
$app = require_once 'bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

use App\Models\CommandeClient;
use App\Models\Vente;
use App\Models\Franchise;

echo "=== TEST DU SYSTÈME STRIPE ===\n\n";

// 1. Vérifier les food trucks disponibles
echo "1. Food trucks disponibles:\n";
$foodTrucks = Franchise::disponible()->avecCamions()->get();
echo "   Nombre de food trucks: " . $foodTrucks->count() . "\n";

foreach ($foodTrucks as $foodTruck) {
    echo "   - {$foodTruck->nom_complet} (ID: {$foodTruck->id})\n";
    echo "     Camions actifs: " . $foodTruck->camions->where('pivot.statut', 'actif')->count() . "\n";
    echo "     Disponible: " . ($foodTruck->disponible ? 'OUI' : 'NON') . "\n";
}

echo "\n";

// 2. Vérifier les commandes récentes
echo "2. Commandes récentes:\n";
$commandes = CommandeClient::with(['client', 'franchise'])->latest()->take(5)->get();
echo "   Nombre de commandes récentes: " . $commandes->count() . "\n";

foreach ($commandes as $commande) {
    echo "   - Commande #{$commande->id} (Client: {$commande->client->nom_complet})\n";
    echo "     Food truck: {$commande->franchise->nom_complet}\n";
    echo "     Statut: {$commande->statut}\n";
    echo "     Montant: {$commande->montant_final}€\n";
    echo "     Date: {$commande->date_commande->format('d/m/Y H:i')}\n";
}

echo "\n";

// 3. Vérifier les ventes
echo "3. Ventes:\n";
$ventes = Vente::with(['franchise', 'commandeClient'])->latest()->take(5)->get();
echo "   Nombre de ventes: " . $ventes->count() . "\n";

foreach ($ventes as $vente) {
    echo "   - Vente #{$vente->id} (Franchise: {$vente->franchise->nom_complet})\n";
    echo "     Montant: {$vente->montant_total}€\n";
    echo "     Date: {$vente->date_vente->format('d/m/Y')}\n";
    if ($vente->commandeClient) {
        echo "     Commande associée: #{$vente->commandeClient->id}\n";
    }
}

echo "\n";

// 4. Vérifier les commandes prêtes sans vente
echo "4. Commandes prêtes sans vente:\n";
$commandesSansVente = CommandeClient::where('statut', 'prete')
    ->whereDoesntHave('vente')
    ->get();
echo "   Nombre de commandes prêtes sans vente: " . $commandesSansVente->count() . "\n";

if ($commandesSansVente->count() > 0) {
    echo "   ⚠️  Il y a des commandes prêtes sans vente!\n";
    echo "   Exécutez le script corriger_ventes.php pour les corriger.\n";
} else {
    echo "   ✅ Toutes les commandes prêtes ont une vente.\n";
}

echo "\n";

// 5. Test de configuration Stripe
echo "5. Configuration Stripe:\n";
$stripeKey = config('services.stripe.key');
$stripeSecret = config('services.stripe.secret');

if ($stripeKey && $stripeSecret) {
    echo "   ✅ Clés Stripe configurées\n";
    echo "   Clé publique: " . substr($stripeKey, 0, 20) . "...\n";
    echo "   Clé secrète: " . substr($stripeSecret, 0, 20) . "...\n";
} else {
    echo "   ❌ Clés Stripe manquantes dans .env\n";
}

echo "\n=== FIN DU TEST ===\n";
