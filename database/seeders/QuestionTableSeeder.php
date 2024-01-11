<?php
namespace Database\Seeders;
use Illuminate\Database\Seeder;
class QuestionTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        \App\Models\Question::factory(10)->create();
        // Question::factory()->forQuiz(1)->create();
        // Question::factory()->forQuiz(1)->create();
        // Question::factory()->forQuiz(1)->create();
        // Question::factory()->forQuiz(1)->create();
        // Question::factory()->forQuiz(1)->create();
        // Question::factory()->forQuiz(1)->create();
        // Question::factory()->forQuiz(1)->create();
        // Question::factory()->forQuiz(1)->create();
        // Question::factory()->forQuiz(1)->create();
        // Question::factory()->forQuiz(1)->create();
        // Question::factory()->forQuiz(1)->create();
        // Question::factory()->forQuiz(2)->create();
    }
}
