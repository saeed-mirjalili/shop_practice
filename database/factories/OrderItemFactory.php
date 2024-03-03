<?php

namespace Database\Factories;

use App\Models\Order;
use App\Models\Product;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\OrderItem>
 */
class OrderItemFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'order_id' => Order::factory()->create()->id,
            'product_id' => Product::factory()->create()->id,
            'price' => fake()->numberBetween(2000, 9000),
            'quantity' => fake()->numberBetween(1, 9),
            'subtotal' => fake()->numberBetween(1000, 9000)
        ];
    }
}
