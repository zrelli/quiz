<?php
namespace Database\Seeders;
use App\Models\User;
use Illuminate\Database\Seeder;
class UserTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $totalAdminsAndSupervisors = 10;//20;
        $totalMembers =10; //100;
        User::factory()->forSuperAdmin()->create();
        User::factory()->resetIndex();
        for ($i = 0; $i < $totalAdminsAndSupervisors; $i++) {
            User::factory()->forAdmin()->create();
        }
        User::factory()->resetIndex();
        for ($i = 0; $i < $totalAdminsAndSupervisors; $i++) {
            User::factory()->forSupervisor()->create();
        }
        User::factory()->resetIndex();
        for ($i = 0; $i < $totalMembers; $i++) {
            User::factory()->forMember()->create();
        }
    }
}
