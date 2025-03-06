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

    public function statusState($status, $validationDate = null, $rejectionReason = null)
    {
        return $this->state(function () use ($status, $validationDate, $rejectionReason) {
            return compact('status', 'validation_date', 'rejection_reason');
        });
    }

    public function pending()
    {
        return $this->statusState('pending');
    }

    public function validated()
    {
        return $this->statusState('validated', now());
    }

    public function rejected()
    {
        return $this->statusState('rejected', null, fake()->sentence());
    }
}
