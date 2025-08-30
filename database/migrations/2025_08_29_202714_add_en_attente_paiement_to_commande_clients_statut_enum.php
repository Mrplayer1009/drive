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
        // Modifier l'ENUM pour inclure 'en_attente_paiement'
        DB::statement("ALTER TABLE commande_clients MODIFY COLUMN statut ENUM('en_attente', 'en_attente_paiement', 'confirmee', 'en_preparation', 'prete', 'terminee', 'annulee') NOT NULL DEFAULT 'en_attente'");
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Revenir à l'ENUM original
        DB::statement("ALTER TABLE commande_clients MODIFY COLUMN statut ENUM('en_attente', 'confirmee', 'en_preparation', 'prete', 'terminee', 'annulee') NOT NULL DEFAULT 'en_attente'");
    }
};
