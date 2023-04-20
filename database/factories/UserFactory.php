<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\User>
 */
class UserFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition()
    {
        return [
            'name' => fake()->name(),
            'email' => fake()->unique()->safeEmail(),
            'email_verified_at' => now(),
            'password' =>  bcrypt('Admin123'),
            'type' =>  'biker',
        ];
    }

    public function sender(): Factory
    {
        return $this->state(function (array $attributes) {
            return [
                'type' => 'sender',
            ];
        });
    }
}
