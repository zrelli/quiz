<?php
use App\Http\Controllers\Api\Member\MemberChoiceController;
use App\Http\Controllers\Api\Member\MemberExamController;
use App\Http\Controllers\Api\Member\MemberQuestionController;
use App\Http\Controllers\Api\Member\MemberQuizController;
use App\Http\Controllers\Api\Member\MemberStatisticsController;
use App\Http\Controllers\Api\Member\MemberUserController;
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
Route::middleware(['isMember', 'auth:sanctum'])->group(function () {
    Route::get('/profile', function (Request $request) {
        return $request->user();
    });
    Route::resource('users', MemberUserController::class)->only(['index', 'show']);
    Route::resource('quizzes', MemberQuizController::class);
    Route::get('quizzes/{quiz}/subscribe', [MemberQuizController::class, 'subscribeToQuiz']);
    Route::resource('online-exams', MemberExamController::class)->only(['index', 'show']);
    Route::get('online-exams/{online_exam}/start-exam', [MemberExamController::class, 'startExam']);
    Route::post('online-exams/{online_exam}/answer-question', [MemberExamController::class, 'answerQuestion']);
    Route::get('online-exams/{online_exam}/last-test-statistics', [MemberExamController::class, 'lastTestStatistic']);
    Route::get('statistics', [MemberStatisticsController::class, 'index']);
});
