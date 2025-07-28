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
        Schema::create('commandes', function (Blueprint $table) {
            $table->id();
            $table->foreignId('franchise_id')->constrained()->onDelete('cascade');
            $table->foreignId('entrepot_id')->constrained()->onDelete('cascade');
            $table->enum('statut', ['en_attente', 'validee', 'livree', 'annulee'])->default('en_attente');
            $table->decimal('total_commande', 10, 2)->default(0);
            $table->decimal('total_obligatoire', 10, 2)->default(0); // 80% minimum
            $table->decimal('total_libre', 10, 2)->default(0); // 20% libre
            $table->text('notes')->nullable();
            $table->string('pdf_path')->nullable(); // Chemin vers le PDF généré
            $table->date('date_commande');
            $table->date('date_livraison')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('commandes');
    }
}; 