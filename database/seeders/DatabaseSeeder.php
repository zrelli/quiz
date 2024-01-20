<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $this->call(PermissionTableSeeder::class);
        $this->call(RoleTableSeeder::class);
        $this->call(UserTableSeeder::class);
        $this->call(TenantTableSeeder::class);
        // $this->call(MemberTableSeeder::class);
        $this->call(QuizTableSeeder::class);
        // $this->call(QuizPeriodTimeTableSeeder::class);//deleted
        $this->call(QuestionTableSeeder::class);
        $this->call(ChoiceTableSeeder::class);
        $this->call(MemberQuizTableSeeder::class);
        // $this->call(QuizTestTableSeeder::class);
    }
}
