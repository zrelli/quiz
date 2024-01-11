<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class QuizTestTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        \App\Models\QuizTest::factory(3)->create();

    }
}
