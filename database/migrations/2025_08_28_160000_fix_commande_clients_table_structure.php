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
        // Vérifier si la table existe
        if (!Schema::hasTable('commande_clients')) {
            Schema::create('commande_clients', function (Blueprint $table) {
                $table->id();
                $table->foreignId('client_id')->constrained()->onDelete('cascade');
                $table->foreignId('franchise_id')->constrained()->onDelete('cascade');
                $table->enum('statut', [
                    'en_attente',
                    'confirmee', 
                    'en_preparation',
                    'en_livraison',
                    'livree',
                    'annulee'
                ])->default('en_attente');
                $table->decimal('montant_total', 10, 2);
                $table->decimal('reduction_fidelite', 10, 2)->default(0);
                $table->decimal('montant_final', 10, 2);
                $table->text('notes')->nullable();
                $table->string('adresse_livraison');
                $table->string('telephone_contact');
                $table->string('mode_paiement')->nullable();
                $table->string('reference_paiement')->nullable();
                $table->timestamp('date_commande')->useCurrent();
                $table->timestamp('date_livraison_souhaitee')->nullable();
                $table->timestamp('date_livraison_effective')->nullable();
                $table->timestamps();
            });
        } else {
            // Si la table existe, modifier la colonne statut
            Schema::table('commande_clients', function (Blueprint $table) {
                // Supprimer l'ancienne colonne statut si elle existe
                if (Schema::hasColumn('commande_clients', 'statut')) {
                    $table->dropColumn('statut');
                }
                
                // Recréer la colonne statut avec la bonne taille
                $table->enum('statut', [
                    'en_attente',
                    'confirmee', 
                    'en_preparation',
                    'en_livraison',
                    'livree',
                    'annulee'
                ])->default('en_attente')->after('franchise_id');
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('commande_clients');
    }
};

