<?php

namespace Database\Factories;

use Domain\Shipping\Models\ShippingWeight;
use Illuminate\Database\Eloquent\Factories\Factory;

class ShippingWeightFactory extends Factory
{
    protected $model = ShippingWeight::class;

    public function definition()
    {
        return [
            // Assuming 'method_id' relates to a ShippingMethod model that also has a factory
            'range' => $this->faker->word(), // e.g., '0-100' or '101-200'
            'minimum_weight' => $this->faker->randomFloat(2, 0, 100), // e.g., between 0 to 100 kg
            'maximum_weight' => $this->faker->randomFloat(2, 101, 200), // e.g., between 101 to 200 kg
        ];
    }
}
