<?php

namespace Database\Factories;

use App\Models\MemberQuiz;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\QuizTest>
 */
class QuizTestFactory extends Factory
{
   
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'member_quiz_id' => MemberQuiz::inRandomOrder()->first()->id,
            'is_successful' => fake()->optional(0.5)->boolean, 
            'score' => fake()->optional(0.9)->numberBetween(1, 100), 
            'spent_time' => fake()->optional(0.7)->numberBetween(1, 300), 
            'created_at' => now(),
            'updated_at' => now(),
        ];
    }
  
}
