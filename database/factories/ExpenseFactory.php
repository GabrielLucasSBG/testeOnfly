<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Expense>
 */
class ExpenseFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'user_id' => 1,
            'description' => $this->faker->sentence(5),
            'value' => $this->faker->randomFloat(2, 1, 1000),
            'date' => $this->faker->date('Y-m-d')
        ];
    }
}
