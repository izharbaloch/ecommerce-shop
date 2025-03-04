<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Product>
 */
class ProductFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'name' => 'Product' . $this->faker->unique()->numberBetween(1, 100),
            'quantity' => $this->faker->numberBetween(10, 800),
            'price' => $this->faker->randomFloat(2, 10, 999), // Ensure price fits column size
        ];
    }
}
