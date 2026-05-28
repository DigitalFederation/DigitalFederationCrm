<?php

use App\Models\Country;
use App\Models\Group;
use Domain\Entities\Models\Entity;
use Domain\Federations\Models\Federation;
use Domain\Individuals\Models\Individual;

use function Pest\Laravel\artisan;

beforeEach(function () {
    artisan('db:seed --class=UserGroupSeeder');
});

it('can store a diving location', function (string $role) {
    $data = [
        'name' => fake()->name(),
        'country_id' => Country::factory()->create()->id,
        'region' => fake()->name,
        'native_name' => fake()->name,
    ];

    $user = \App\Models\User::factory()->create(['group_id' => Group::select('id')->where('code', strtoupper($role))->first()->id]);
    $this->actingAs($user);

    switch ($role) {
        case 'individual':
            $user->individuals()->create(Individual::factory()->make()->toArray());
            break;
        case 'entity':
            $user->entities()->create(Entity::factory()->make()->toArray());
            break;
        case 'federation':
            $federation = Federation::factory()->make()->toArray();
            $federation = $user->federations()->create($federation);
            // Create an active membership for the federation
            $federation->memberships()->create([
                'status_class' => \Domain\Memberships\States\ActiveMembershipState::class,
                'year' => now()->year,
            ]);
            break;
    }

    $response = $this->post(route($role . '.diving-location.store'), $data);
    $response->assertStatus(302);
    $response->assertSessionHas('success', __('diving_location.created_successfully'));
    $this->assertDatabaseHas('diving_location', $data);
})->with(['federation', 'entity', 'individual']);
