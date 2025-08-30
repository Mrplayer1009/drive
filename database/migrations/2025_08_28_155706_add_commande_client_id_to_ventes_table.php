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
        Schema::table('ventes', function (Blueprint $table) {
            $table->unsignedBigInteger('commande_client_id')->nullable()->after('camion_id');
            $table->foreign('commande_client_id')->references('id')->on('commande_clients')->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('ventes', function (Blueprint $table) {
            $table->dropForeign(['commande_client_id']);
            $table->dropColumn('commande_client_id');
        });
    }
};
