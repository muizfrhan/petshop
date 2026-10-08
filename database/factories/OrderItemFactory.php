<?php

namespace Database\Factories;

use App\Models\Order;
use App\Models\Product;
use Illuminate\Database\Eloquent\Factories\Factory;

class OrderItemFactory extends Factory
{
    protected $model = \App\Models\OrderItem::class;

    public function definition(): array
    {
        $quantity = fake()->numberBetween(1, 4);
        $price = fake()->numberBetween(50_000, 900_000);

        return [
            'order_id' => Order::factory(),
            'product_id' => Product::factory(),
            'product_name' => fake()->words(3, true),
            'product_sku' => 'PCH'.fake()->numerify('######'),
            'price' => $price,
            'quantity' => $quantity,
            'subtotal' => $price * $quantity,
        ];
    }
}