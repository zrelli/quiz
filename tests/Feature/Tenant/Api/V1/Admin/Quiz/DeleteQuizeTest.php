<?php

use App\Models\Tenant;
use App\Models\User;
use Illuminate\Support\Facades\Log;
use Laravel\Sanctum\Sanctum;
use Illuminate\Testing\Fluent\AssertableJson;

test('quizzes can delete quiz details', function () {
    $tenant = Tenant::where('id', 'tenant1')->first();
    $user = User::where('id', '2')->first();
    Sanctum::actingAs($user);
    $baseUrlWithoutProtocol = str_replace('http://', '', route('api.company.quizzes.destroy', 1));
    $tenantUrl = "http://$tenant->id.$baseUrlWithoutProtocol";
    $response = $this->delete($tenantUrl);
    $response->assertStatus(200)
        ->assertJson([
            'message' => 'quiz deleted successfully',
            'success' => true,
        ]);
});



test('quizzes delete Resource not found for other companies', function () {
    $tenant = Tenant::where('id', 'tenant1')->first();
    $user = User::where('id', '2')->first();
    Sanctum::actingAs($user);
    $baseUrlWithoutProtocol = str_replace('http://', '', route('api.company.quizzes.destroy', 11));
    $tenantUrl = "http://$tenant->id.$baseUrlWithoutProtocol";
    $response = $this->delete($tenantUrl);
    $response->assertStatus(404)
        ->assertJson([
            'message' => 'Resource not found',
            'success' => false,
        ]);
});




