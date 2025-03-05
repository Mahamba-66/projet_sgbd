<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $this->call([
            AdminSeeder::class,
            RegionsSeeder::class,
            EligibleVotersSeeder::class
        ]);

        // Créer un électeur par défaut
        User::create([
            'name' => 'Voter',
            'email' => 'voter@example.com',
            'password' => bcrypt('password'),
            'role' => 'voter',
            'nin' => '1234567890123',
            'voter_card_number' => 'VOTER123',
            'status' => 'active'
        ]);
    }
}
