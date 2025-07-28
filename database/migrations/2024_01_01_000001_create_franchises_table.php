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
        Schema::create('franchises', function (Blueprint $table) {
            $table->id();
            $table->string('nom');
            $table->string('prenom');
            $table->string('email')->unique();
            $table->string('telephone');
            $table->text('adresse');
            $table->string('ville');
            $table->string('code_postal');
            $table->date('date_entree');
            $table->enum('statut', ['actif', 'inactif', 'suspendu'])->default('actif');
            $table->decimal('droits_entree', 10, 2)->default(50000.00);
            $table->decimal('pourcentage_ventes', 5, 2)->default(4.00);
            $table->string('password');
            $table->rememberToken();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('franchises');
    }
}; 