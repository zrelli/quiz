<?php
use App\Http\Controllers\Api\Member\MemberController;
use App\Http\Controllers\Api\Member\MemberExamQuestionController;
use App\Http\Controllers\Api\Member\MemberQuizController;
use App\Http\Controllers\Api\Member\MemberStatisticsController;
use App\Http\Controllers\Api\Member\QuizController;
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
Route::middleware(['auth:sanctum'])->group(function () {
    Route::get('/profile', function (Request $request) {
        return $request->user();
    });
    Route::resource('members', MemberController::class)->only(['index', 'show']);
    Route::resource('quizzes', QuizController::class);
    Route::get('quizzes/{quiz}/subscribe', [QuizController::class, 'subscribeToQuiz']);
    Route::resource('online-exams', MemberQuizController::class)->only(['index', 'show']);
    Route::get('online-exams/{online_exam}/start-exam', [MemberQuizController::class, 'startExam']);
    Route::get('online-exams/{online_exam}/last-test-statistics', [MemberQuizController::class, 'lastTestStatistic']);
    Route::resource('online-exams/{online_exam}/questions', MemberExamQuestionController::class)->only(['index', 'show'])->names([
        'index' => 'online-exams.questions.index',
        'show' => 'online-exams.questions.show',
    ]);
    Route::post('online-exams/{online_exam}/questions/{question}/answer-question', [MemberQuizController::class, 'answerQuestion']);
    Route::get('statistics', [MemberStatisticsController::class, 'index']);
});
