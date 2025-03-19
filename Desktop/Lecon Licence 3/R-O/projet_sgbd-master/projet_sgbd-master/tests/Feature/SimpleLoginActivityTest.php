<?php

namespace Tests\Feature;

use App\Models\User;
use App\Models\Region;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use Spatie\Activitylog\Models\Activity;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class SimpleLoginActivityTest extends TestCase
{
    use RefreshDatabase;

    public function test_login_activity_is_logged()
    {
        // 1. Créer une région
        $region = Region::create([
            'name' => 'Test Region',
            'code' => 'TEST'
        ]);

        // 2. Créer un utilisateur
        $user = User::create([
            'role' => 'voter',
            'email' => 'test.voter@example.com',
            'password' => bcrypt('password123'),
            'region_id' => $region->id,
            'voter_card_number' => 'VOTER123'
        ]);

        echo "\nUtilisateur créé avec l'ID: " . $user->id;

        // 3. Vérifier le nombre d'activités initial
        $initialCount = Activity::count();
        echo "\nNombre d'activités initial: " . $initialCount;

        // 4. Tentative de connexion
        $response = $this->post('/login', [
            'email' => 'test.voter@example.com',
            'password' => 'password123'
        ]);

        // 5. Attendre un peu pour s'assurer que l'activité est enregistrée
        sleep(1);

        // 6. Vérifier les activités
        $activities = Activity::all();
        echo "\nNombre d'activités après connexion: " . $activities->count();
        
        foreach ($activities as $activity) {
            echo "\nActivité: " . json_encode([
                'description' => $activity->description,
                'subject_type' => $activity->subject_type,
                'subject_id' => $activity->subject_id,
                'causer_type' => $activity->causer_type,
                'causer_id' => $activity->causer_id,
                'properties' => $activity->properties
            ], JSON_PRETTY_PRINT);
        }

        // 7. Vérifier l'enregistrement de l'activité
        $this->assertDatabaseHas('activity_log', [
            'description' => 'login',
            'subject_type' => User::class,
            'subject_id' => $user->id
        ]);
    }
}
