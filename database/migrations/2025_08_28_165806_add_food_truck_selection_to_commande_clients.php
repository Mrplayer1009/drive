<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('commande_clients', function (Blueprint $table) {
            if (!Schema::hasColumn('commande_clients', 'food_truck_id')) {
                $table->foreignId('food_truck_id')
                      ->nullable()
                      ->constrained('franchises')
                      ->nullOnDelete();
            }
        });
    }

    public function down(): void
    {
        Schema::table('commande_clients', function (Blueprint $table) {
            if (Schema::hasColumn('commande_clients', 'food_truck_id')) {
                $table->dropConstrainedForeignId('food_truck_id');
            }
        });
    }
};
