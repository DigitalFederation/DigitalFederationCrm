<?php

namespace Database\Factories;

use Domain\DivingLogs\Models\DivingLogDiving;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<DivingLogDiving>
 */
class DivingLogDivingFactory extends Factory
{
    protected $model = DivingLogDiving::class;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'diving_log_id' => DivingLogDiving::factory(),
            'entry' => $this->faker->word,
            'speciality_dive' => $this->faker->randomElements(['type1', 'type2', 'type3'], $this->faker->numberBetween(1, 3)),
            'duration_minutes' => $this->faker->randomNumber(2),
            'depth' => $this->faker->randomNumber(2),
            'depth_unit' => $this->faker->word,
            'nitrox_percentage' => $this->faker->numberBetween(21, 100),
            'tank_type' => $this->faker->word,
            'tank_volume' => $this->faker->randomNumber(2),
            'tank_volume_unit' => $this->faker->word,
            'start_pressure' => $this->faker->randomNumber(3),
            'start_pressure_unit' => $this->faker->word,
            'end_pressure' => $this->faker->randomNumber(3),
            'end_pressure_unit' => $this->faker->word,
            'average_depth' => $this->faker->randomNumber(2),
            'average_depth_unit' => $this->faker->word,
            'equipment_suit' => $this->faker->word,
            'equipment_mask' => $this->faker->word,
            'equipment_fins' => $this->faker->word,
            'equipment_bcd_wing_sidemount' => $this->faker->word,
            'equipment_first_stage' => $this->faker->word,
            'equipment_second_stage' => $this->faker->word,
            'equipment_dive_computer' => $this->faker->word,
            'equipment_lights' => $this->faker->word,
            'equipment_other' => $this->faker->text,
            'equipment_weight' => $this->faker->randomNumber(2),
            'equipment_weight_unit' => $this->faker->word,
            'created_at' => $this->faker->dateTime(),
            'updated_at' => $this->faker->dateTime(),
        ];
    }
}
