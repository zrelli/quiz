<?php

namespace Database\Factories;

use App\Models\Question;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Choice>
 */
class ChoiceFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'question_id' => Question::inRandomOrder()->first()->id,
            'is_correct' => fake()->boolean,
            // 'order' => fake()->numberBetween(1, 10),//todo 
            'description' => fake()->paragraph,
            'explanation' => fake()->paragraph,
            'created_at' => now(),
            'updated_at' => now(),
        ];
    }
}
