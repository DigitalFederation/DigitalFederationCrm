<?php

use App\Models\Group;
use Domain\DivingLogs\Models\DivingBuddy;
use Domain\Individuals\Models\Individual;

it('can store a diving buddy', function () {
    $group = Group::factory()->create(['code' => 'INDIVIDUAL', 'id' => 3]);
    // Create a fake authenticated user
    $user = \App\Models\User::factory()->create([
        'group_id' => $group->id,
    ]);
    $individual = Individual::factory()->create([
        'user_id' => $user->id,
    ]);
    $this->actingAs($user);

    // Generate fake data for the request
    $data = DivingBuddy::factory()->make()->toArray();

    // Send a POST request to the store route with the fake data
    $response = $this->post(route('individual.diving-buddy.store'), $data);

    // Assert that a new DivingBuddy was created in the database
    $this->assertDatabaseHas('diving_buddies', [
        'user_id' => $user->id,
        'name' => $data['name'],
    ]);
});
