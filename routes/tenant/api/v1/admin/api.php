<?php

use App\Http\Controllers\Api\Admin\ChoiceController;
use App\Http\Controllers\Api\Admin\MemberQuizController;
use App\Http\Controllers\Api\Admin\QuestionController;
use App\Http\Controllers\Api\Admin\QuizController;
use App\Http\Controllers\Api\Admin\StatisticsController;
use App\Http\Controllers\Api\Admin\MemberController;
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
Route::middleware(['isAdmin', 'auth:sanctum'])->group(function () {

    // MEMBERS
    Route::resource('members', MemberController::class);
    // QUIZZES
    Route::resource('quizzes', QuizController::class)->names('api.company.quizzes');
    Route::get('quizzes/{quiz}/toggle-quiz-publishing', [QuizController::class, 'toggleQuizPublishing']);
    Route::get('quizzes/{quiz}/increase-quiz-attempts', [QuizController::class, 'increaseQuizAttempts']);
    Route::post('quizzes/{quiz}/invite-members', [QuizController::class, 'inviteMembers']);
    Route::post('quizzes/{quiz}/remind-members', [QuizController::class, 'remindMembers']);
    Route::post('quizzes/{quiz}/send-exam-results', [QuizController::class, 'sendExamResults']);
    // Route::get('quizzes/{quiz}/close-quiz', [QuizController::class, 'closeQuiz']);
    // Route::post('quizzes/{quiz}/subscribe-member-to-quiz', [QuizController::class, 'subscribeMemberToQuiz']);
    //QUESTIONS
    Route::resource('quizzes.questions', QuestionController::class);
    //CHOICES
    Route::resource('quizzes.questions.choices', ChoiceController::class);
    //EXAMS
    Route::resource('quizzes.online-exams', MemberQuizController::class)->only(['index']);
    //STATISTICS
    Route::get('statistics', [StatisticsController::class, 'index']);
});
