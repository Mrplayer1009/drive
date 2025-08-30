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
        Schema::table('commande_clients', function (Blueprint $table) {
            $table->decimal('montant_total', 10, 2)->after('statut');
            $table->decimal('reduction_fidelite', 10, 2)->default(0)->after('montant_total');
            $table->decimal('montant_final', 10, 2)->after('reduction_fidelite');
            $table->text('notes')->nullable()->after('montant_final');
            $table->string('adresse_livraison')->after('notes');
            $table->string('telephone_contact')->after('adresse_livraison');
            $table->string('mode_paiement')->nullable()->after('telephone_contact');
            $table->string('reference_paiement')->nullable()->after('mode_paiement');
            $table->timestamp('date_commande')->useCurrent()->after('reference_paiement');
            $table->timestamp('date_livraison_souhaitee')->nullable()->after('date_commande');
            $table->timestamp('date_livraison_effective')->nullable()->after('date_livraison_souhaitee');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('commande_clients', function (Blueprint $table) {
            $table->dropColumn([
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
                'date_livraison_effective'
            ]);
        });
    }
};
