<?php

namespace Database\Factories;

use App\Models\Order;
use App\Models\Product;
use App\Models\Review;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

class ReviewFactory extends Factory
{
    protected $model = Review::class;

    public function definition(): array
    {
        return [
            'user_id' => User::factory(),
            'product_id' => Product::factory(),
            'order_id' => Order::factory(),
            'rating' => fake()->numberBetween(3, 5),
            'comment' => fake()->paragraph(2),
            'status' => Review::STATUS_PUBLISHED,
        ];
    }

    public function pending(): static
    {
        return $this->state(fn () => ['status' => Review::STATUS_PENDING]);
    }

    public function hidden(): static
    {
        return $this->state(fn () => ['status' => Review::STATUS_HIDDEN]);
    }
}