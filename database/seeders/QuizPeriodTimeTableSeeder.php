<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class QuizPeriodTimeTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        \App\Models\QuizPeriodTime::factory(3)->create();

    }
}
