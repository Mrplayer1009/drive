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
        Schema::table('commande_client_menus', function (Blueprint $table) {
            $table->foreignId('commande_client_id')->constrained('commande_clients')->onDelete('cascade')->after('id');
            $table->foreignId('menu_id')->constrained('menus')->onDelete('cascade')->after('commande_client_id');
            $table->integer('quantite')->after('menu_id');
            $table->decimal('prix_unitaire', 8, 2)->after('quantite');
            $table->decimal('prix_total', 8, 2)->after('prix_unitaire');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('commande_client_menus', function (Blueprint $table) {
            $table->dropForeign(['commande_client_id']);
            $table->dropForeign(['menu_id']);
            $table->dropColumn([
                'commande_client_id',
                'menu_id',
                'quantite',
                'prix_unitaire',
                'prix_total'
            ]);
        });
    }
};
