<?php

namespace Database\Factories;

use App\Models\Service;
use App\Models\ServiceBooking;
use Illuminate\Database\Eloquent\Factories\Factory;

class BookingFactory extends Factory
{
    protected $model = ServiceBooking::class;

    public function definition(): array
    {
        $service = Service::factory();

        return [
            'service_id' => $service,
            'user_id' => \App\Models\User::factory(),
            'customer_name' => fake()->name(),
            'phone' => '08'.fake()->numerify('##########'),
            'email' => fake()->safeEmail(),
            'pet_name' => fake()->firstName(),
            'pet_type' => fake()->randomElement(['anjing', 'kucing', 'burung', 'ikan', 'kelinci', 'lainnya']),
            'pet_breed' => fake()->randomElement(['Golden Retriever', 'Persia', 'Kakatua', 'Nemo', 'Rex', 'Mixed']),
            'pet_weight' => fake()->randomFloat(1, 1, 30),
            'booking_date' => fake()->dateTimeBetween('-2 weeks', '+1 month')->format('Y-m-d'),
            'booking_time' => fake()->randomElement(['09:00', '10:30', '13:00', '15:30', '17:00']),
            'notes' => fake()->sentence(10),
            'status' => ServiceBooking::STATUS_PENDING,
            'price' => fake()->numberBetween(50_000, 500_000),
        ];
    }

    public function confirmed(): static
    {
        return $this->state(fn () => ['status' => ServiceBooking::STATUS_CONFIRMED]);
    }

    public function today(): static
    {
        return $this->state(fn () => ['booking_date' => today()->format('Y-m-d')]);
    }
}