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
        // Modifier l'enum pour ajouter 'refusee'
        DB::statement("ALTER TABLE commandes MODIFY COLUMN statut ENUM('en_attente', 'validee', 'refusee', 'livree', 'annulee') DEFAULT 'en_attente'");
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Retirer 'refusee' de l'enum
        DB::statement("ALTER TABLE commandes MODIFY COLUMN statut ENUM('en_attente', 'validee', 'livree', 'annulee') DEFAULT 'en_attente'");
    }
};
