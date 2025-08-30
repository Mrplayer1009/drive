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
            if (!Schema::hasColumn('commande_clients', 'reduction_fidelite')) {
                $table->decimal('reduction_fidelite', 8, 2)->default(0.00)->after('montant_final');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('commande_clients', function (Blueprint $table) {
            $table->dropColumn('reduction_fidelite');
        });
    }
};
