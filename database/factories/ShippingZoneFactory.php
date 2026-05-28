<?php

namespace Database\Factories;

use Domain\Shipping\Models\ShippingZone;
use Illuminate\Database\Eloquent\Factories\Factory;

class ShippingZoneFactory extends Factory
{
    protected $model = ShippingZone::class;

    public function definition()
    {
        return [
            'name' => $this->faker->numberBetween(1, 10),
        ];
    }
}
