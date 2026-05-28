<?php

namespace Database\Factories;

use Domain\DivingLogs\Models\DivingLog;
use Domain\DivingLogs\Models\DivingLogRebreatherScr;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<DivingLogRebreatherScr>
 */
class DivingLogRebreatherScrFactory extends Factory
{
    protected $model = DivingLogRebreatherScr::class;

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
            'scr_total_deco_time' => $this->faker->randomNumber(2),
            'depth' => $this->faker->randomNumber(2),
            'depth_unit' => $this->faker->word,
            'weight' => $this->faker->randomNumber(2),
            'weight_unit' => $this->faker->word,
            'bailout_sac' => $this->faker->randomNumber(2),
            'deco_sac' => $this->faker->randomNumber(2),
            'tank_volume' => $this->faker->randomNumber(2),
            'tank_volume_unit' => $this->faker->word,
            'oxygen_percentage' => $this->faker->randomNumber(2),
            'setpoint' => $this->faker->randomNumber(2),
            'start_pressure' => $this->faker->randomNumber(2),
            'start_pressure_unit' => $this->faker->word,
            'end_pressure' => $this->faker->randomNumber(2),
            'end_pressure_unit' => $this->faker->word,
            'deco_tank_volume' => $this->faker->randomNumber(2),
            'deco_tank_volume_unit' => $this->faker->word,
            'deco_oxygen_percentage' => $this->faker->randomNumber(2),
            'deco_setpoint' => $this->faker->randomNumber(2),
            'deco_start_pressure' => $this->faker->randomNumber(2),
            'deco_start_pressure_unit' => $this->faker->word,
            'deco_end_pressure' => $this->faker->randomNumber(2),
            'deco_end_pressure_unit' => $this->faker->word,
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
