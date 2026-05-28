<?php

namespace Database\Factories;

use Domain\DivingLogs\Models\DivingLog;
use Domain\DivingLogs\Models\DivingLogFreediving;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<DivingLogFreediving>
 */
class DivingLogFreedivingFactory extends Factory
{
    protected $model = DivingLogFreediving::class;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'diving_log_id' => DivingLog::factory(),
            'entry' => $this->faker->word,
            'freedive_discipline' => $this->faker->word,
            'warm_ups' => $this->faker->randomNumber(2),
            'max_time' => $this->faker->randomNumber(2),
            'contraction_time' => $this->faker->randomNumber(2),
            'time' => $this->faker->randomNumber(2),
            'max_distance' => $this->faker->randomNumber(2),
            'max_distance_unit' => $this->faker->word,
            'max_depth' => $this->faker->randomNumber(2),
            'max_depth_unit' => $this->faker->word,
            'equipment_suit' => $this->faker->word,
            'equipment_mask' => $this->faker->word,
            'equipment_fins' => $this->faker->word,
            'equipment_dive_computer' => $this->faker->word,
            'equipment_other' => $this->faker->paragraph,
            'equipment_weight' => $this->faker->randomNumber(2),
            'equipment_weight_unit' => $this->faker->word,
        ];
    }
}
