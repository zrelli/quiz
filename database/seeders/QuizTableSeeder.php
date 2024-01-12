<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class QuizTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        \App\Models\Quiz::factory(2)->forOutOfTimeQuiz('tenant1')->create();
        \App\Models\Quiz::factory()->setDuration(1);
        \App\Models\Quiz::factory(2)->forInTimeQuiz('tenant1')->create();
        \App\Models\Quiz::factory()->setDuration(2);
        \App\Models\Quiz::factory(2)->forInTimeQuiz('tenant1')->create();
    }
}
