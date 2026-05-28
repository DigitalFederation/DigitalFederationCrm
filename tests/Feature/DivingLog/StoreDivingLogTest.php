<?php

namespace Tests\Feature\DivingLog;

use App\Livewire\DivingLogForm;
use App\Models\Country;
use App\Models\Group;
use Domain\DivingLogs\Models\DivingLocation;
use Domain\DivingLogs\Models\DivingLog;
use Domain\Individuals\Models\Individual;
use Livewire\Livewire;

use function Pest\Laravel\artisan;

beforeEach(function () {
    artisan('db:seed --class=RoleAndPermissionSeeder');
    artisan('db:seed --class=UserGroupSeeder');

    $group = Group::factory()->create(['code' => 'INDIVIDUAL']);
    $this->country = Country::factory()->create();
    $this->user = \App\Models\User::factory()->create(['group_id' => $group->id]);
    $this->user->assignRole('individual-diver')->assignRole('individual-approved');
    $this->individual = Individual::factory()->create([
        'user_id' => $this->user->id,
        'country_id' => $this->country->id,
    ]);
    $this->actingAs($this->user);
    $this->location = DivingLocation::factory()->create();
});

it('can store and update a diving log as individual', function () {

    $time = '2024-02-15 10:30:00';
    // return country from individual
    $individual_country = $this->individual->country;

    $dive_type = 1;
    $divingLogData = [
        'individual_id' => $this->individual->id,
        'dive_type' => $dive_type,
        'category' => 'Recreational',
        'date_and_time' => $time,
        'diving_location_id' => $this->location->id,
        'location' => [
            'lat' => $individual_country->lat,
            'lng' => $individual_country->lng,
            'zoom' => 6,
            'country_id' => $individual_country->id,
        ],
        'is_first_dive' => 1,
        'dive_sequence_number' => 101,
    ];

    Livewire::test(DivingLogForm::class, [
        'individual' => $this->individual,
    ])
        ->set('divingLogArray', $divingLogData)->call('saveAsComplete')->assertHasNoErrors();

    $divingLog = DivingLog::where('individual_id', $this->individual->id)->where('date_and_time', $time)->first();

    $this->get(route('individual.diving-log.edit', $divingLog->id))->assertStatus(200);

    $text = fake()->realText();

    Livewire::test(DivingLogForm::class, [
        'individual' => $this->individual,
        'oldDivingLog' => $divingLog,
        'isEditMode' => true,
    ])
        ->set('divingLogArray', [
            'individual_id' => $this->individual->id,
            'dive_type' => $dive_type,
            'category' => 'Recreational',
            'date_and_time' => '2024-02-15 10:30:00',
            'diving_location_id' => $this->location->id,
            'environment_entry' => 'ShoreBeach',
            'environment_water_type' => 'SaltWater',
            'environment_current' => 'LightCurrent',
            'environment_surface' => 'Calm',
            'environment_water_temperature' => 15,
            'environment_water_temperature_unit' => 'Celsius',
            'environment_air_temperature' => 25,
            'environment_air_temperature_unit' => 'Celsius',
            'environment_water_visibility' => 10,
            'environment_water_visibility_unit' => 'Meter',
            'wildlife' => $text,
            'notes' => $text,
            'divingData' => [
                'diving_log_id' => $divingLog->id,
                'speciality_dive' => ['Open Water' => 1, 'Search' => 1],
                'duration_minutes' => 20,
                'depth' => 10,
                'depth_unit' => 'Meter',
                'nitrox_percentage' => 50,
                'tank_type' => 'Steel',
                'tank_volume' => 1,
                'tank_volume_unit' => 'Liter',
                'start_pressure' => 20,
                'start_pressure_unit' => 'Bar',
                'end_pressure' => 20,
                'end_pressure_unit' => 'Bar',
                'average_depth' => 5,
                'average_depth_unit' => 'Meter',
                'equipment_suit' => $text,
                'equipment_mask' => $text,
                'equipment_fins' => $text,
                'equipment_bcd_wing_sidemount' => $text,
                'equipment_first_stage' => $text,
                'equipment_second_stage' => $text,
                'equipment_dive_computer' => $text,
                'equipment_lights' => $text,
                'equipment_other' => $text,
                'equipment_weight' => 10,
                'equipment_weight_unit' => 'Kg',
            ],
        ])
        ->call('saveAsComplete')
        ->assertHasNoErrors();

    $this->assertDatabaseHas('diving_log', [
        'individual_id' => $this->individual->id,
        'dive_type' => $dive_type,
        'category' => 'Recreational',
        'date_and_time' => '2024-02-15 10:30:00',
        'diving_location_id' => $this->location->id,
        'environment_entry' => 'ShoreBeach',
    ]);

    $this->assertDatabaseHas('individual_diving_log_sequence', [
        'individual_id' => $this->individual->id,
        'diving_log_id' => $divingLog->id,
        'dive_type' => $dive_type,
        'initial_log_number' => 101,
        'log_number' => 101,
    ]);

    $divingLogUpdated = DivingLog::with(['location', 'diving', 'extendedRange', 'freeDiving', 'rebreatherCCR', 'rebreatherSCR'])->find($divingLog->id);

    Livewire::test(DivingLogForm::class, [
        'individual' => $this->individual,
        'oldDivingLog' => $divingLogUpdated,
        'isEditMode' => true,
    ])
        ->assertSet('divingLogArray.individual_id', $this->individual->id)
        ->assertSet('divingLogArray.dive_type', $dive_type)
        ->assertSet('divingLogArray.category', 'Recreational')
        ->assertSet('divingLogArray.date_and_time', '2024-02-15 10:30:00')
        ->assertSet('divingLogArray.diving_location_id', $this->location->id)
        ->assertSet('divingLogArray.environment_entry', 'ShoreBeach')
        ->assertSet('divingLogArray.environment_water_type', 'SaltWater')
        ->assertSet('divingLogArray.environment_current', 'LightCurrent')
        ->assertSet('divingLogArray.environment_surface', 'Calm')
        ->assertSet('divingLogArray.environment_water_temperature', 15)
        ->assertSet('divingLogArray.environment_water_temperature_unit', 'C')
        ->assertSet('divingLogArray.environment_air_temperature', 25)
        ->assertSet('divingLogArray.environment_air_temperature_unit', 'C')
        ->assertSet('divingLogArray.environment_water_visibility', 10)
        ->assertSet('divingLogArray.environment_water_visibility_unit', 'm')
        ->assertSet('divingLogArray.wildlife', $text)->assertSet('divingLogArray.notes', $text);
    // ->call('goToStep', 3)
    // ->assertSet('divingLogArray.divingData.speciality_dive', ['Open Water' => 1, 'Search' => 1, 'Wreck Dive' => null]);
    /*
    'divingData'=>[
        "id" => 1
  "diving_log_id" => 1
  "entry" => null
  "speciality_dive" => {#9517
    +"Search": 1
    +"Open Water": 1
  }
  "duration_minutes" => 20
  "depth" => 10
  "depth_unit" => "Meter"
  "nitrox_percentage" => 50
  "tank_type" => "Steel"
  "tank_volume" => 1
  "tank_volume_unit" => "Liter"
  "start_pressure" => 20
  "start_pressure_unit" => "Bar"
  "end_pressure" => 20
  "end_pressure_unit" => "Bar"
  "average_depth" => 5
  "average_depth_unit" => "Meter"
  "equipment_suit" => "I've finished.' So they got settled down in a court of justice before, but she saw in my size; and a"
  "equipment_mask" => "I've finished.' So they got settled down in a court of justice before, but she saw in my size; and a"
  "equipment_fins" => "I've finished.' So they got settled down in a court of justice before, but she saw in my size; and a"
  "equipment_bcd_wing_sidemount" => "I've finished.' So they got settled down in a court of justice before, but she saw in my size; and a"
  "equipment_first_stage" => "I've finished.' So they got settled down in a court of justice before, but she saw in my size; and a"
  "equipment_second_stage" => "I've finished.' So they got settled down in a court of justice before, but she saw in my size; and a"
  "equipment_dive_computer" => "I've finished.' So they got settled down in a court of justice before, but she saw in my size; and a"
  "equipment_lights" => "I've finished.' So they got settled down in a court of justice before, but she saw in my size; and a"
  "equipment_other" => "I've finished.' So they got settled down in a court of justice before, but she saw in my size; and as it settled down again in a very humble tone, going down on their faces, and the cool fountains."
  "equipment_weight" => 10
  "equipment_weight_unit" => "Kg"
    ]
    ]);
    */
});
