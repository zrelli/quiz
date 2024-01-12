<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\MemberQuiz>
 */
class MemberQuizFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            // 'average_score' => fake()->numberBetween(1, 100),
            // 'average_spent_time' => fake()->optional(0.7)->numberBetween(1, 180), //MINUTES
            // 'total_attempts' => fake()->optional(0.7)->numberBetween(1, 50), 
            // 'is_successful' => fake()->optional(0.5)->boolean, 
            // 'member_id' => Member::inRandomOrder()->first()->id,
            // 'quiz_id' => Quiz::inRandomOrder()->first()->id,
            // 'created_at' => now(),
            // 'updated_at' => now(),
        ];
    }
    /**
     * Admin Custom Fields
     *
     * @return \Illuminate\Database\Eloquent\Factories\Factory
     */
    public function MemberSubscribeToQuiz($memberId, $quizId): MemberQuizFactory
    {
        // $memberName = "member" . self::$index;
        // $memberEmail = $memberName . "@masaq.com";
        return $this->state(
            [
                'member_id' => $memberId,
                'quiz_id' => $quizId
            ]
        );
    }
}
