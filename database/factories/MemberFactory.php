<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Member>
 */
class MemberFactory extends Factory
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
            // 'tenant_id' => Tenant::inRandomOrder()->first()->id,
            // 'user_id' => User::inRandomOrder()->first()->id,
            // 'created_at' => now(),
            // 'updated_at' => now()
        ];
    }
    /**
     * Indicate that the question belongs to a specific quiz.
     *
     * @param  int  $quizId
     * @return \Illuminate\Database\Eloquent\Factories\Factory
     */
    public function setUserIdTenantId($userId, $tenantId): MemberFactory
    {
        return $this->state(['user_id' => $userId, 'tenant_id' => $tenantId]);
    }
}
