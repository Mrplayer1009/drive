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
        Schema::create('notification_pannes', function (Blueprint $table) {
            $table->id();
            $table->foreignId('franchise_id')->constrained()->onDelete('cascade');
            $table->foreignId('camion_id')->constrained()->onDelete('cascade');
            $table->enum('type_panne', ['mecanique', 'electrique', 'pneumatique', 'autre'])->default('mecanique');
            $table->enum('gravite', ['legere', 'moderee', 'grave', 'critique'])->default('moderee');
            $table->text('description_panne');
            $table->text('symptomes');
            $table->enum('statut', ['signalee', 'en_maintenance', 'resolue', 'ignoree'])->default('signalee');
            $table->text('commentaire_admin')->nullable();
            $table->timestamp('date_resolution')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('notification_pannes');
    }
};
