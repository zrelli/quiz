<?php

use App\Models\Tenant;
use App\Models\User;
use Illuminate\Support\Facades\Log;
use Laravel\Sanctum\Sanctum;
use Illuminate\Testing\Fluent\AssertableJson;

test('quizzes can get a list of quizzes', function () {

    $tenant = Tenant::where('id', 'tenant1')->first();
    $user = User::where('id', '2')->first();
    Sanctum::actingAs($user);
    $baseUrlWithoutProtocol = str_replace('http://', '', route('api.company.quizzes.index'));
    $tenantUrl = "http://$tenant->id.$baseUrlWithoutProtocol";
    $response = $this->get($tenantUrl);
    $response->assertStatus(200)
        ->assertJson(function (AssertableJson $json) {
            $json->has('data', 10, function ($json) {
                $json->whereType('id', 'integer');
                $json->whereType('title', 'string');
                $json->whereType('description', 'string');
                $json->whereType('slug', 'string');
                $json->whereType('max_attempts', 'integer');
                $json->whereType('test_type', 'string');
                $json->whereType('is_published', 'boolean');
                $json->whereType('expired_at', 'string');
                $json->whereType('started_at', 'string');
                $json->whereType('created_at', 'string');
                $json->whereType('updated_at', 'string');
            });
            $json->has('links');
            $json->has('meta');
        });
});

test('quizzes guest can not get a list of quizzes', function () {

    $tenant = Tenant::where('id', 'tenant1')->first();
    $baseUrlWithoutProtocol = str_replace('http://', '', route('api.company.quizzes.index'));
    $tenantUrl = "http://$tenant->id.$baseUrlWithoutProtocol";
    $response = $this->get($tenantUrl);
    $response->assertStatus(401) // Check for Unauthorized status code
        ->assertJson([
            'message' => 'Unauthenticated',
            'success' => false,
        ]);
});
