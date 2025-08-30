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
        Schema::table('franchises', function (Blueprint $table) {
            $table->decimal('latitude', 10, 8)->nullable()->after('telephone');
            $table->decimal('longitude', 11, 8)->nullable()->after('latitude');
            $table->string('adresse_emplacement')->nullable()->after('longitude');
            $table->boolean('disponible')->default(true)->after('adresse_emplacement');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('franchises', function (Blueprint $table) {
            $table->dropColumn(['latitude', 'longitude', 'adresse_emplacement', 'disponible']);
        });
    }
};
