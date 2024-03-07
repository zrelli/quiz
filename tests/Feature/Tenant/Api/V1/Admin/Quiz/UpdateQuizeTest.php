<?php
use App\Models\Quiz;
use App\Models\Tenant;
use App\Models\User;
use Illuminate\Support\Facades\Log;
use Laravel\Sanctum\Sanctum;
use Illuminate\Testing\Fluent\AssertableJson;
test('quizzes can update quiz details', function () {
    $tenant = Tenant::where('id', 'tenant1')->first();
    $user = User::where('id', '2')->first();
    Sanctum::actingAs($user);
    $baseUrlWithoutProtocol = str_replace('http://', '', route('api.company.quizzes.update', 1));
    $tenantUrl = "http://$tenant->id.$baseUrlWithoutProtocol";
    $response = $this->put($tenantUrl, [
        'title' => 'updated title',
        'description' => 'updated description',
        'is_published' => true,
    ]);
    $response->assertStatus(200)
        ->assertJson([
            'message' => 'quiz updated successfully',
            'success' => true,
        ]);
    // Assert the updated values
    $updateQuiz = Quiz::find(1);
    $this->assertEquals('updated title', $updateQuiz->title);
    $this->assertEquals('updated description', $updateQuiz->description);
    $this->assertEquals(true, $updateQuiz->is_published);
});
