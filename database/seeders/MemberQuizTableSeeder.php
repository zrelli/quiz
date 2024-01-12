<?php
namespace Database\Seeders;
use App\Models\Member;
use App\Models\MemberQuiz;
use App\Models\Quiz;
use Illuminate\Database\Seeder;
class MemberQuizTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // \App\Models\MemberQuiz::factory(10)->create();
        // to do add global scope to show only current tenant members
        $members = Member::where('tenant_id', '=', 'tenant1')->get();
        $quizzes = Quiz::where('tenant_id', '=', 'tenant1')->get();
        foreach ($quizzes as $quiz) {
            foreach ($members as $member) {
                MemberQuiz::factory()->MemberSubscribeToQuiz($member->id, $quiz->id)->create();
            }
        }
    }
}
