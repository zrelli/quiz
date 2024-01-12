<?php

namespace Database\Seeders;

use App\Models\MemberQuiz;
use Illuminate\Database\Seeder;

class QuizTestTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        //todo 
        // \App\Models\QuizTest::factory(3)->create();
        // $members = Member::where('tenant_id', '=', 'tenant1')->get();
        // $quizzes = Quiz::where('tenant_id', '=', 'tenant1')->get();
        $quizzesSubscriptions = MemberQuiz::all();
        foreach ($quizzesSubscriptions as $quizzesSubscription) {
            $questions = $quizzesSubscription->quiz()->questions();
            foreach ($questions as $question) {
                $choices = $question->choices();
                $selectedChoice = $choices[0]->id;
            }
        }
    }
}
