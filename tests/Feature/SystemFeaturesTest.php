<?php

namespace Tests\Feature;

use App\Models\User;
use App\Models\Region;
use App\Models\Sponsorship;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use Carbon\Carbon;

class SystemFeaturesTest extends TestCase
{
    use RefreshDatabase;
    use WithFaker;

    protected $voter;
    protected $candidate;
    protected $admin;
    protected $region;

    protected function setUp(): void
    {
        parent::setUp();
        
        // Seed la base de données avec les régions
        $this->seed(\Database\Seeders\RegionSeeder::class);
        $this->region = Region::first();

        // Créer les utilisateurs de test
        $this->voter = User::factory()->create([
            'role' => 'voter',
            'email' => 'test.voter@example.com',
            'region_id' => $this->region->id,
            'voter_card_number' => 'VOTER123'
        ]);

        $this->candidate = User::factory()->create([
            'role' => 'candidate',
            'email' => 'test.candidate@example.com',
            'party_name' => 'Test Party',
            'party_position' => 'Leader',
            'birth_date' => '1980-01-01'
        ]);

        $this->admin = User::factory()->create([
            'role' => 'admin',
            'email' => 'test.admin@example.com'
        ]);
    }

    /**
     * Tests des fonctionnalités de l'électeur
     */
    public function test_voter_features()
    {
        $this->actingAs($this->voter);

        // Test accès au dashboard
        $response = $this->get('/voter/dashboard');
        if (!$response->isOk()) {
            $this->fail("L'électeur ne peut pas accéder à son dashboard. Code HTTP: " . $response->status());
        }

        // Test affichage du profil
        $response = $this->get('/voter/profile');
        if (!$response->isOk()) {
            $this->fail("L'électeur ne peut pas accéder à son profil. Code HTTP: " . $response->status());
        }

        // Test mise à jour du profil
        $response = $this->put('/voter/profile', [
            'name' => 'Updated Voter Name',
            'email' => 'updated.voter@example.com'
        ]);
        if (!$response->isRedirect()) {
            $this->fail("La mise à jour du profil a échoué. Erreurs: " . 
                json_encode(session('errors')->all()));
        }

        // Test affichage liste des candidats
        $response = $this->get('/voter/candidates');
        if (!$response->isOk()) {
            $this->fail("L'électeur ne peut pas voir la liste des candidats. Code HTTP: " . $response->status());
        }

        // Test parrainage d'un candidat
        $response = $this->post('/voter/sponsor/' . $this->candidate->id);
        if (!$response->isRedirect()) {
            $this->fail("Le parrainage a échoué. Erreurs: " . 
                json_encode(session('errors')->all()));
        }

        // Test double parrainage (doit échouer)
        $response = $this->post('/voter/sponsor/' . $this->candidate->id);
        if ($response->isRedirect() && !session('errors')) {
            $this->fail("Le système permet le double parrainage, ce qui ne devrait pas être possible");
        }
    }

    /**
     * Tests des fonctionnalités du candidat
     */
    public function test_candidate_features()
    {
        $this->actingAs($this->candidate);

        // Test accès au dashboard
        $response = $this->get('/candidate/dashboard');
        if (!$response->isOk()) {
            $this->fail("Le candidat ne peut pas accéder à son dashboard. Code HTTP: " . $response->status());
        }

        // Test affichage du profil
        $response = $this->get('/candidate/profile');
        if (!$response->isOk()) {
            $this->fail("Le candidat ne peut pas accéder à son profil. Code HTTP: " . $response->status());
        }

        // Test mise à jour du profil
        $response = $this->put('/candidate/profile', [
            'name' => 'Updated Candidate Name',
            'email' => 'updated.candidate@example.com',
            'party_name' => 'Updated Party',
            'party_position' => 'Updated Position'
        ]);
        if (!$response->isRedirect()) {
            $this->fail("La mise à jour du profil a échoué. Erreurs: " . 
                json_encode(session('errors')->all()));
        }

        // Test affichage des statistiques de parrainage
        $response = $this->get('/candidate/sponsorship-stats');
        if (!$response->isOk()) {
            $this->fail("Le candidat ne peut pas voir ses statistiques de parrainage. Code HTTP: " . $response->status());
        }

        // Test téléchargement de la fiche de parrainage
        $response = $this->get('/candidate/sponsorship-form');
        if (!$response->isOk()) {
            $this->fail("Le candidat ne peut pas télécharger sa fiche de parrainage. Code HTTP: " . $response->status());
        }
    }

