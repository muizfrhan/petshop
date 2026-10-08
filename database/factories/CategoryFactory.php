<?php

namespace Database\Factories;

use App\Models\Category;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

class CategoryFactory extends Factory
{
    protected $model = Category::class;

    public function definition(): array
    {
        $name = fake()->unique()->randomElement([
            'Makanan Anjing', 'Makanan Kucing', 'Makanan Burung', 'Makanan Ikan',
            'Makanan Kelinci', 'Suplemen & Vitamin', 'Mainan Interaktif', 'Perawatan Bulu',
            'Aksesoris Kandang', 'Kebutuhan Bayi Hewan',
        ]).' '.fake()->unique()->numberBetween(1, 9999);

        return [
            'name' => $name,
            'slug' => Str::slug($name).'-'.Str::lower(Str::random(4)),
            'description' => fake()->sentence(12),
            'icon' => fake()->randomElement(['pets', 'restaurant', 'medical_services', 'toys', 'cruelty_free', 'vaccines']),
            'status' => 'active',
            'sort_order' => fake()->numberBetween(0, 50),
        ];
    }

    public function inactive(): static
    {
        return $this->state(fn () => ['status' => 'inactive']);
    }
}