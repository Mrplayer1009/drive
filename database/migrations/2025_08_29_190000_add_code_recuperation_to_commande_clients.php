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
            $table->string('code_recuperation', 4)->nullable()->after('statut');
            $table->timestamp('date_generation_code')->nullable()->after('code_recuperation');
            $table->timestamp('date_recuperation')->nullable()->after('date_generation_code');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('commande_clients', function (Blueprint $table) {
            $table->dropColumn(['code_recuperation', 'date_generation_code', 'date_recuperation']);
        });
    }
};
