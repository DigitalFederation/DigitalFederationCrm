<?php

namespace Database\Factories;

use App\Enums\DivingLogDiveTypeEnum;
use Domain\DivingLogs\Models\DivingBuddy;
use Domain\DivingLogs\Models\DivingLog;
use Domain\DivingLogs\States\PendingDivingLogState;
use Domain\Individuals\Models\Individual;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<DivingLog>
 */
class DivingLogFactory extends Factory
{
    protected $model = DivingLog::class;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'individual_id' => Individual::factory(),
            'dive_type' => $this->faker->randomElement(DivingLogDiveTypeEnum::cases()),
            'category' => $this->faker->word,
            'buddy_id' => DivingBuddy::factory(),
            'diving_location_id' => $this->faker->randomNumber(),
            'date_and_time' => $this->faker->dateTime(),
            'dive_site_score' => $this->faker->randomNumber(),
            'status_class' => PendingDivingLogState::class,
            'environment_entry' => $this->faker->word,
            'environment_water_type' => $this->faker->word,
            'environment_current' => $this->faker->word,
            'environment_surface' => $this->faker->word,
            'environment_water_temperature' => $this->faker->randomNumber(2),
            'environment_water_temperature_unit' => $this->faker->text(10),
            'environment_air_temperature' => $this->faker->randomNumber(2),
            'environment_air_temperature_unit' => $this->faker->text(10),
            'environment_water_visibility' => $this->faker->randomNumber(2),
            'environment_water_visibility_unit' => $this->faker->text(10),
            'wildlife' => $this->faker->text,
            'notes' => $this->faker->text,
            'created_at' => $this->faker->dateTime(),
            'updated_at' => $this->faker->dateTime(),
        ];
    }
}
