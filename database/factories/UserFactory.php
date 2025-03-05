<?php

namespace Database\Factories;

use App\Models\Region;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Hash;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\User>
 */
class UserFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $region = Region::first() ?? Region::factory()->create();

        return [
            'name' => fake()->name(),
            'email' => fake()->unique()->safeEmail(),
            'email_verified_at' => now(),
            'password' => Hash::make('password'),
            'remember_token' => Str::random(10),
            'role' => 'voter',
            'nin' => fake()->numerify('#############'),
            'voter_card_number' => 'CARD' . Str::random(8),
            'phone' => fake()->phoneNumber(),
            'region_id' => $region->id,
            'status' => 'pending',
        ];
    }

    /**
     * Indicate that the model's email address should be unverified.
     */
    public function unverified(): static
    {
        return $this->state(fn (array $attributes) => [
            'email_verified_at' => null,
        ]);
    }

    /**
     * Indicate that the user is a voter.
     */
    public function voter(): static
    {
        return $this->state(fn (array $attributes) => [
            'role' => 'voter',
        ]);
    }

    /**
     * Indicate that the user is a candidate.
     */
    public function candidate(): static
    {
        return $this->state(fn (array $attributes) => [
            'role' => 'candidate',
            'party_name' => fake()->company(),
            'party_position' => fake()->jobTitle(),
            'birth_date' => fake()->dateTimeBetween('-60 years', '-35 years')->format('Y-m-d'),
        ]);
    }

    /**
     * Indicate that the user is an admin.
     */
    public function admin(): static
    {
        return $this->state(fn (array $attributes) => [
            'role' => 'admin',
        ]);
    }
}
