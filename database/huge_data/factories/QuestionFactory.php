<?php
namespace Database\Factories;
use App\Models\Quiz;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;
/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Question>
 */
class QuestionFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $question = fake()->sentence;
        $slug = Str::slug($question);
        return [
            'quiz_id' => Quiz::inRandomOrder()->first()->id,
            'question' => $question,
            'slug' => $slug,
            'description' => fake()->paragraph,
            'is_choices_randomly_ordered' => fake()->boolean,
            'has_multiple_answers' => fake()->boolean,
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
    public function forQuiz(int $quizId): QuestionFactory
    {
        return $this->state(['quiz_id' => $quizId]);
    }
}
