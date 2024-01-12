<?php

namespace Database\Seeders;

use App\Enums\RolesEnum;
use App\Models\Tenant;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Log;

class TenantTableSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $totalAdmins  = User::role(RolesEnum::ADMIN)->count();
        // Log::info($totalAdmins);
        for ($i = 0; $i < $totalAdmins; $i++) {
            Tenant::factory()->setTenantIdFromIndex()->create();
        }
    }
}
