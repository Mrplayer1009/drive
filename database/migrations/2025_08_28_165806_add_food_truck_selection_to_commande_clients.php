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
            $table->unsignedBigInteger('food_truck_id')->nullable()->after('client_id');
            $table->foreign('food_truck_id')->references('id')->on('franchises')->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('commande_clients', function (Blueprint $table) {
            $table->dropForeign(['food_truck_id']);
            $table->dropColumn('food_truck_id');
        });
    }
};
