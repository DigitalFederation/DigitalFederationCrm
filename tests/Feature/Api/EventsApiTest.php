<?php

use Domain\EvtEvents\Models\Competition;
use Domain\EvtEvents\Models\CompetitionType;
use Domain\EvtEvents\Models\Event;
use Domain\EvtEvents\Models\Sport;
use Domain\EvtEvents\States\ActiveEventState;
use Illuminate\Foundation\Testing\RefreshDatabase;

use function Pest\Laravel\get;

uses(RefreshDatabase::class);
beforeEach(function () {
    // Reset the database before each test
    $this->artisan('migrate:fresh');

    // Create an API token for testing
    $this->apiToken = \App\Models\ApiToken::create([
        'name' => 'Test API Token',
        'token' => \App\Models\ApiToken::hashToken('test-api-token'),
        'permissions' => json_encode(['api.events.competitions']),
    ]);

    // Set up the test environment to use Bearer token authentication
    $this->withHeaders([
        'Authorization' => 'Bearer test-api-token',
    ]);
});

it('returns empty collection when no competition events exist', function () {
    get('/api/events/competitions?start_date=2023-01-01&end_date=2023-12-31')
        ->assertOk()
        ->assertJsonCount(0, 'data');
});

it('returns competition events within date range', function () {
    // Create a sport for the competition
    $sport = Sport::factory()->create();

    // Create events with competitions
    $event1 = Event::factory()->create([
        'name' => 'Event Inside Range',
        'event_category' => 'competition',
        'is_visible' => true,
        'start_date' => '2023-06-01',
        'end_date' => '2023-06-10',
        'start_registration' => '2023-05-01',
        'end_registration' => '2023-05-25',
        'status_class' => ActiveEventState::class,
    ]);

    $competition1 = Competition::factory()->create([
        'event_id' => $event1->id,
        'sport_id' => $sport->id,
        'full_name' => 'Competition Inside Range',
    ]);

    CompetitionType::factory()->create([
        'competition_id' => $competition1->id,
        'competition_type' => 'world_championship',
    ]);

    // Event outside the date range
    $event2 = Event::factory()->create([
        'name' => 'Event Outside Range',
        'event_category' => 'competition',
        'is_visible' => true,
        'start_date' => '2024-01-01',
        'end_date' => '2024-01-10',
        'status_class' => ActiveEventState::class,
    ]);

    $competition2 = Competition::factory()->create([
        'event_id' => $event2->id,
        'sport_id' => $sport->id,
        'full_name' => 'Competition Outside Range',
    ]);

    CompetitionType::factory()->create([
        'competition_id' => $competition2->id,
        'competition_type' => 'continental_championship',
    ]);

    // Test endpoint with date range
    get('/api/events/competitions?start_date=2023-01-01&end_date=2023-12-31')
        ->assertOk()
        ->assertJsonCount(1, 'data')
        ->assertJsonPath('data.0.name', 'Event Inside Range')
        ->assertJsonPath('data.0.competition.full_name', 'Competition Inside Range');
});

it('returns events with specific competition type', function () {
    // Create a sport for the competition
    $sport = Sport::factory()->create();

    // Create two events with different competition types
    $event1 = Event::factory()->create([
        'name' => 'World Championship Event',
        'event_category' => 'competition',
        'is_visible' => true,
        'start_date' => '2023-06-01',
        'end_date' => '2023-06-10',
        'status_class' => ActiveEventState::class,
    ]);

    $competition1 = Competition::factory()->create([
        'event_id' => $event1->id,
        'sport_id' => $sport->id,
    ]);

    CompetitionType::factory()->create([
        'competition_id' => $competition1->id,
        'competition_type' => 'world_championship',
    ]);

    $event2 = Event::factory()->create([
        'name' => 'Continental Championship Event',
        'event_category' => 'competition',
        'is_visible' => true,
        'start_date' => '2023-07-01',
        'end_date' => '2023-07-10',
        'status_class' => ActiveEventState::class,
    ]);

    // Test filtering by competition type
    $response = $this->get('/api/events/competitions?competition_type=world_championship');

    $response->assertOk();
    $response->assertJsonCount(1, 'data');
    $response->assertJsonPath('data.0.name', 'World Championship Event');
});
