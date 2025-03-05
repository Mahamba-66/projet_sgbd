<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;

class AuthTest extends TestCase
{
    use RefreshDatabase;

    public function test_voter_registration()
    {
        $response = $this->post('/register', [
            'name' => 'Test Voter',
            'email' => 'voter@test.com',
            'password' => 'password123',
            'password_confirmation' => 'password123',
            'user_type' => 'voter',
            'phone_number' => '771234567',
            'voter_card_number' => 'VC123456',
            'national_id_number' => 'NID123456',
            'polling_station' => 'Dakar Centre'
        ]);

        $this->assertTrue(User::where('email', 'voter@test.com')->exists());
        $user = User::where('email', 'voter@test.com')->first();
        $this->assertEquals('voter', $user->user_type);
    }

    public function test_candidate_registration()
    {
        $response = $this->post('/register', [
            'name' => 'Test Candidate',
            'email' => 'candidate@test.com',
            'password' => 'password123',
            'password_confirmation' => 'password123',
            'user_type' => 'candidate',
            'phone_number' => '771234568'
        ]);

        $this->assertTrue(User::where('email', 'candidate@test.com')->exists());
        $user = User::where('email', 'candidate@test.com')->first();
        $this->assertEquals('candidate', $user->user_type);
    }

    public function test_admin_registration()
    {
        $response = $this->post('/register', [
            'name' => 'Test Admin',
            'email' => 'admin@test.com',
            'password' => 'password123',
            'password_confirmation' => 'password123',
            'user_type' => 'admin',
            'phone_number' => '771234569'
        ]);

        $this->assertTrue(User::where('email', 'admin@test.com')->exists());
        $user = User::where('email', 'admin@test.com')->first();
        $this->assertEquals('admin', $user->user_type);
    }

    public function test_login_and_redirect()
    {
        // Créer un utilisateur de test
        $user = User::factory()->create([
            'email' => 'test@example.com',
            'password' => bcrypt('password123'),
            'user_type' => 'voter'
        ]);

        // Tester la connexion
        $response = $this->post('/login', [
            'email' => 'test@example.com',
            'password' => 'password123'
        ]);

        $this->assertAuthenticated();
        $response->assertRedirect('/voter/dashboard');
    }

    public function test_voter_fields_required()
    {
        $response = $this->post('/register', [
            'name' => 'Test Voter',
            'email' => 'voter@test.com',
            'password' => 'password123',
            'password_confirmation' => 'password123',
            'user_type' => 'voter',
            'phone_number' => '771234567'
            // Champs manquants : voter_card_number, national_id_number, polling_station
        ]);

        $response->assertSessionHasErrors(['voter_card_number', 'national_id_number', 'polling_station']);
    }
}
