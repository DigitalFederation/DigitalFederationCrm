<?php

namespace Database\Factories;

use Domain\Shipping\Models\ShippingMethod;
use Illuminate\Database\Eloquent\Factories\Factory;

class ShippingMethodFactory extends Factory
{
    protected $model = ShippingMethod::class;

    public function definition()
    {
        return [
            'name' => 'UPS',
        ];
    }
}
