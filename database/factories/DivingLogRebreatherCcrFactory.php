<?php

namespace Database\Factories;

use Domain\DivingLogs\Models\DivingLog;
use Domain\DivingLogs\Models\DivingLogRebreatherCcr;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<DivingLogRebreatherCcr>
 */
class DivingLogRebreatherCcrFactory extends Factory
{
    protected $model = DivingLogRebreatherCCR::class;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'diving_log_id' => DivingLog::factory(),
            'runtime' => $this->faker->randomNumber(2),
            'ccr_total_deco_time' => $this->faker->randomNumber(2),
            'depth' => $this->faker->randomNumber(2),
            'depth_unit' => $this->faker->word,
            'bailout_sac' => $this->faker->randomNumber(2),
            'deco_sac' => $this->faker->randomNumber(2),
            'diluent_tank_type' => $this->faker->word,
            'diluent_tank_volume' => $this->faker->randomNumber(2),
            'diluent_tank_volume_unit' => $this->faker->word,
            'diluent_oxygen_percentage' => $this->faker->randomNumber(2),
            'diluent_helium_percentage' => $this->faker->randomNumber(2),
            'diluent_start_pressure' => $this->faker->randomNumber(2),
            'diluent_start_pressure_unit' => $this->faker->word,
            'diluent_end_pressure' => $this->faker->randomNumber(2),
            'diluent_end_pressure_unit' => $this->faker->word,
            'bailout_gas_1_tank_type' => $this->faker->word,
            'bailout_gas_1_tank_volume' => $this->faker->randomNumber(2),
            'bailout_gas_1_tank_volume_unit' => $this->faker->word,
            'bailout_gas_1_oxygen_percentage' => $this->faker->randomNumber(2),
            'bailout_gas_1_helium_percentage' => $this->faker->randomNumber(2),
            'bailout_gas_1_start_pressure' => $this->faker->randomNumber(2),
            'bailout_gas_1_start_pressure_unit' => $this->faker->word,
            'bailout_gas_1_end_pressure' => $this->faker->randomNumber(2),
            'bailout_gas_1_end_pressure_unit' => $this->faker->word,
            'bailout_gas_2_tank_type' => $this->faker->word,
            'bailout_gas_2_tank_volume' => $this->faker->randomNumber(2),
            'bailout_gas_2_tank_volume_unit' => $this->faker->word,
            'bailout_gas_2_oxygen_percentage' => $this->faker->randomNumber(2),
            'bailout_gas_2_helium_percentage' => $this->faker->randomNumber(2),
            'bailout_gas_2_start_pressure' => $this->faker->randomNumber(2),
            'bailout_gas_2_start_pressure_unit' => $this->faker->word,
            'bailout_gas_2_end_pressure' => $this->faker->randomNumber(2),
            'bailout_gas_2_end_pressure_unit' => $this->faker->word,
            'bailout_gas_3_tank_type' => $this->faker->word,
            'bailout_gas_3_tank_volume' => $this->faker->randomNumber(2),
            'bailout_gas_3_tank_volume_unit' => $this->faker->word,
            'bailout_gas_3_oxygen_percentage' => $this->faker->randomNumber(2),
            'bailout_gas_3_helium_percentage' => $this->faker->randomNumber(2),
            'bailout_gas_3_start_pressure' => $this->faker->randomNumber(2),
            'bailout_gas_3_start_pressure_unit' => $this->faker->word,
            'bailout_gas_3_end_pressure' => $this->faker->randomNumber(2),
            'bailout_gas_3_end_pressure_unit' => $this->faker->word,
            'entry' => $this->faker->word,
            'water_type' => $this->faker->word,
            'current' => $this->faker->word,
            'surface' => $this->faker->word,
            'equipment_suit' => $this->faker->word,
            'equipment_mask' => $this->faker->word,
            'equipment_fins' => $this->faker->word,
            'equipment_bcd_wing_sidemount' => $this->faker->word,
            'equipment_rebreather_unit' => $this->faker->word,
            'equipment_dive_computer' => $this->faker->word,
            'equipment_lights' => $this->faker->word,
            'equipment_other' => $this->faker->paragraph,
            'equipment_weight' => $this->faker->randomNumber(2),
            'equipment_weight_unit' => $this->faker->word,
        ];
    }
}
