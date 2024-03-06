<?php
use App\Models\Tenant;
use App\Models\User;
use Laravel\Sanctum\Sanctum;
test('companies can authenticate using the login api', function () {
    $tenant = Tenant::where('id', 'tenant2')->first();
    $user = User::where('id', '3')->first();
    $baseUrlWithoutProtocol = str_replace('http://', '', route('api.company.login'));
    $tenantUrl = "http://$tenant->id.$baseUrlWithoutProtocol";
    $response = $this->post($tenantUrl, [
        'email' => $user->email,
        'password' => 'ap123456',
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
test('companies can not authenticate with invalid password', function () {
    $tenant = Tenant::where('id', 'tenant2')->first();
    $user = User::where('id', '3')->first();
    $baseUrlWithoutProtocol = str_replace('http://', '', route('api.company.login'));
    $tenantUrl = "http://$tenant->id.$baseUrlWithoutProtocol";
    $response = $this->post($tenantUrl, [
        'email' => $user->email,
        'password' => 'ap123456...',
    ]);
    $response->assertStatus(401) // Check for Unauthorized status code
        ->assertJson([
            'message' => 'Unauthorized',
            'success' => false,
        ]);
});

test('companies login validation', function () {
    $tenant = Tenant::where('id', 'tenant2')->first();
    $baseUrlWithoutProtocol = str_replace('http://', '', route('api.company.login'));
    $tenantUrl = "http://$tenant->id.$baseUrlWithoutProtocol";
    $response = $this->post($tenantUrl, []);
    $response->assertStatus(401);
});

test('companies can authenticate using the logout api', function () {
    $tenant = Tenant::where('id', 'tenant2')->first();
    $user = User::where('id', '3')->first();
    Sanctum::actingAs($user);
    $baseUrlWithoutProtocol = str_replace('http://', '', route('api.company.logout'));
    $tenantUrl = "http://$tenant->id.$baseUrlWithoutProtocol";
    $response = $this->post($tenantUrl);
    $response->assertStatus(200)
        ->assertJson([
            'message' => 'logout success',
            'success' => true,
        ]);
});


test('companies can not authenticate to other tenant using the login api', function () {
    $tenant = Tenant::where('id', 'tenant1')->first();
    $user = User::where('id', '3')->first();
    $baseUrlWithoutProtocol = str_replace('http://', '', route('api.company.login'));
    $tenantUrl = "http://$tenant->id.$baseUrlWithoutProtocol";
    $response = $this->post($tenantUrl, [
        'email' => $user->email,
        'password' => 'ap123456',
    ]);
    $response->assertStatus(401) // Check for Unauthorized status code
        ->assertJson([
            'message' => 'Unauthorized',
            'success' => false,
        ]);
});

