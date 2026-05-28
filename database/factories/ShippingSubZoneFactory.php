<?php

namespace Database\Factories;

use App\Models\Country;
use Domain\Shipping\Models\ShippingSubZone;
use Illuminate\Database\Eloquent\Factories\Factory;

class ShippingSubZoneFactory extends Factory
{
    protected $model = ShippingSubZone::class;

    public function definition()
    {
        return [
            'country_id' => Country::factory(),
            'name' => $this->faker->word(), // generates a random word as name
        ];
    }
}
