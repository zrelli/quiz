<?php

use App\Enums\RolesEnum;
use App\Models\Tenant;
use App\Models\User;
use App\Providers\RouteServiceProvider;
use Illuminate\Support\Facades\Log;
use Spatie\Permission\Models\Role;

test('login screen can be rendered', function () {
    $response = $this->get('/login');

    $response->assertStatus(200);
});

test('users can authenticate using the login screen', function () {
    Role::findOrCreate(RolesEnum::SUPERADMIN->value);
    Role::findOrCreate(RolesEnum::ADMIN->value);
    Role::findOrCreate(RolesEnum::SUPERVISOR->value);
    Role::findOrCreate(RolesEnum::MEMBER->value);

    // $user =         User::factory()->forSuperAdmin()->create();

    // $user =User::factory()->forAdmin()->create();

    // // Log::info($totalAdmins);

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
    $totalAdmins  = User::role(RolesEnum::ADMIN)->count();

    for ($i = 0; $i < $totalAdmins; $i++) {
        Tenant::factory()->setTenantIdFromIndex()->create();
    }

    $tenant = Tenant::where('id','tenant2')->first();


    tenancy()->initialize($tenant);
$user = User::where('id','3')->first();





    $response = $this->post('login', [
        'email' => "admin2@msaaq.com",
        // 'password' => 'sap123456',
        'password' => 'ap123456',
    ]);
    Log::info(tenantId());

    $this->assertAuthenticated();
    $response->assertRedirect(RouteServiceProvider::HOME);
});

test('users can not authenticate with invalid password', function () {
    $user = User::factory()->create();

    $this->post('/login', [
        'email' => $user->email,
        'password' => 'wrong-password',
    ]);

    $this->assertGuest();
});

test('users can logout', function () {
    $user = User::factory()->create();

    $response = $this->actingAs($user)->post('/logout');

    $this->assertGuest();
    $response->assertRedirect('/');
});
