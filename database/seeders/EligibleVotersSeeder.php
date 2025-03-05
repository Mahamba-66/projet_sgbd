<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\EligibleVoter;
use Illuminate\Support\Facades\DB;

class EligibleVotersSeeder extends Seeder
{
    public function run()
    {
        DB::table('eligible_voters')->insert([
            [
                'first_name' => 'Amadou',
                'last_name' => 'Diallo',
                'card_number' => 'SN2025001',
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'first_name' => 'Fatou',
                'last_name' => 'Sow',
                'card_number' => 'SN2025002',
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'first_name' => 'Moussa',
                'last_name' => 'Ndiaye',
                'card_number' => 'SN2025003',
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'first_name' => 'Aissatou',
                'last_name' => 'Ba',
                'card_number' => 'SN2025004',
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'first_name' => 'Omar',
                'last_name' => 'Fall',
                'card_number' => 'SN2025005',
                'created_at' => now(),
                'updated_at' => now()
            ]
        ]);
    }
}
