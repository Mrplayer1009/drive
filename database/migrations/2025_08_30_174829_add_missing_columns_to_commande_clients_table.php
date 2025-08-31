<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('commande_clients', function (Blueprint $table) {
            if (!Schema::hasColumn('commande_clients', 'client_id')) {
                $table->foreignId('client_id')->constrained()->cascadeOnDelete();
            }
            if (!Schema::hasColumn('commande_clients', 'franchise_id')) {
                $table->foreignId('franchise_id')->constrained()->cascadeOnDelete();
            }
        });
    }

    public function down(): void
    {
        Schema::table('commande_clients', function (Blueprint $table) {
            if (Schema::hasColumn('commande_clients', 'client_id')) {
                $table->dropConstrainedForeignId('client_id');
            }
            if (Schema::hasColumn('commande_clients', 'franchise_id')) {
                $table->dropConstrainedForeignId('franchise_id');
            }
        });
    }
};
