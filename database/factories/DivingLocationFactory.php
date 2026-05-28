<?php

namespace Database\Factories;

use App\Models\Country;
use App\Models\User;
use Domain\DivingLogs\Models\DivingLocation;
use Domain\Federations\Models\Federation;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<DivingLocation>
 */
class DivingLocationFactory extends Factory
{
    protected $model = DivingLocation::class;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'name' => $this->faker->unique()->city,
            'region' => $this->faker->state,
            'country_id' => Country::factory(),
            'lat' => $this->faker->latitude,
            'lng' => $this->faker->longitude,
            'owner_type' => Federation::class,
            'owner_id' => Federation::factory(),
            'created_by' => User::factory(),
            'updated_by' => User::factory(),
            'native_name' => $this->faker->city,
        ];
    }
}
