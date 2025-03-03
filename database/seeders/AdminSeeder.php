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
            'name' => 'Admin',
            'email' => 'admin@parrainage.sn',
            'password' => Hash::make('Seydina2004'),
            'user_type' => 'admin',
            'email_verified_at' => now(),
        ]);
    }
}
