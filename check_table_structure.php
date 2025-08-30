<?php

require_once 'vendor/autoload.php';

$app = require_once 'bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

use Illuminate\Support\Facades\DB;

echo "=== VÉRIFICATION STRUCTURE TABLE ===\n\n";

try {
    $columns = DB::select("SHOW COLUMNS FROM commande_clients");
    echo "Colonnes de la table commande_clients:\n";
    foreach ($columns as $column) {
        echo "- {$column->Field} ({$column->Type})\n";
    }
    
    echo "\n=== VÉRIFICATION TERMINÉE ===\n";
    
} catch (Exception $e) {
    echo "ERREUR: " . $e->getMessage() . "\n";
}
