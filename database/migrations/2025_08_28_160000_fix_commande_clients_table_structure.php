<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
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
                ])->nullable();
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
            Schema::table('commande_clients', function (Blueprint $table) {
                $enum = [
                    'en_attente',
                    'confirmee',
                    'en_preparation',
                    'en_livraison',
                    'livree',
                    'annulee'
                ];
                if (Schema::hasColumn('commande_clients', 'statut')) {
                    $table->enum('statut', $enum)->nullable()->change();
                } else {
                    $table->enum('statut', $enum)->nullable();
                }
                if (!Schema::hasColumn('commande_clients', 'franchise_id')) {
                    $table->foreignId('franchise_id')->nullable()->constrained()->nullOnDelete();
                }
            });
        }
    }

    public function down(): void
    {
        Schema::dropIfExists('commande_clients');
    }
};


