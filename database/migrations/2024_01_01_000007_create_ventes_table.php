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
        Schema::create('ventes', function (Blueprint $table) {
            $table->id();
            $table->foreignId('franchise_id')->constrained()->onDelete('cascade');
            $table->foreignId('camion_id')->nullable()->constrained()->onDelete('set null');
            $table->date('date_vente');
            $table->decimal('montant_total', 10, 2);
            $table->decimal('pourcentage_reverse', 5, 2)->default(4.00);
            $table->decimal('montant_reverse', 10, 2);
            $table->integer('nombre_commandes')->default(0);
            $table->text('notes')->nullable();
            $table->string('pdf_path')->nullable(); // Chemin vers le PDF généré
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('ventes');
    }
}; 