<?php
namespace Database\Seeders;
use Illuminate\Database\Seeder;
class MemberQuizTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        \App\Models\MemberQuiz::factory(10)->create();
    }
}
