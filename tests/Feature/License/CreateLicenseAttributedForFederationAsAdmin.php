<?php

use App\Models\Group;
use Domain\Documents\Models\Document;
use Domain\Federations\Models\Federation;
use Domain\Individuals\Models\Individual;
use Domain\Licenses\Models\License;
use Domain\Licenses\Models\LicenseAttributed;

use function Pest\Laravel\artisan;

beforeEach(function () {
    artisan('db:seed --class=RoleAndPermissionSeeder');
    artisan('db:seed --class=UserGroupSeeder');
    artisan('db:seed --class=CommitteeSeeder');
    artisan('db:seed --class=DocumentTypeSeeder');

});

it('can store a individual license attributed', function () {

    $user = \App\Models\User::factory()->create([
        'group_id' => Group::select('id')->where('code', 'ADMIN')->first()->id,
    ]);

    $individual = Individual::factory()->create();
    $federation = Federation::factory()->create(['is_local' => false]);
    $federation->individuals()->attach($individual);

    $user->assignRole('admin');

    // Create a license with unit_value as 100
    $license = License::factory()->create([
        'unit_value' => 50,
        'unit_value_individual' => 100,
        'tax_value' => 0,
        'tax_percentage' => 0,
        'requester_model' => 'All',
    ]);

    $data = [
        'license_id' => $license->id,
        'federation_id' => $federation->id,
        'license_type_name' => 'individual',
        'individual' => [$individual->id],
        'requester_model_type' => Federation::class,
        'notes' => 'Some notes about the license',
    ];

    $this->actingAs($user)
        ->post(route('admin.license-attributed.store'), $data);

    $licenseAttributed = LicenseAttributed::latest()->first();

    $this->assertDatabaseHas('license_attributed', [
        'license_id' => $license->id,
        'model_id' => $individual->id,
        'model_type' => 'individual',
        'holder_name' => $individual->name.' '.$individual->surname,
        'requester_model_type' => Federation::class,
        'notes' => 'Some notes about the license',
    ]);

    $expectedTotalPrice = $license->unit_value;

    $createdDocument = Document::with('details')->latest()->first();
    expect($createdDocument->total_value)->toEqual($expectedTotalPrice);

});

it('can request a license attributed to himself', function ($role) {

    $user = \App\Models\User::factory()->create([
        'group_id' => Group::where('code', 'INDIVIDUAL')->first()->id,
    ]);

    $federation = Federation::factory()->create();

    $license_individual = License::factory()->create([
        'unit_value' => 50,
        'unit_value_individual' => 100,
        'tax_value' => 23,
        'tax_percentage' => 23,
        'requester_model' => Individual::class,
    ]);

    $individual = Individual::factory()->create(['user_id' => $user->id]);
    $federation->individuals()->attach(Individual::first()->id);

    $this->actingAs($user);

    $request = [
        '_token' => csrf_token(),
        'license_id' => $license_individual->id,
        'individual' => [$individual->id],
        'license_type_name' => 'individual',
        'federation_id' => $federation->id,
        'requester_model_type' => 'individual',
    ];

    $response = $this->post(route('individual.license-attributed.store'), $request ?? null);

    $response->assertStatus(302);
    $response->assertSessionHas('success');

})->with(['individual']);
