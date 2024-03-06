<?php
use App\Http\Controllers\Api\Member\MemberAuthController;
use Illuminate\Support\Facades\Route;
/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/
Route::post('/register', [MemberAuthController::class, 'register'])->name('api.member.register');
Route::post('/login', [MemberAuthController::class, 'login'])->name('api.member.login');
Route::middleware('auth:sanctum')->group(function () {
    Route::post('/logout', [MemberAuthController::class, 'logout'])->name('api.member.logout');
});
