<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class AdminSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        User::create([
            'name' => 'Administrateur Driv\'n Cook',
            'email' => 'admin@drivncook.com',
            'password' => Hash::make('admin123'),
        ]);
    }
} 