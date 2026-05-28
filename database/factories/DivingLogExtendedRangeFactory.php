<?php

namespace Database\Factories;

use Domain\DivingLogs\Models\DivingLog;
use Domain\DivingLogs\Models\DivingLogExtendedRange;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<DivingLogExtendedRange>
 */
class DivingLogExtendedRangeFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'diving_log_id' => DivingLog::factory(),
            'total_runtime' => $this->faker->randomNumber(2),
            'total_deco_time' => $this->faker->randomNumber(2),
            'depth' => $this->faker->randomNumber(2),
            'depth_unit' => $this->faker->word,
            'configuration' => $this->faker->word,
            'sac_bottom_sac' => $this->faker->randomNumber(2),
            'sac_sac' => $this->faker->randomNumber(2),
            'sac_deco_sac' => $this->faker->randomNumber(2),
            'details_si_before' => $this->faker->word,
            'details_gf_set' => $this->faker->word,
            'details_gradient_factor_end' => $this->faker->word,
            'details_cns_start' => $this->faker->word,
            'details_cns_end' => $this->faker->word,
            'details_otu_start' => $this->faker->word,
            'details_otu_end' => $this->faker->word,
            'back_gas_tank_volume' => $this->faker->randomNumber(2),
            'back_gas_tank_volume_unit' => $this->faker->word,
            'back_gas_start_pressure' => $this->faker->randomNumber(2),
            'back_gas_start_pressure_unit' => $this->faker->word,
            'back_gas_end_pressure' => $this->faker->randomNumber(2),
            'back_gas_end_pressure_unit' => $this->faker->word,
            'back_gas_tank_type' => $this->faker->word,
            'back_gas_oxygen_percentage' => $this->faker->numberBetween(21, 100),
            'back_gas_helium_percentage' => $this->faker->numberBetween(0, 79),

            // deco_gas_1
            'deco_gas_1_tank_volume' => $this->faker->randomNumber(2),
            'deco_gas_1_tank_volume_unit' => $this->faker->word,
            'deco_gas_1_start_pressure' => $this->faker->randomNumber(2),
            'deco_gas_1_start_pressure_unit' => $this->faker->word,
            'deco_gas_1_end_pressure' => $this->faker->randomNumber(2),
            'deco_gas_1_end_pressure_unit' => $this->faker->word,
            'deco_gas_1_tank_type' => $this->faker->word,
            'deco_gas_1_oxygen_percentage' => $this->faker->numberBetween(21, 100),
            'deco_gas_1_helium_percentage' => $this->faker->numberBetween(0, 79),

            // deco_gas_2
            'deco_gas_2_tank_volume' => $this->faker->randomNumber(2),
            'deco_gas_2_tank_volume_unit' => $this->faker->word,
            'deco_gas_2_start_pressure' => $this->faker->randomNumber(2),
            'deco_gas_2_start_pressure_unit' => $this->faker->word,
            'deco_gas_2_end_pressure' => $this->faker->randomNumber(2),
            'deco_gas_2_end_pressure_unit' => $this->faker->word,
            'deco_gas_2_tank_type' => $this->faker->word,
            'deco_gas_2_oxygen_percentage' => $this->faker->numberBetween(21, 100),
            'deco_gas_2_helium_percentage' => $this->faker->numberBetween(0, 79),

            // deco_gas_3
            'deco_gas_3_tank_volume' => $this->faker->randomNumber(2),
            'deco_gas_3_tank_volume_unit' => $this->faker->word,
            'deco_gas_3_start_pressure' => $this->faker->randomNumber(2),
            'deco_gas_3_start_pressure_unit' => $this->faker->word,
            'deco_gas_3_end_pressure' => $this->faker->randomNumber(2),
            'deco_gas_3_end_pressure_unit' => $this->faker->word,
            'deco_gas_3_tank_type' => $this->faker->word,
            'deco_gas_3_oxygen_percentage' => $this->faker->numberBetween(21, 100),
            'deco_gas_3_helium_percentage' => $this->faker->numberBetween(0, 79),
        ];
    }
}
