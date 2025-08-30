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
        Schema::table('menus', function (Blueprint $table) {
            $table->string('nom')->after('id');
            $table->text('description')->after('nom');
            $table->decimal('prix', 8, 2)->after('description');
            $table->enum('categorie', ['burger', 'boisson', 'dessert', 'accompagnement'])->after('prix');
            $table->boolean('disponible')->default(true)->after('categorie');
            $table->boolean('special')->default(false)->after('disponible');
            $table->integer('ordre_affichage')->default(0)->after('special');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('menus', function (Blueprint $table) {
            $table->dropColumn([
                'nom',
                'description',
                'prix',
                'categorie',
                'disponible',
                'special',
                'ordre_affichage'
            ]);
        });
    }
};
