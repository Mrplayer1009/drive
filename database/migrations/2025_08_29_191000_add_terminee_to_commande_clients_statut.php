<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('commande_clients', function (Blueprint $table) {
            // Modifier l'ENUM pour inclure 'terminee'
            DB::statement("ALTER TABLE commande_clients MODIFY COLUMN statut ENUM('en_attente', 'confirmee', 'en_preparation', 'prete', 'terminee', 'annulee')");
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('commande_clients', function (Blueprint $table) {
            // Revenir à l'ENUM sans 'terminee'
            DB::statement("ALTER TABLE commande_clients MODIFY COLUMN statut ENUM('en_attente', 'confirmee', 'en_preparation', 'prete', 'annulee')");
        });
    }
};
