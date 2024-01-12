<?php

namespace Database\Seeders;

use App\Models\Question;
use App\Models\Quiz;
use Illuminate\Database\Seeder;

class QuestionTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $quizzes  = Quiz::all();
        foreach ($quizzes as $quiz) {
            $totalQuestions = 10;
            for ($i = 0; $i < $totalQuestions; $i++) {
                Question::factory()->forQuiz($quiz->id)->create();
            }
        }
    }
}
