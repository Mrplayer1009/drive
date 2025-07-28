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
        Schema::table('franchise_camion', function (Blueprint $table) {
            // Supprimer l'ancien enum
            $table->dropColumn('statut');
        });

        Schema::table('franchise_camion', function (Blueprint $table) {
            // Ajouter le nouvel enum avec les bonnes valeurs
            $table->enum('statut', ['actif', 'inactif', 'en_utilisation', 'en_maintenance'])->default('actif')->after('date_fin');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('franchise_camion', function (Blueprint $table) {
            // Revenir à l'ancien enum
            $table->dropColumn('statut');
        });

        Schema::table('franchise_camion', function (Blueprint $table) {
            $table->enum('statut', ['actif', 'inactif'])->default('actif')->after('date_fin');
        });
    }
};
