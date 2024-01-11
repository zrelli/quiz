<?php

namespace Database\Factories;

use App\Models\Quiz;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\QuizPeriodTime>
 */
class QuizPeriodTimeFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $startTime = $this->faker->dateTimeBetween('-1 week', 'now');
        $endTime = Carbon::parse($startTime)->addHours($this->faker->randomElement([2, 3]));
        return [
            'start_time' => $startTime,
            'end_time' => $endTime,
            'quiz_id' =>  Quiz::inRandomOrder()->first()->id,
            'created_at' => now(),
            'updated_at' => now(),
        ];
    }
}
