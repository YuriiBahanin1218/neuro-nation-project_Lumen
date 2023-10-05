<?php

use App\Models\History;
use App\Models\Session;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class SessionHistoryControllerTest extends TestCase
{
    use RefreshDatabase;

    // Initialize the database before running the tests
    public function setUp(): void
    {
        parent::setUp();

        // Seed the database with sample data (you can adjust this based on your needs)
        $this->seed();
    }

    // Test retrieving session history for an existing user
    public function testGetHistoryForExistingUser()
    {
        $user = User::factory()->create();

        $response = $this->get("/api/session-history/{$user->id}");

        $response->assertStatus(200)
            ->assertJsonStructure(['history', 'lastSessionCategories']);
    }

    // Test retrieving session history for a non-existing user
    public function testGetHistoryForNonExistingUser()
    {
        $response = $this->get('/api/session-history/9999'); // Use a non-existing user ID

        $response->assertStatus(404)
            ->assertJson(['error' => 'User not found']);
    }

    // Test retrieving session history when user has no history
    public function testGetHistoryForUserWithNoHistory()
    {
        $user = User::factory()->create();

        $response = $this->get("/api/session-history/{$user->id}");

        $response->assertStatus(200)
            ->assertJsonStructure(['history', 'lastSessionCategories']);
        $response->assertJson(['history' => []]); // User has no history, so it should be an empty array
    }

    // Test retrieving session history with last session categories
    public function testGetHistoryWithLastSessionCategories()
    {
        $user = User::factory()->create();

        // Create a session history for the user
        $sessions = Session::factory()->count(3)->create(['user_id' => $user->id]);

        // Create some history records with scores
        foreach ($sessions as $session) {
            History::factory()->create(['user_id' => $user->id, 'session_id' => $session->id]);
        }

        // Create a session with categories for the last session
        $lastSession = $sessions->last();
        $lastSession->courses()->attach([1, 2]); // Attach category IDs to the session

        $response = $this->get("/api/session-history/{$user->id}");

        $response->assertStatus(200)
            ->assertJsonStructure(['history', 'lastSessionCategories']);
        $response->assertJson(['lastSessionCategories' => [1, 2]]);
    }
}
