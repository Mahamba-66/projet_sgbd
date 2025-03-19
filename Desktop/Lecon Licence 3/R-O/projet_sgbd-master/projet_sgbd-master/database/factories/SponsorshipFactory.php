<?php

namespace Database\Factories;

use App\Models\User;
use App\Models\Region;
use App\Models\Sponsorship;
use Illuminate\Database\Eloquent\Factories\Factory;

class SponsorshipFactory extends Factory
{
    protected $model = Sponsorship::class;

    public function definition()
    {
        $region = Region::factory()->create();
        $voter = User::factory()->create(['role' => 'voter', 'region_id' => $region->id]);
        $candidate = User::factory()->create(['role' => 'candidate', 'region_id' => $region->id]);

        return [
            'voter_id' => $voter->id,
            'candidate_id' => $candidate->id,
            'region_id' => $region->id,
            'status' => $this->faker->randomElement(['pending', 'approved', 'rejected']),
            'rejection_reason' => null,
        ];
    }

    public function approved()
    {
        return $this->state(function (array $attributes) {
            return [
                'status' => 'approved',
            ];
        });
    }

    public function rejected()
    {
        return $this->state(function (array $attributes) {
            return [
                'status' => 'rejected',
                'rejection_reason' => $this->faker->sentence,
            ];
        });
    }
}
