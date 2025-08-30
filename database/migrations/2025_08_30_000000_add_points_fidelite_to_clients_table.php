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
        Schema::table('clients', function (Blueprint $table) {
            if (!Schema::hasColumn('clients', 'niveau_fidelite')) {
                $table->integer('niveau_fidelite')->default(1)->after('points_fidelite');
            }
            if (!Schema::hasColumn('clients', 'reduction_cumulee')) {
                $table->decimal('reduction_cumulee', 8, 2)->default(0.00)->after('niveau_fidelite');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('clients', function (Blueprint $table) {
            $table->dropColumn(['points_fidelite', 'niveau_fidelite', 'reduction_cumulee']);
        });
    }
};
