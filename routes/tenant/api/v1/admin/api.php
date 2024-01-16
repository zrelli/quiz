<?php
use App\Http\Controllers\Api\ChoiceController;
use App\Http\Controllers\Api\MemberQuizController;
use App\Http\Controllers\Api\QuestionController;
use App\Http\Controllers\Api\QuizController;
use App\Http\Controllers\Api\StatisticsController;
use App\Http\Controllers\Api\UserController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/
require __DIR__ . '/auth.php';
Route::middleware('auth:sanctum')->get('/profile', function (Request $request) {
    return $request->user();
});
Route::middleware(['isAdmin','auth:sanctum'])    ->group(function () {
    Route::resource('users', UserController::class);
    Route::resource('quizzes', QuizController::class);
    Route::get('quizzes/{quiz}/increase-quiz-attempts',[QuizController::class, 'increaseQuizAttempts']);
    Route::get('quizzes/{quiz}/close-quiz',[QuizController::class, 'closeQuiz']);
    //
    Route::resource('quizzes.questions', QuestionController::class);
    Route::resource('quizzes.questions.choices', ChoiceController::class);
    Route::resource('quizzes.online-exams', MemberQuizController::class)->except('update');
    Route::get('statistics',[StatisticsController::class,'index']);
});
