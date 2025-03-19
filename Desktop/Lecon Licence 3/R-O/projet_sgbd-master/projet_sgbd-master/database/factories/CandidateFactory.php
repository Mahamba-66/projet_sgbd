<?php

namespace Database\Factories;

use App\Models\Candidate;
use Illuminate\Database\Eloquent\Factories\Factory;

class CandidateFactory extends Factory
{
    protected $model = Candidate::class;

    public function definition()
    {
        return [
            'name' => fake()->name(),
            'party_name' => fake()->company(),
            'status' => 'pending',
            'validation_date' => null,
            'rejection_reason' => null
        ];
    }

    public function pending()
    {
        return $this->state(function (array $attributes) {
            return [
                'status' => 'pending',
                'validation_date' => null,
                'rejection_reason' => null
            ];
        });
    }

    public function validated()
    {
        return $this->state(function (array $attributes) {
            return [
                'status' => 'validated',
                'validation_date' => now(),
                'rejection_reason' => null
            ];
        });
    }

    public function rejected()
    {
        return $this->state(function (array $attributes) {
            return [
                'status' => 'rejected',
                'validation_date' => null,
                'rejection_reason' => fake()->sentence()
            ];
        });
    }
}
