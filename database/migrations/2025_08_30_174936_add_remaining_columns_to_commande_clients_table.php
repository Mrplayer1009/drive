<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('commande_clients', function (Blueprint $table) {
            if (!Schema::hasColumn('commande_clients', 'montant_total')) {
                if (Schema::hasColumn('commande_clients', 'statut')) {
                    $table->decimal('montant_total', 10, 2)->after('statut');
                } else {
                    $table->decimal('montant_total', 10, 2);
                }
            }

            if (!Schema::hasColumn('commande_clients', 'reduction_fidelite')) {
                if (Schema::hasColumn('commande_clients', 'montant_total')) {
                    $table->decimal('reduction_fidelite', 10, 2)->default(0)->after('montant_total');
                } else {
                    $table->decimal('reduction_fidelite', 10, 2)->default(0);
                }
            }

            if (!Schema::hasColumn('commande_clients', 'montant_final')) {
                if (Schema::hasColumn('commande_clients', 'reduction_fidelite')) {
                    $table->decimal('montant_final', 10, 2)->after('reduction_fidelite');
                } else {
                    $table->decimal('montant_final', 10, 2);
                }
            }

            if (!Schema::hasColumn('commande_clients', 'notes')) {
                $table->text('notes')->nullable();
            }

            if (!Schema::hasColumn('commande_clients', 'adresse_livraison')) {
                $table->string('adresse_livraison');
            }

            if (!Schema::hasColumn('commande_clients', 'telephone_contact')) {
                $table->string('telephone_contact');
            }

            if (!Schema::hasColumn('commande_clients', 'mode_paiement')) {
                $table->string('mode_paiement')->nullable();
            }

            if (!Schema::hasColumn('commande_clients', 'reference_paiement')) {
                $table->string('reference_paiement')->nullable();
            }

            if (!Schema::hasColumn('commande_clients', 'date_commande')) {
                $table->timestamp('date_commande')->useCurrent();
            }

            if (!Schema::hasColumn('commande_clients', 'date_livraison_souhaitee')) {
                $table->timestamp('date_livraison_souhaitee')->nullable();
            }

            if (!Schema::hasColumn('commande_clients', 'date_livraison_effective')) {
                $table->timestamp('date_livraison_effective')->nullable();
            }
        });
    }

    public function down(): void
    {
        Schema::table('commande_clients', function (Blueprint $table) {
            $cols = [
                'montant_total',
                'reduction_fidelite',
                'montant_final',
                'notes',
                'adresse_livraison',
                'telephone_contact',
                'mode_paiement',
                'reference_paiement',
                'date_commande',
                'date_livraison_souhaitee',
                'date_livraison_effective',
            ];
            foreach ($cols as $col) {
                if (Schema::hasColumn('commande_clients', $col)) {
                    $table->dropColumn($col);
                }
            }
        });
    }
};
