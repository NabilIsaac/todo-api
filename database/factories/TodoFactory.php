<?php

namespace Database\Factories;

use App\Enums\TodoStatus;
use App\Models\Todo;
use Illuminate\Database\Eloquent\Factories\Factory;

class TodoFactory extends Factory
{
    protected $model = Todo::class;

    public function definition(): array
    {
        return [
            'title' => fake()->sentence(),
            'details' => fake()->paragraph(),
            'status' => fake()->randomElement(TodoStatus::cases())->value,
        ];
    }
}
