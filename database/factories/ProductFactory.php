<?php

namespace Database\Factories;

use Domain\Products\Models\Product;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Product>
 */
class ProductFactory extends Factory
{
    protected $model = Product::class;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'name' => $this->faker->name,
            'code' => $this->faker->unique()->randomNumber(6),
            'description' => $this->faker->text,
            'price' => $this->faker->numberBetween(1, 1000),
            'tax_value' => $this->faker->numberBetween(1, 100),
            'tax_percentage' => $this->faker->numberBetween(1, 100),
        ];
    }
}
