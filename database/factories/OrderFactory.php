<?php

namespace Database\Factories;

use App\Enums\OrderStatus;
use App\Models\Order;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Order>
 */
class OrderFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $subtotal = fake()->numberBetween(20, 120) * 1000;

        return [
            'customer_name' => fake()->name(),
            'customer_phone' => '3'.fake()->numerify('#########'),
            'city' => fake()->city(),
            'address' => fake()->streetAddress(),
            'notes' => null,
            'subtotal' => $subtotal,
            'shipping_cost' => 0,
            'total' => $subtotal,
            'status' => OrderStatus::Pending,
        ];
    }
}
