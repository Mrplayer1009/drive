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
        // Mettre à jour les statuts existants pour les food trucks
        DB::statement("ALTER TABLE commande_clients MODIFY COLUMN statut ENUM('en_attente', 'confirmee', 'en_preparation', 'en_route', 'terminee', 'annulee') NOT NULL DEFAULT 'en_attente'");
        
        // Mettre à jour les données existantes
        DB::table('commande_clients')
            ->where('statut', 'en_livraison')
            ->update(['statut' => 'en_route']);
            
        DB::table('commande_clients')
            ->where('statut', 'livree')
            ->update(['statut' => 'terminee']);
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Remettre les anciens statuts
        DB::statement("ALTER TABLE commande_clients MODIFY COLUMN statut ENUM('en_attente', 'confirmee', 'en_preparation', 'en_livraison', 'livree', 'annulee') NOT NULL DEFAULT 'en_attente'");
        
        // Remettre les données originales
        DB::table('commande_clients')
            ->where('statut', 'en_route')
            ->update(['statut' => 'en_livraison']);
            
        DB::table('commande_clients')
            ->where('statut', 'terminee')
            ->update(['statut' => 'livree']);
    }
};
