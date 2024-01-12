<?php

namespace Database\Factories;

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
            // 'question_id' => Question::inRandomOrder()->first()->id,
            // 'is_correct' => fake()->boolean,// all first created choices are true
            // 'order' => fake()->numberBetween(1, 10),//todo 
            'description' => fake()->paragraph,
            'explanation' => fake()->paragraph,
            'created_at' => now(),
            'updated_at' => now(),
        ];
    }
    /**
     * Indicate that the question belongs to a specific quiz.
     *
     * @param  int  $quizId
     * @return \Illuminate\Database\Eloquent\Factories\Factory
     */
    public function forQuestion(int $questionId, $isCorrect = false): ChoiceFactory
    {
        return $this->state([
            'question_id' => $questionId,
            'is_correct' => $isCorrect
        ]);
    }
}
