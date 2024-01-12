<?php

namespace Database\Seeders;

use App\Models\Choice;
use App\Models\Question;
use Illuminate\Database\Seeder;

class ChoiceTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $questions  = Question::all();
        foreach ($questions as $question) {
            $totalChoices = 5;
            for ($i = 0; $i < $totalChoices; $i++) {
                $isCorrect = $i == 0 ? true : false;
                Choice::factory()->forQuestion($question->id, $isCorrect)->create();
            }
        }
    }
}
