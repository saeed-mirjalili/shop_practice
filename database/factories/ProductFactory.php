<?php

namespace Database\Factories;

use App\Models\Brand;
use App\Models\Category;
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
            'name' => fake()->name(),
            'category_id' => Category::factory()->create()->id,
            'primary_image' => fake()->image(),
            'price' => fake()->numberBetween(1000, 9000),
            'quantity' => fake()->numberBetween(1, 9),
            'description' => fake()->sentence(5),
        ];
    }
}
