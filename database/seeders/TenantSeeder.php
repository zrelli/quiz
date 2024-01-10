<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;

use App\Models\Tenant;
use App\Models\User;
use Illuminate\Database\Seeder;

class TenantSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $users = User::all();
        $users->shift();
        foreach ($users as $user) {
            $tenantIdentifier = 'tenant_' . ($user->id - 1);
            $tenant = Tenant::create(['id' => $tenantIdentifier]);
            $tenant->domains()->create(['domain' => ('tenant' . $user->id - 1) . '.quizzes.test']);
            $user->tenant_id = $tenantIdentifier;
            $user->save();
        }
    }
}
