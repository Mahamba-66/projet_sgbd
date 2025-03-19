<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\User;
use App\Models\Region;
use App\Models\EligibleVoter;
use Illuminate\Foundation\Testing\RefreshDatabase;

class VoterRegistrationTest extends TestCase
{
    use RefreshDatabase;

    protected $region;

    protected function setUp(): void
    {
        parent::setUp();
        
        // Utiliser une région existante ou en créer une si nécessaire
        $this->region = Region::first() ?? Region::create([
            'name' => 'Dakar',
            'code' => 'DK'
        ]);
    }

    /** @test */
    public function un_electeur_eligible_peut_sinscrire()
    {
        // Créer un électeur éligible
        $eligibleVoter = EligibleVoter::create([
            'first_name' => 'Amadou',
            'last_name' => 'Diallo',
            'card_number' => 'SN2025001',
            'is_registered' => false
        ]);

        // Données d'inscription
        $registrationData = [
            'name' => 'Amadou Diallo',
            'email' => 'amadou@example.com',
            'password' => 'password123',
            'password_confirmation' => 'password123',
            'region_id' => $this->region->id,
            'card_number' => 'SN2025001'
        ];

        // Tenter l'inscription
        $response = $this->post('/register/voter', $registrationData);

        // Vérifier que l'inscription a réussi
        $response->assertRedirect('/voter/dashboard');
        $this->assertDatabaseHas('users', [
            'email' => 'amadou@example.com',
            'role' => 'voter'
        ]);

        // Vérifier que l'électeur est marqué comme inscrit
        $this->assertTrue($eligibleVoter->fresh()->is_registered);
    }

    /** @test */
    public function un_electeur_non_eligible_ne_peut_pas_sinscrire()
    {
        // Données d'inscription avec un numéro de carte non éligible
        $registrationData = [
            'name' => 'Non Eligible',
            'email' => 'non.eligible@example.com',
            'password' => 'password123',
            'password_confirmation' => 'password123',
            'region_id' => $this->region->id,
            'card_number' => 'SN2025999'
        ];

        // Tenter l'inscription
        $response = $this->post('/register/voter', $registrationData);

        // Vérifier que l'inscription a échoué
        $response->assertSessionHasErrors('card_number');
        $this->assertDatabaseMissing('users', [
            'email' => 'non.eligible@example.com'
        ]);
    }

    /** @test */
    public function un_electeur_deja_inscrit_ne_peut_pas_se_reinscrire()
    {
        // Créer un électeur éligible déjà inscrit
        $eligibleVoter = EligibleVoter::create([
            'first_name' => 'Fatou',
            'last_name' => 'Sow',
            'card_number' => 'SN2025002',
            'is_registered' => true
        ]);

        // Données d'inscription
        $registrationData = [
            'name' => 'Fatou Sow',
            'email' => 'fatou@example.com',
            'password' => 'password123',
            'password_confirmation' => 'password123',
            'region_id' => $this->region->id,
            'card_number' => 'SN2025002'
        ];

        // Tenter l'inscription
        $response = $this->post('/register/voter', $registrationData);

        // Vérifier que l'inscription a échoué
        $response->assertSessionHasErrors('card_number');
        $this->assertDatabaseMissing('users', [
            'email' => 'fatou@example.com'
        ]);
    }
}
