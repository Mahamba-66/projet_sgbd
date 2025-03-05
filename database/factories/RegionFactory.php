<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

class RegionFactory extends Factory
{
    public function definition(): array
    {
        return [
            'name' => fake()->unique()->randomElement([
                'Dakar',
                'Thiès',
                'Diourbel',
                'Fatick',
                'Kaolack',
                'Kaffrine',
                'Kolda',
                'Kédougou',
                'Louga',
                'Matam',
                'Saint-Louis',
                'Sédhiou',
                'Tambacounda',
                'Ziguinchor'
            ]),
            'code' => fake()->unique()->numerify('REG###'),
        ];
    }
}
