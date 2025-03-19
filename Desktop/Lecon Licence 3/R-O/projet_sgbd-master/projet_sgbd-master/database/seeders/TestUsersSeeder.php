<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Region;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class TestUsersSeeder extends Seeder
{
    public function run()
    {
        // Création de l'admin
        User::create([
            'name' => 'Admin Test',
            'email' => 'admin@parrainage.sn',
            'password' => Hash::make('password123'),
            'role' => 'admin',
            'nin' => 'ADMIN123456',
            'voter_card_number' => 'ADMINCARD123',
            'phone' => '770000001',
            'region_id' => Region::first()->id
        ]);

        // Création des candidats
        $regions = Region::all();
        $candidates = [
            [
                'name' => 'Candidat 1',
                'email' => 'candidat1@parrainage.sn',
                'nin' => 'CAND100000',
                'voter_card_number' => 'CANDCARD100',
                'phone' => '770000002',
                'party_name' => 'Parti du Progrès',
                'biography' => 'Candidat engagé pour le développement du Sénégal.'
            ],
            [
                'name' => 'Candidat 2',
                'email' => 'candidat2@parrainage.sn',
                'nin' => 'CAND200000',
                'voter_card_number' => 'CANDCARD200',
                'phone' => '770000003',
                'party_name' => 'Union pour le Changement',
                'biography' => 'Pour un Sénégal nouveau et prospère.'
            ]
        ];

        foreach ($candidates as $candidate) {
            User::create([
                'name' => $candidate['name'],
                'email' => $candidate['email'],
                'password' => Hash::make('password123'),
                'role' => 'candidate',
                'nin' => $candidate['nin'],
                'voter_card_number' => $candidate['voter_card_number'],
                'phone' => $candidate['phone'],
                'party_name' => $candidate['party_name'],
                'biography' => $candidate['biography'],
                'region_id' => $regions->random()->id
            ]);
        }

        // Création des électeurs
        for ($i = 1; $i <= 10; $i++) {
            User::create([
                'name' => "Électeur $i",
                'email' => "electeur$i@parrainage.sn",
                'password' => Hash::make('password123'),
                'role' => 'voter',
                'nin' => "VOTER{$i}0000",
                'voter_card_number' => "VOTERCARD{$i}00",
                'phone' => "77000" . str_pad($i + 3, 4, '0', STR_PAD_LEFT),
                'region_id' => $regions->random()->id
            ]);
        }
    }
}
