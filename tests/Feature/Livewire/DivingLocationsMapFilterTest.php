<?php

namespace Tests\Feature\Livewire;

use App\Livewire\Public\DivingLocationsMap;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Livewire\Livewire;
use Tests\TestCase;

class DivingLocationsMapFilterTest extends TestCase
{
    use RefreshDatabase;

    public function test_nested_water_type_filter_does_not_break_where_in(): void
    {
        // Reproduces the crash: Livewire hydrated selectedWaterTypes with a NESTED
        // array (malformed query string from some clients), and
        // whereIn('water_type', [[...]]) threw "Nested arrays may not be passed to
        // whereIn method." Setting the filter triggers updated() -> mapLocations,
        // which is exactly the /livewire/update path that crashed.
        Livewire::test(DivingLocationsMap::class)
            ->set('selectedWaterTypes', [['Salt Water', 'Fresh Water']])
            ->assertOk()
            ->assertSet('selectedWaterTypes', ['Salt Water', 'Fresh Water']);
    }

    public function test_nested_level_and_dive_type_filters_are_flattened(): void
    {
        Livewire::test(DivingLocationsMap::class)
            ->set('selectedLevels', [['Beginner'], ['Advanced']])
            ->set('selectedDiveTypes', [['Cave']])
            ->assertOk()
            ->assertSet('selectedLevels', ['Beginner', 'Advanced'])
            ->assertSet('selectedDiveTypes', ['Cave']);
    }
}
