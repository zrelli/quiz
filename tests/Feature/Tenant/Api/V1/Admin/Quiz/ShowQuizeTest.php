<?php
use App\Models\Tenant;
use App\Models\User;
use Illuminate\Support\Facades\Log;
use Laravel\Sanctum\Sanctum;
use Illuminate\Testing\Fluent\AssertableJson;
test('quizzes can show quiz details', function () {
    $tenant = Tenant::where('id', 'tenant1')->first();
    $user = User::where('id', '2')->first();
    Sanctum::actingAs($user);
    $baseUrlWithoutProtocol = str_replace('http://', '', route('api.company.quizzes.show', 1));
    $tenantUrl = "http://$tenant->id.$baseUrlWithoutProtocol";
    $response = $this->get($tenantUrl);
    $response->assertStatus(200)
        ->assertJson(function (AssertableJson $json) {
            $json->has('data',  function ($json) {
                $json->whereType('id', 'integer');
                $json->whereType('title', 'string');
                $json->whereType('description', 'string');
                $json->whereType('slug', 'string');
                $json->whereType('max_attempts', 'integer');
                $json->whereType('test_type', 'string');
                $json->whereType('is_published', 'boolean');
                $json->whereType('is_public', 'boolean');
                $json->whereType('expired_at', 'string');
                $json->whereType('started_at', 'string');
                $json->whereType('created_at', 'string');
                $json->whereType('updated_at', 'string');
            });
        });
});
