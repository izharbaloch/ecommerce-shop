<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str; // Import Str helper

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
        $productName = 'Product' . $this->faker->unique()->numberBetween(1, 100);
        return [
            'name' => $productName,
            'slug' => Str::slug($productName). '-' . Str::random(5), // Use imported Str helper
            'quantity' => $this->faker->numberBetween(10, 800),
            'price' => $this->faker->randomFloat(2, 10, 999), // Ensure price fits column size
        ];
    }
}
