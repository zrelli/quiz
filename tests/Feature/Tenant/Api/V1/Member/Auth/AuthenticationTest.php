<?php

use App\Models\Member;
use App\Models\Tenant;
use App\Models\User;
use Laravel\Sanctum\Sanctum;

test('members can authenticate using the login api', function () {
    $tenant = Tenant::where('id', 'tenant1')->first();
    $user = Member::where('id', '1')->first();
    $baseUrlWithoutProtocol = str_replace('http://', '', route('api.member.login'));
    $tenantUrl = "http://$tenant->id.$baseUrlWithoutProtocol";
    $response = $this->post($tenantUrl, [
        'email' => $user->email,
        'password' => 'mp123456',
    ]);
    $response->assertStatus(200)
        ->assertJson([
            'message' => 'Login success',
            'token_type' => 'Bearer',
        ])
        ->assertJsonStructure([
            'message',
            'access_token',
            'token_type',
        ]);
});
test('members can not authenticate with invalid password', function () {
    $tenant = Tenant::where('id', 'tenant1')->first();
    $user = Member::where('id', '1')->first();
    $baseUrlWithoutProtocol = str_replace('http://', '', route('api.member.login'));
    $tenantUrl = "http://$tenant->id.$baseUrlWithoutProtocol";
    $response = $this->post($tenantUrl, [
        'email' => $user->email,
        'password' => 'mp123456...',
    ]);
    $response->assertStatus(401) // Check for Unauthorized status code
        ->assertJson([
            'message' => 'Unauthorized',
            'success' => false,
        ]);
});


// todo add validation
test('members login validation', function () {
    $tenant = Tenant::where('id', 'tenant1')->first();
    $baseUrlWithoutProtocol = str_replace('http://', '', route('api.member.login'));
    $tenantUrl = "http://$tenant->id.$baseUrlWithoutProtocol";
    $response = $this->post($tenantUrl, []);
    $response->assertStatus(401);
});








test('members can authenticate using the logout api', function () {
    $tenant = Tenant::where('id', 'tenant1')->first();
    $user = Member::where('id', '1')->first();
    Sanctum::actingAs($user);
    $baseUrlWithoutProtocol = str_replace('http://', '', route('api.member.logout'));
    $tenantUrl = "http://$tenant->id.$baseUrlWithoutProtocol";
    $response = $this->post($tenantUrl);
    $response->assertStatus(200)
        ->assertJson([
            'message' => 'logout success',
            'success' => true,
        ]);
});
test('members can not authenticate to other tenant using the login api', function () {
    $tenant = Tenant::where('id', 'tenant2')->first();
    $user = Member::where('id', '1')->first();
    $baseUrlWithoutProtocol = str_replace('http://', '', route('api.member.login'));
    $tenantUrl = "http://$tenant->id.$baseUrlWithoutProtocol";
    $response = $this->post($tenantUrl, [
        'email' => $user->email,
        'password' => 'mp123456',
    ]);
    $response->assertStatus(401) // Check for Unauthorized status code
        ->assertJson([
            'message' => 'Unauthorized',
            'success' => false,
        ]);
});

test('members can create account using register api', function () {
    $tenant = Tenant::where('id', 'tenant1')->first();
    $baseUrlWithoutProtocol = str_replace('http://', '', route('api.member.register'));
    $tenantUrl = "http://$tenant->id.$baseUrlWithoutProtocol";
    $response = $this->post($tenantUrl, [
        'email' => 'member99@msaaq.com',
        'name' => 'membmer99',
        'password' => 'mp123456',
    ]);
    $response->assertStatus(200)
        ->assertJsonStructure([
            'data' => [
                'name',
                'email',
                'password',
                'tenant_id',
                'updated_at',
                'created_at',
                'id',
                'tenant' => [
                    'id',
                    'created_at',
                    'updated_at',
                    'data',
                    'domains' => [
                        [
                            'id',
                            'domain',
                            'tenant_id',
                            'created_at',
                            'updated_at',
                        ],
                    ],
                ],
            ],
        ]);
});


test('member register validation', function () {
    $tenant = Tenant::where('id', 'tenant1')->first();
    $baseUrlWithoutProtocol = str_replace('http://', '', route('api.member.register'));
    $tenantUrl = "http://$tenant->id.$baseUrlWithoutProtocol";
    $response = $this->post($tenantUrl, []);
    $response->assertStatus(422)->assertJsonValidationErrors(['email', 'name', 'password']);
});



//
