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
        Schema::create('produits', function (Blueprint $table) {
            $table->id();
            $table->string('nom');
            $table->text('description')->nullable();
            $table->enum('categorie', ['ingredient', 'plat', 'boisson']);
            $table->decimal('prix_unitaire', 8, 2);
            $table->string('unite_mesure'); // kg, l, unité, etc.
            $table->integer('stock_minimum')->default(0);
            $table->boolean('obligatoire')->default(false); // Pour la règle 80/20
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('produits');
    }
}; 