    /**
     * Tests des fonctionnalités de l'administrateur
     */
    public function test_admin_features()
    {
        $this->actingAs($this->admin);

        // Test accès au dashboard
        $response = $this->get('/admin/dashboard');
        if (!$response->isOk()) {
            $this->fail("L'administrateur ne peut pas accéder à son dashboard. Code HTTP: " . $response->status());
        }

        // Test gestion des électeurs
        $response = $this->get('/admin/voters');
        if (!$response->isOk()) {
            $this->fail("L'administrateur ne peut pas accéder à la gestion des électeurs. Code HTTP: " . $response->status());
        }

        // Test création d'un électeur
        $response = $this->post('/admin/voters', [
            'name' => 'New Voter',
            'email' => 'new.voter@example.com',
            'password' => 'password123',
            'password_confirmation' => 'password123',
            'nin' => '1234567890123',
            'voter_card_number' => 'NEWVOTER123',
            'region_id' => $this->region->id
        ]);
        if (!$response->isRedirect()) {
            $this->fail("La création d'un électeur a échoué. Erreurs: " . 
                json_encode(session('errors')->all()));
        }

        // Test gestion des candidats
        $response = $this->get('/admin/candidates');
        if (!$response->isOk()) {
            $this->fail("L'administrateur ne peut pas accéder à la gestion des candidats. Code HTTP: " . $response->status());
        }

        // Test validation d'un candidat
        $response = $this->post('/admin/candidates/' . $this->candidate->id . '/validate');
        if (!$response->isRedirect()) {
            $this->fail("La validation du candidat a échoué. Erreurs: " . 
                json_encode(session('errors')->all()));
        }

        // Test génération de rapports
        $response = $this->get('/admin/reports/sponsorships');
        if (!$response->isOk()) {
            $this->fail("L'administrateur ne peut pas générer de rapports. Code HTTP: " . $response->status());
        }
    }

    /**
     * Tests des validations et contraintes
     */
    public function test_system_validations()
    {
        // Test validation âge minimum candidat (35 ans)
        $response = $this->post('/register/candidate', [
            'name' => 'Young Candidate',
            'email' => 'young.candidate@example.com',
            'password' => 'password123',
            'password_confirmation' => 'password123',
            'nin' => '9876543210123',
            'birth_date' => Carbon::now()->subYears(30)->format('Y-m-d'),
            'party_name' => 'Young Party',
            'party_position' => 'Leader'
        ]);
        if (!$response->isRedirect() || !session('errors')) {
            $this->fail("Le système permet l'inscription d'un candidat de moins de 35 ans");
        }

        // Test unicité du numéro de carte d'électeur
        $response = $this->post('/register/voter', [
            'name' => 'Duplicate Voter',
            'email' => 'duplicate.voter@example.com',
            'password' => 'password123',
            'password_confirmation' => 'password123',
            'nin' => '5555555555555',
            'voter_card_number' => 'VOTER123', // Déjà utilisé
            'region_id' => $this->region->id
        ]);
        if (!$response->isRedirect() || !session('errors')) {
            $this->fail("Le système permet l'utilisation d'un numéro de carte d'électeur en double");
        }

        // Test limite de parrainages par région
        $this->actingAs($this->admin);
        $maxVoters = 10;
        for ($i = 0; $i < $maxVoters; $i++) {
            User::factory()->create([
                'role' => 'voter',
                'region_id' => $this->region->id,
                'voter_card_number' => 'VOTER' . ($i + 1000)
            ]);
        }

        $this->actingAs($this->voter);
        $response = $this->post('/voter/sponsor/' . $this->candidate->id);
        if (!$response->isRedirect() || !session('errors')) {
            $this->fail("Le système ne vérifie pas correctement les limites de parrainage par région");
        }
    }

    /**
     * Tests des fonctionnalités de sécurité
     */
    public function test_security_features()
    {
        // Test protection CSRF
        $response = $this->post('/login', [
            'email' => 'test.voter@example.com',
            'password' => 'password123'
        ]);
        if ($response->isOk()) {
            $this->fail("Le système n'applique pas la protection CSRF");
        }

        // Test verrouillage de compte après tentatives échouées
        for ($i = 0; $i < 6; $i++) {
            $this->post('/login', [
                '_token' => csrf_token(),
                'email' => 'test.voter@example.com',
                'password' => 'wrong_password'
            ]);
        }
        
        $response = $this->post('/login', [
            '_token' => csrf_token(),
            'email' => 'test.voter@example.com',
            'password' => 'password123'
        ]);
        if (!$response->isRedirect() || !session('errors')) {
            $this->fail("Le système ne verrouille pas les comptes après plusieurs tentatives échouées");
        }

        // Test expiration de session
        $this->actingAs($this->voter);
        $this->travel(config('session.lifetime') + 1)->minutes();
        
        $response = $this->get('/voter/dashboard');
        if ($response->isOk()) {
            $this->fail("Le système ne gère pas correctement l'expiration des sessions");
        }
    }

    /**
     * Tests des fonctionnalités de journalisation
     */
    public function test_logging_features()
    {
        // Test journalisation des connexions
        $response = $this->post('/login', [
            'email' => 'test.voter@example.com',
            'password' => 'password123'
        ]);

        $this->assertDatabaseHas('activity_log', [
            'description' => 'login',
            'subject_type' => User::class,
            'subject_id' => $this->voter->id
        ]);

        // Test journalisation des parrainages
        $this->actingAs($this->voter);
        $response = $this->post('/voter/sponsor/' . $this->candidate->id);

        $this->assertDatabaseHas('activity_log', [
            'description' => 'sponsorship_created',
            'subject_type' => Sponsorship::class
        ]);

        // Test journalisation des modifications de profil
        $this->put('/voter/profile', [
            'name' => 'Updated Name',
            'email' => 'updated.email@example.com'
        ]);

        $this->assertDatabaseHas('activity_log', [
            'description' => 'profile_updated',
            'subject_type' => User::class,
            'subject_id' => $this->voter->id
        ]);
    }
}
