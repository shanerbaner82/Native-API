<?php

namespace Database\Factories;

use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Todo>
 */
class TodoFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'user_id' => User::factory()->make(),
            'title' => $this->faker->sentence,
            'uuid' => Str::uuid()->toString(),
            'completed_at' => $this->faker->randomElement([null, now()]),
            'currently_working_on' => $this->faker->boolean(10),
        ];
    }
}
