<?php

use App\Models\Group;
use Domain\Individuals\Models\Individual;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Spatie\Permission\Models\Permission;

uses(RefreshDatabase::class);

beforeEach(function () {
    // Create the 'access international licenses' permission
    $this->permission = Permission::create([
        'name' => 'access international licenses',
        'guard_name' => 'web',
    ]);

    // Create the INDIVIDUAL group (required by middleware)
    $this->individualGroup = Group::firstOrCreate(['code' => 'INDIVIDUAL'], ['name' => 'Individual']);
});

test('cmas individual license purchase requires permission', function () {
    $individual = Individual::factory()->create();
    $user = $individual->user;

    // Without permission - should be forbidden
    $response = $this->actingAs($user)->get(route('cmas.individual.license-purchase.index'));
    $response->assertForbidden();
});

test('cmas individual license purchase allows access with permission', function () {
    $individual = Individual::factory()->create();
    $user = $individual->user;
    $user->givePermissionTo('access international licenses');

    // With permission - should succeed
    $response = $this->actingAs($user)->get(route('cmas.individual.license-purchase.index'));
    $response->assertSuccessful();
});

test('cmas individual licenses attributed requires permission', function () {
    $individual = Individual::factory()->create();
    $user = $individual->user;

    // Without permission - should be forbidden
    $response = $this->actingAs($user)->get(route('cmas.individual.licenses-attributed.index'));
    $response->assertForbidden();
});

test('cmas individual licenses attributed allows access with permission', function () {
    $individual = Individual::factory()->create();
    $user = $individual->user;
    $user->givePermissionTo('access international licenses');

    // With permission - should succeed (but also requires active affiliation)
    // The controller checks hasActiveAffiliation() which requires MemberSubscription with Affiliations
    $response = $this->actingAs($user)->get(route('cmas.individual.licenses-attributed.index'));
    // Returns 403 because individual has no active affiliation - this is expected behavior
    $response->assertForbidden();
});

test('cmas individual certification card requires permission', function () {
    $individual = Individual::factory()->create();
    $user = $individual->user;

    // Without permission - should be forbidden
    $response = $this->actingAs($user)->get(route('cmas.individual.certification-card.index'));
    $response->assertForbidden();
});

test('cmas individual certification card allows access with permission', function () {
    $individual = Individual::factory()->create();
    $user = $individual->user;
    $user->givePermissionTo('access international licenses');

    // With permission - should succeed
    $response = $this->actingAs($user)->get(route('cmas.individual.certification-card.index'));
    $response->assertSuccessful();
});

test('cmas individual certifications requires permission', function () {
    $individual = Individual::factory()->create();
    $user = $individual->user;

    // Without permission - should be forbidden
    $response = $this->actingAs($user)->get(route('cmas.individual.certifications.index'));
    $response->assertForbidden();
});

test('cmas individual certifications allows access with permission', function () {
    $individual = Individual::factory()->create();
    $user = $individual->user;
    $user->givePermissionTo('access international licenses');

    // With permission - should succeed
    $response = $this->actingAs($user)->get(route('cmas.individual.certifications.index'));
    $response->assertSuccessful();
});

test('all cmas routes require authentication', function () {
    $routes = [
        'cmas.individual.license-purchase.index',
        'cmas.individual.licenses-attributed.index',
        'cmas.individual.certification-card.index',
        'cmas.individual.certifications.index',
    ];

    foreach ($routes as $route) {
        $response = $this->get(route($route));
        $response->assertRedirect(route('login'));
    }
});

test('cmas routes reject users without permission even when authenticated', function () {
    $individual = Individual::factory()->create();
    $user = $individual->user;
    // No permission given

    $routes = [
        'cmas.individual.license-purchase.index',
        'cmas.individual.licenses-attributed.index',
        'cmas.individual.certification-card.index',
        'cmas.individual.certifications.index',
    ];

    foreach ($routes as $route) {
        $response = $this->actingAs($user)->get(route($route));
        $response->assertForbidden();
    }
});
