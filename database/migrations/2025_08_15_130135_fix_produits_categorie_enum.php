<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // Étape 1: Étendre l'ENUM pour accepter toutes les valeurs
        DB::statement("ALTER TABLE produits MODIFY COLUMN categorie ENUM('ingredient', 'ingredients', 'plat', 'plats', 'boisson', 'boissons')");
        
        // Étape 2: Mettre à jour les données existantes
        DB::table('produits')->where('categorie', 'ingredient')->update(['categorie' => 'ingredients']);
        DB::table('produits')->where('categorie', 'plat')->update(['categorie' => 'plats']);
        DB::table('produits')->where('categorie', 'boisson')->update(['categorie' => 'boissons']);
        
        // Étape 3: Réduire l'ENUM aux nouvelles valeurs
        DB::statement("ALTER TABLE produits MODIFY COLUMN categorie ENUM('ingredients', 'plats', 'boissons')");
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Étape 1: Étendre l'ENUM pour accepter toutes les valeurs
        DB::statement("ALTER TABLE produits MODIFY COLUMN categorie ENUM('ingredient', 'ingredients', 'plat', 'plats', 'boisson', 'boissons')");
        
        // Étape 2: Mettre à jour les données existantes
        DB::table('produits')->where('categorie', 'ingredients')->update(['categorie' => 'ingredient']);
        DB::table('produits')->where('categorie', 'plats')->update(['categorie' => 'plat']);
        DB::table('produits')->where('categorie', 'boissons')->update(['categorie' => 'boisson']);
        
        // Étape 3: Réduire l'ENUM aux anciennes valeurs
        DB::statement("ALTER TABLE produits MODIFY COLUMN categorie ENUM('ingredient', 'plat', 'boisson')");
    }
};
