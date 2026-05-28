<?php

namespace Database\Factories;

use Domain\DivingLogs\Models\DivingBuddy;
use Domain\DivingLogs\Models\DivingLog;
use Domain\DivingLogs\Models\DivingLogBuddies;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<DivingLogBuddies>
 */
class DivingLogBuddiesFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'diving_buddy_id' => DivingBuddy::factory(),
            'diving_log_id' => DivingLog::factory(),
        ];
    }
}
