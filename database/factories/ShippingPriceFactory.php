<?php

namespace Database\Factories;

use Domain\Shipping\Models\ShippingPrice;
use Illuminate\Database\Eloquent\Factories\Factory;

class ShippingPriceFactory extends Factory
{
    protected $model = ShippingPrice::class;

    public function definition()
    {
        return [
            'zone_id' => \Domain\Shipping\Models\ShippingZone::factory(), // assuming you have a ShippingZoneFactory
            'weight_id' => \Domain\Shipping\Models\ShippingWeight::factory(), // assuming you have a ShippingWeightFactory
            'method_id' => \Domain\Shipping\Models\ShippingMethod::factory(), // assuming you have a ShippingMethodFactory
            'price' => $this->faker->randomFloat(2, 0, 1000), // generates a random price with 2 decimal places between 0 and 1000
        ];
    }
}
