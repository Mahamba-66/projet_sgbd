<?php

namespace Tests\Feature;

use App\Models\User;
use App\Models\Region;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class FlowTest extends TestCase
{
    use RefreshDatabase;
    use WithFaker;

    protected function setUp(): void
    {
        parent::setUp();
        $this->seed(\Database\Seeders\RegionSeeder::class);
    }

    public function test_home_page_loads()
    {
        $response = $this->get('/');
        $response->assertStatus(200);
    }

    public function test_register_type_page_loads_and_contains_required_elements()
    {
        $response = $this->get('/register');
        $response->assertStatus(200);
        $response->assertSeeText('Choisissez votre type d\'inscription', false);
        $response->assertSeeText('Électeur', false);
        $response->assertSeeText('Candidat', false);
    }

    public function test_register_type_page_has_correct_links()
    {
        $response = $this->get('/register');
        $response->assertStatus(200);
        
        // Vérifier que les liens sont présents dans le HTML
        $content = $response->getContent();
        $this->assertStringContainsString(route('voter.register'), $content);
        $this->assertStringContainsString(route('candidate.register'), $content);
        $this->assertStringContainsString(route('login'), $content);
    }

    public function test_voter_registration_flow()
    {
        $region = Region::first();
        
        // Test registration page loads
        $response = $this->get('/register/voter');
        $response->assertStatus(200);

        // Test successful registration
        $userData = [
            'name' => 'Test Voter',
            'email' => 'voter@test.com',
            'password' => 'password123',
            'password_confirmation' => 'password123',
            'nin' => '1234567890123',
            'voter_card_number' => 'VOTER123',
            'region_id' => $region->id,
        ];

        $response = $this->post('/register/voter', $userData);
        $response->assertRedirect('/voter/dashboard');
        $this->assertAuthenticated();

        $this->assertDatabaseHas('users', [
            'email' => 'voter@test.com',
            'role' => 'voter',
            'name' => 'Test Voter',
            'nin' => '1234567890123',
            'voter_card_number' => 'VOTER123',
            'region_id' => $region->id,
        ]);
    }

    public function test_voter_registration_validation()
    {
        // Test validation errors
        $response = $this->post('/register/voter', []);
        $response->assertSessionHasErrors(['name', 'email', 'password', 'nin', 'voter_card_number', 'region_id']);

        // Test unique email validation
        User::factory()->create(['email' => 'existing@test.com']);
        $response = $this->post('/register/voter', [
            'email' => 'existing@test.com',
        ]);
        $response->assertSessionHasErrors('email');

        // Test unique NIN validation
        User::factory()->create(['nin' => '9876543210123']);
        $response = $this->post('/register/voter', [
            'nin' => '9876543210123',
        ]);
        $response->assertSessionHasErrors('nin');
    }

    public function test_candidate_registration_flow()
    {
        // Test registration page loads
        $response = $this->get('/register/candidate');
        $response->assertStatus(200);

        // Test successful registration
        $userData = [
            'name' => 'Test Candidate',
            'email' => 'candidate@test.com',
            'password' => 'password123',
            'password_confirmation' => 'password123',
            'nin' => '1234567890123',
            'birth_date' => '1980-01-01',
            'party_name' => 'Test Party',
            'party_position' => 'Leader'
        ];

        $response = $this->post('/register/candidate', $userData);
        $response->assertRedirect('/candidate/dashboard');
        $this->assertAuthenticated();

        $this->assertDatabaseHas('users', [
            'email' => 'candidate@test.com',
            'role' => 'candidate',
            'name' => 'Test Candidate',
            'nin' => '1234567890123',
            'party_name' => 'Test Party',
            'party_position' => 'Leader'
        ]);
    }

    public function test_candidate_registration_validation()
    {
        // Test validation errors
        $response = $this->post('/register/candidate', []);
        $response->assertSessionHasErrors(['name', 'email', 'password', 'nin', 'birth_date', 'party_name', 'party_position']);

        // Test birth date validation (must be at least 35 years old)
        $response = $this->post('/register/candidate', [
            'birth_date' => now()->subYears(30)->format('Y-m-d')
        ]);
        $response->assertSessionHasErrors('birth_date');

        // Test unique email and NIN
        User::factory()->create([
            'email' => 'existing@test.com',
            'nin' => '9876543210123'
        ]);

        $response = $this->post('/register/candidate', [
            'email' => 'existing@test.com',
            'nin' => '9876543210123'
        ]);
        $response->assertSessionHasErrors(['email', 'nin']);
    }

    public function test_login_flow()
    {
        // Create test users
        $voter = User::factory()->create([
            'email' => 'voter@test.com',
            'password' => bcrypt('password123'),
            'role' => 'voter'
        ]);

        $candidate = User::factory()->create([
            'email' => 'candidate@test.com',
            'password' => bcrypt('password123'),
            'role' => 'candidate'
        ]);

        $admin = User::factory()->create([
            'email' => 'admin@test.com',
            'password' => bcrypt('password123'),
            'role' => 'admin'
        ]);

        // Test login page loads
        $response = $this->get('/login');
        $response->assertStatus(200);

        // Test voter login and redirection
        $response = $this->post('/login', [
            'email' => 'voter@test.com',
            'password' => 'password123'
        ]);
        $response->assertRedirect('/voter/dashboard');
        $this->assertAuthenticatedAs($voter);

        // Logout
        $this->post('/logout');

        // Test candidate login and redirection
        $response = $this->post('/login', [
            'email' => 'candidate@test.com',
            'password' => 'password123'
        ]);
        $response->assertRedirect('/candidate/dashboard');
        $this->assertAuthenticatedAs($candidate);

        // Logout
        $this->post('/logout');

        // Test admin login and redirection
        $response = $this->post('/login', [
            'email' => 'admin@test.com',
            'password' => 'password123'
        ]);
        $response->assertRedirect('/admin/dashboard');
        $this->assertAuthenticatedAs($admin);
    }

    public function test_login_validation()
    {
        // Test required fields
        $response = $this->post('/login', []);
        $response->assertSessionHasErrors(['email', 'password']);

        // Test invalid credentials
        $response = $this->post('/login', [
            'email' => 'nonexistent@test.com',
            'password' => 'wrongpassword'
        ]);
        $response->assertSessionHasErrors();
        $this->assertGuest();
    }

    public function test_authenticated_users_cannot_access_registration_pages()
    {
        $user = User::factory()->create();
        $this->actingAs($user);

        $response = $this->get('/register');
        $response->assertRedirect('/home');

        $response = $this->get('/register/voter');
        $response->assertRedirect('/home');

        $response = $this->get('/register/candidate');
        $response->assertRedirect('/home');
    }

    public function test_authenticated_users_cannot_access_login_page()
    {
        $user = User::factory()->create();
        $this->actingAs($user);

        $response = $this->get('/login');
        $response->assertRedirect('/home');
    }

    public function test_role_based_redirections()
    {
        // Créer les utilisateurs de test
        $voter = User::factory()->create([
            'role' => 'voter',
            'email' => 'voter.test@example.com'
        ]);

        $candidate = User::factory()->create([
            'role' => 'candidate',
            'email' => 'candidate.test@example.com'
        ]);

        $admin = User::factory()->create([
            'role' => 'admin',
            'email' => 'admin.test@example.com'
        ]);

        // Test des redirections pour l'électeur
        $this->actingAs($voter);
        
        // L'électeur ne devrait pas accéder au dashboard candidat
        $response = $this->get('/candidate/dashboard');
        $response->assertRedirect('/');
        
        // L'électeur ne devrait pas accéder au dashboard admin
        $response = $this->get('/admin/dashboard');
        $response->assertRedirect('/');
        
        // L'électeur devrait accéder à son propre dashboard
        $response = $this->get('/voter/dashboard');
        $response->assertStatus(200);

        // Test des redirections pour le candidat
        $this->actingAs($candidate);
        
        // Le candidat ne devrait pas accéder au dashboard électeur
        $response = $this->get('/voter/dashboard');
        $response->assertRedirect('/');
        
        // Le candidat ne devrait pas accéder au dashboard admin
        $response = $this->get('/admin/dashboard');
        $response->assertRedirect('/');
        
        // Le candidat devrait accéder à son propre dashboard
        $response = $this->get('/candidate/dashboard');
        $response->assertStatus(200);

        // Test des redirections pour l'admin
        $this->actingAs($admin);
        
        // L'admin ne devrait pas accéder au dashboard électeur
        $response = $this->get('/voter/dashboard');
        $response->assertRedirect('/');
        
        // L'admin ne devrait pas accéder au dashboard candidat
        $response = $this->get('/candidate/dashboard');
        $response->assertRedirect('/');
        
        // L'admin devrait accéder à son propre dashboard
        $response = $this->get('/admin/dashboard');
        $response->assertStatus(200);
    }

    public function test_guest_redirections()
    {
        // Les invités devraient être redirigés vers la page de connexion
        $response = $this->get('/voter/dashboard');
        $response->assertRedirect('/login');

        $response = $this->get('/candidate/dashboard');
        $response->assertRedirect('/login');

        $response = $this->get('/admin/dashboard');
        $response->assertRedirect('/login');
    }

    public function test_post_registration_redirections()
    {
        // Test redirection après inscription électeur
        $region = Region::first();
        $voterData = [
            'name' => 'New Voter',
            'email' => 'new.voter@example.com',
            'password' => 'password123',
            'password_confirmation' => 'password123',
            'nin' => '1234567890123',
            'voter_card_number' => 'VOTER123',
            'region_id' => $region->id,
        ];

        $response = $this->post('/register/voter', $voterData);
        $response->assertRedirect('/voter/dashboard');
        
        // Déconnexion pour tester le prochain cas
        $this->post('/logout');

        // Test redirection après inscription candidat
        $candidateData = [
            'name' => 'New Candidate',
            'email' => 'new.candidate@example.com',
            'password' => 'password123',
            'password_confirmation' => 'password123',
            'nin' => '9876543210123',
            'birth_date' => '1980-01-01',
            'party_name' => 'New Party',
            'party_position' => 'Leader'
        ];

        $response = $this->post('/register/candidate', $candidateData);
        $response->assertRedirect('/candidate/dashboard');
    }

    public function test_post_login_redirections()
    {
        // Créer les utilisateurs
        $voter = User::factory()->create([
            'email' => 'voter.redirect@example.com',
            'password' => bcrypt('password123'),
            'role' => 'voter'
        ]);

        $candidate = User::factory()->create([
            'email' => 'candidate.redirect@example.com',
            'password' => bcrypt('password123'),
            'role' => 'candidate'
        ]);

        $admin = User::factory()->create([
            'email' => 'admin.redirect@example.com',
            'password' => bcrypt('password123'),
            'role' => 'admin'
        ]);

        // Test redirection électeur après login
        $response = $this->post('/login', [
            'email' => 'voter.redirect@example.com',
            'password' => 'password123'
        ]);
        $response->assertRedirect('/voter/dashboard');
        $this->post('/logout');

        // Test redirection candidat après login
        $response = $this->post('/login', [
            'email' => 'candidate.redirect@example.com',
            'password' => 'password123'
        ]);
        $response->assertRedirect('/candidate/dashboard');
        $this->post('/logout');

        // Test redirection admin après login
        $response = $this->post('/login', [
            'email' => 'admin.redirect@example.com',
            'password' => 'password123'
        ]);
        $response->assertRedirect('/admin/dashboard');
    }

    public function test_middleware_redirections()
    {
        // Test du middleware auth
        $response = $this->get('/home');
        $response->assertRedirect('/login');

        // Test du middleware guest
        $user = User::factory()->create();
        $this->actingAs($user);
        
        $response = $this->get('/login');
        $response->assertRedirect('/home');
        
        $response = $this->get('/register');
        $response->assertRedirect('/home');
    }

    public function test_logout_redirection()
    {
        $user = User::factory()->create();
        $this->actingAs($user);

        $response = $this->post('/logout');
        $response->assertRedirect('/');
        $this->assertGuest();
    }
}
