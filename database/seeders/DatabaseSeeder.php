<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $this->call([
            AdminSeeder::class,
            EntrepotSeeder::class,
            ProduitSeeder::class,
            FranchiseSeeder::class,
            CamionSeeder::class,
            CommandeSeeder::class,
            VenteSeeder::class,
        ]);
    }
}
