<?php

namespace Database\Seeders;

use App\Enums\RolesEnum;
use App\Models\Member;
use App\Models\Tenant;
use App\Models\User;
use Illuminate\Database\Seeder;

class MemberTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // \App\Models\Member::factory(10)->create();
        // setUserIdTenantId
        // $members  = User::role(RolesEnum::MEMBER)->get();
        // $totalTenants  = Tenant::count();
        // foreach ($members as $member) {
        //     // most ids should be between 1 and 5
        //     $randomNumber =
        //         fake()->optional(0.9)->numberBetween(1, 3)
        //         ?? fake()->numberBetween(4, ($totalTenants - 1));
        //     $tenantId = 'tenant' . $randomNumber;
        //     Member::factory()->setUserIdTenantId($member->id, $tenantId)->create();
        // }
    }
}
