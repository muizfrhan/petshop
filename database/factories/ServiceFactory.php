<?php

namespace Database\Factories;

use App\Models\Service;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

class ServiceFactory extends Factory
{
    protected $model = Service::class;

    public function definition(): array
    {
        $name = Str::title(fake()->unique()->words(2, true)).' Hewan';

        return [
            'name' => $name,
            'slug' => Str::slug($name).'-'.Str::lower(Str::random(4)),
            'description' => fake()->sentence(14),
            'includes' => "Perawatan dasar\nPembersihan area sensitif\nReports abnormal\nKonsultasi allowances",
            'price' => fake()->numberBetween(50_000, 750_000),
            'duration_minutes' => fake()->randomElement([30, 45, 60, 90, 120]),
            'icon' => fake()->randomElement(['shower', 'hotel', 'medical_services', 'vaccines', 'spa', 'local_shipping', 'home']),
            'status' => 'active',
            'featured' => fake()->boolean(30),
            'sort_order' => fake()->numberBetween(0, 50),
        ];
    }

    public function inactive(): static
    {
        return $this->state(fn () => ['status' => 'inactive']);
    }
}