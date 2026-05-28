<?php

namespace Database\Factories;

use App\Models\User;
use Domain\DivingLogs\Models\DivingBuddy;
use Domain\Individuals\Models\Individual;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<DivingBuddy>
 */
class DivingBuddiesFactory extends Factory
{
    protected $model = DivingBuddy::class;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'user_id' => User::factory(),
            'individual_id' => Individual::factory(),
            'name' => $this->faker->name,
            'cmas_code' => $this->faker->word,
            'created_by' => User::factory(),
            'updated_by' => User::factory(),
        ];
    }
}
