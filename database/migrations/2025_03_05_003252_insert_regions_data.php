<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up()
    {
        $regions = [
            ['name' => 'Dakar', 'code' => 'DKR'],
            ['name' => 'Thiès', 'code' => 'THS'],
            ['name' => 'Saint-Louis', 'code' => 'STL'],
            ['name' => 'Diourbel', 'code' => 'DBL'],
            ['name' => 'Louga', 'code' => 'LGA'],
            ['name' => 'Fatick', 'code' => 'FTK'],
            ['name' => 'Kaolack', 'code' => 'KLK'],
            ['name' => 'Kaffrine', 'code' => 'KFR'],
            ['name' => 'Tambacounda', 'code' => 'TMB'],
            ['name' => 'Kolda', 'code' => 'KLD'],
            ['name' => 'Ziguinchor', 'code' => 'ZGR'],
            ['name' => 'Sédhiou', 'code' => 'SDH'],
            ['name' => 'Kédougou', 'code' => 'KDG'],
            ['name' => 'Matam', 'code' => 'MTM'],
        ];

        foreach ($regions as $region) {
            DB::table('regions')->insert([
                'name' => $region['name'],
                'code' => $region['code'],
                'created_at' => now(),
                'updated_at' => now()
            ]);
        }
    }

    public function down()
    {
        DB::table('regions')->truncate();
    }
};
