<?php
namespace Database\Seeders;

use App\Enums\RolesEnum;
use App\Models\Member;
use App\Models\Tenant;
use App\Models\User;
use Illuminate\Database\Seeder;
class UserTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $totalAdminsAndSupervisors = 10; //20;
        $totalMembers = 10; //100;
        User::factory()->forSuperAdmin()->create();
        User::factory()->resetIndex();
        for ($i = 0; $i < $totalAdminsAndSupervisors; $i++) {
            User::factory()->forAdmin()->create();
        }



      





        User::factory()->resetIndex();
        for ($i = 0; $i < $totalAdminsAndSupervisors; $i++) {
            User::factory()->forSupervisor()->create();
        }

        $totalAdmins  = User::role(RolesEnum::ADMIN)->count();
        // Log::info($totalAdmins);
        for ($i = 0; $i < $totalAdmins; $i++) {
            Tenant::factory()->setTenantIdFromIndex()->create();
        }



        // User::factory()->resetIndex();
        for ($i = 0; $i < $totalMembers; $i++) {
            // User::factory()->forMember()->create();
            $totalTenants  = Tenant::count();                // most ids should be between 1 and 5
            $randomNumber =
                fake()->optional(0.9)->numberBetween(1, 3)
                ?? fake()->numberBetween(4, ($totalTenants - 1));
            $tenantId = 'tenant' . $randomNumber;
            Member::factory()->forMember($tenantId)->create();
        }
    }
}
