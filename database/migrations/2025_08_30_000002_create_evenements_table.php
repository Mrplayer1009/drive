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
        Schema::create('evenements', function (Blueprint $table) {
            $table->id();
            $table->foreignId('franchise_id')->constrained('franchises')->onDelete('cascade');
            $table->string('titre');
            $table->text('description');
            $table->dateTime('date_evenement');
            $table->integer('prix_points'); // Prix en points de fidélité
            $table->integer('nombre_max_participants');
            $table->integer('nombre_inscrits')->default(0);
            $table->enum('statut', ['actif', 'annule', 'termine'])->default('actif');
            $table->string('lieu')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('evenements');
    }
};
