<?php
use App\Models\Quiz;
use App\Models\Tenant;
use App\Models\User;
use Illuminate\Support\Facades\Log;
use Laravel\Sanctum\Sanctum;
use Illuminate\Testing\Fluent\AssertableJson;
test('quizzes can create quiz ', function () {
    $tenant = Tenant::where('id', 'tenant1')->first();
    $user = User::where('id', '2')->first();
    Sanctum::actingAs($user);
    $baseUrlWithoutProtocol = str_replace('http://', '', route('api.company.quizzes.store'));
    $tenantUrl = "http://$tenant->id.$baseUrlWithoutProtocol";
    $response = $this->post($tenantUrl, [
        'title' => 'new title...',
        'description' => 'new description...',
        'is_published' => true,
        'max_attempts'=>3,
        'test_type'=>'in_time',
        'duration'=>1,
        'started_at'=>'2024-01-16 12:30:00',
    ]);
    // Assert the response status code is 201 (Created)
    $response->assertStatus(201);

    // Assert the response JSON structure and content
    $response->assertJsonStructure([
        'data' => [
            'id',
            'title',
            'description',
            'slug',
            'max_attempts',
            'test_type',
            'is_published',
            'expired_at',
            'started_at',
            'created_at',
            'updated_at',
        ],
    ])->assertJson([
        'data' => [
            'title' => 'new title...',
            'description' => 'new description...',
            'max_attempts' => 3,
            'test_type' => 'in_time',
            'is_published' => true,
            'expired_at' => '2024-01-16 13:30:00',
            'started_at' => '2024-01-16 12:30:00',
        ],
    ]);

    // Check if the quiz was actually created in the database
    $this->assertDatabaseHas('quizzes', [
        'title' => 'new title...',
        'description' => 'new description...',
        'max_attempts' => 3,
        'test_type' => 'in_time',
        'is_published' => true,
        'expired_at' => '2024-01-16 13:30:00',
        'started_at' => '2024-01-16 12:30:00',
    ]);

});
