<?php

namespace Tests\Feature;

use App\Models\User;
use App\Models\Region;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use Spatie\Activitylog\Models\Activity;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class LoginActivityTest extends TestCase
{
    use RefreshDatabase;

    protected $voter;
    protected $region;

    protected function setUp(): void
    {
        parent::setUp();

        // Vérifier la connexion à la base de données
        try {
            DB::connection()->getPdo();
            echo "\nConnecté à la base de données: " . DB::connection()->getDatabaseName();

            // Vérifier si la table activity_log existe
            if (!Schema::hasTable('activity_log')) {
                echo "\nLa table activity_log n'existe pas!";
                $this->markTestSkipped("La table activity_log n'existe pas.");
            }

            // Vérifier la structure de la table
            $columns = Schema::getColumnListing('activity_log');
            echo "\nColonnes de la table activity_log: " . implode(', ', $columns);
        } catch (\Exception $e) {
            echo "\nErreur de connexion à la base de données: " . $e->getMessage();
            $this->markTestSkipped("Impossible de se connecter à la base de données.");
        }
        
        // Seed la base de données avec les régions
        $this->seed(\Database\Seeders\RegionSeeder::class);
        $this->region = Region::first();

        if (!$this->region) {
            echo "\nAucune région n'a été créée!";
            $this->markTestSkipped("Aucune région disponible.");
        }

        // Créer l'utilisateur de test
        try {
            $this->voter = User::factory()->create([
                'role' => 'voter',
                'email' => 'test.voter@example.com',
                'password' => bcrypt('password123'),
                'region_id' => $this->region->id,
                'voter_card_number' => 'VOTER123'
            ]);

            echo "\nUtilisateur de test créé avec l'ID: " . $this->voter->id;
        } catch (\Exception $e) {
            echo "\nErreur lors de la création de l'utilisateur: " . $e->getMessage();
            $this->markTestSkipped("Impossible de créer l'utilisateur de test.");
        }
    }

    public function test_login_activity_is_logged()
    {
        // 1. Vérifier qu'il n'y a pas d'activité avant la connexion
        $initialCount = Activity::count();
        echo "\nNombre d'activités initial: " . $initialCount;

        // 2. Tentative de connexion
        $response = $this->post('/login', [
            'email' => 'test.voter@example.com',
            'password' => 'password123'
        ]);

        // 3. Vérifier que la connexion a réussi
        $response->assertRedirect();
        
        if (!$this->isAuthenticated()) {
            echo "\nL'authentification a échoué!";
            $this->fail("L'authentification a échoué.");
        }

        // 4. Attendre un peu pour s'assurer que l'activité est enregistrée
        sleep(1);

        // 5. Récupérer et afficher toutes les activités pour le débogage
        $activities = Activity::all();
        echo "\nNombre d'activités enregistrées: " . $activities->count();
        
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

        // 6. Vérifier l'enregistrement de l'activité
        $this->assertDatabaseHas('activity_log', [
            'description' => 'login',
            'subject_type' => User::class,
            'subject_id' => $this->voter->id
        ], 'Devrait avoir enregistré une activité de connexion');
    }
}
