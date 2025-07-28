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
        Schema::create('demande_camions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('franchise_id')->constrained()->onDelete('cascade');
            $table->foreignId('camion_id')->nullable()->constrained()->onDelete('set null');
            $table->enum('type_demande', ['nouveau', 'remplacement'])->default('nouveau');
            $table->enum('statut', ['en_attente', 'approuvee', 'refusee'])->default('en_attente');
            $table->string('type_camion_souhaite')->nullable(); // petit, moyen, grand, refrigere, plateau
            $table->string('localisation_souhaitee');
            $table->date('date_debut_souhaitee');
            $table->enum('duree_attribution', ['temporaire', 'semaine', 'mois', 'permanent'])->default('mois');
            $table->text('motif');
            $table->boolean('urgent')->default(false);
            $table->text('commentaire_admin')->nullable();
            $table->timestamp('date_reponse')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('demande_camions');
    }
};
