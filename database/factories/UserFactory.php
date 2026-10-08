<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

class UserFactory extends Factory
{
    protected $model = \App\Models\User::class;

    protected static ?string $password = null;

    public function definition(): array
    {
        return [
            'name' => fake()->name(),
            'email' => fake()->unique()->safeEmail(),
            'email_verified_at' => now(),
            'password' => static::$password ??= bcrypt('password'),
            'phone' => '08'.fake()->numerify('##########'),
            'role' => \App\Models\User::ROLE_CUSTOMER,
            'status' => \App\Models\User::STATUS_ACTIVE,
            'remember_token' => \Illuminate\Support\Str::random(10),
        ];
    }

    public function unverified(): static
    {
        return $this->state(fn () => ['email_verified_at' => null]);
    }

    public function admin(): static
    {
        return $this->state(fn () => ['role' => \App\Models\User::ROLE_ADMIN]);
    }

    public function inactive(): static
    {
        return $this->state(fn () => ['status' => \App\Models\User::STATUS_INACTIVE]);
    }
}