<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Region;

class RegionSeeder extends Seeder
{
    public function run(): void
    {
        $regions = [
            ['name' => 'Dakar', 'code' => 'DKR'],
            ['name' => 'Thiès', 'code' => 'THS'],
            ['name' => 'Diourbel', 'code' => 'DBL'],
            ['name' => 'Fatick', 'code' => 'FTK'],
            ['name' => 'Kaolack', 'code' => 'KLK'],
            ['name' => 'Kaffrine', 'code' => 'KFR'],
            ['name' => 'Kolda', 'code' => 'KLD'],
            ['name' => 'Kédougou', 'code' => 'KDG'],
            ['name' => 'Louga', 'code' => 'LGA'],
            ['name' => 'Matam', 'code' => 'MTM'],
            ['name' => 'Saint-Louis', 'code' => 'STL'],
            ['name' => 'Sédhiou', 'code' => 'SDH'],
            ['name' => 'Tambacounda', 'code' => 'TBC'],
            ['name' => 'Ziguinchor', 'code' => 'ZGR']
        ];

        foreach ($regions as $region) {
            Region::create($region);
        }
    }
}
