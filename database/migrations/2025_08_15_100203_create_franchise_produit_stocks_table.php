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
        Schema::create('franchise_produit_stocks', function (Blueprint $table) {
            $table->id();
            $table->foreignId('franchise_id')->constrained()->onDelete('cascade');
            $table->foreignId('produit_id')->constrained()->onDelete('cascade');
            $table->decimal('quantite_stock', 10, 2)->default(0);
            $table->decimal('stock_minimum', 10, 2)->default(0);
            $table->timestamps();
            
            // Index unique pour éviter les doublons
            $table->unique(['franchise_id', 'produit_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('franchise_produit_stocks');
    }
};
