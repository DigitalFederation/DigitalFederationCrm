<?php

use App\Models\ApiToken;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

it('validates plain tokens against stored sha256 hashes', function () {
    ApiToken::create([
        'name' => 'Competition API',
        'token' => ApiToken::hashToken('plain-text-token'),
        'permissions' => json_encode(['api.events.competitions']),
    ]);

    expect(ApiToken::validateToken('Competition API', 'plain-text-token'))->toBeTrue()
        ->and(ApiToken::validateToken('Competition API', 'wrong-token'))->toBeFalse()
        ->and(ApiToken::validateToken('Missing API', 'plain-text-token'))->toBeFalse();
});

it('rejects api requests without a bearer token', function () {
    $this->getJson('/api/events/competitions')
        ->assertUnauthorized()
        ->assertJson(['message' => 'No API token provided']);
});

it('rejects api requests with an unknown bearer token', function () {
    $this->withHeader('Authorization', 'Bearer unknown-token')
        ->getJson('/api/events/competitions')
        ->assertUnauthorized()
        ->assertJson(['message' => 'Unauthorized']);
});

it('rejects api requests when the token lacks the route permission', function () {
    ApiToken::create([
        'name' => 'Other API',
        'token' => ApiToken::hashToken('valid-token'),
        'permissions' => json_encode(['api.certifications.show']),
    ]);

    $this->withHeader('Authorization', 'Bearer valid-token')
        ->getJson('/api/events/competitions')
        ->assertForbidden()
        ->assertJson(['message' => 'Forbidden']);
});
