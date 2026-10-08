<?php

namespace Database\Factories;

use App\Models\Brand;
use App\Models\Category;
use App\Models\Product;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

class OrderFactory extends Factory
{
    protected $model = \App\Models\Order::class;

    public function definition(): array
    {
        $subtotal = fake()->numberBetween(75_000, 2_500_000);
        $shipping = 15_000;

        return [
            'order_number' => 'PCH-'.now()->format('Ymd').'-'.Str::upper(Str::random(6)),
            'user_id' => \App\Models\User::factory(),
            'status' => \App\Models\Order::STATUS_PENDING,
            'customer_name' => fake()->name(),
            'email' => fake()->safeEmail(),
            'phone' => '08'.fake()->numerify('##########'),
            'whatsapp' => '08'.fake()->numerify('##########'),
            'street' => fake()->streetAddress(),
            'province' => fake()->randomElement(['Jawa Barat', 'DKI Jakarta', 'Jawa Timur', 'DI Yogyakarta']),
            'city' => fake()->city(),
            'district' => fake()->citySuffix(),
            'postal_code' => fake()->numerify('#####'),
            'shipping_method' => 'reguler',
            'shipping_address' => fake()->streetAddress(),
            'subtotal' => $subtotal,
            'discount' => 0,
            'shipping_cost' => $shipping,
            'tax' => 0,
            'total' => $subtotal + $shipping,
            'payment_method' => 'bank_transfer',
        ];
    }

    public function completed(): static
    {
        return $this->state(fn () => [
            'status' => \App\Models\Order::STATUS_COMPLETED,
            'paid_at' => now()->subDays(fake()->numberBetween(1, 30)),
            'completed_at' => now()->subDays(fake()->numberBetween(0, 20)),
        ]);
    }

    public function paid(): static
    {
        return $this->state(fn () => ['status' => \App\Models\Order::STATUS_PAID, 'paid_at' => now()]);
    }

    public function shipped(): static
    {
        return $this->state(fn () => ['status' => \App\Models\Order::STATUS_SHIPPED, 'paid_at' => now()->subDays(2)]);
    }

    public function cancelled(): static
    {
        return $this->state(fn () => ['status' => \App\Models\Order::STATUS_CANCELLED]);
    }
}