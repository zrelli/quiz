<?php
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
Route::middleware(['isMember','auth:sanctum'])    ->group(function () {
    Route::get('/profile', function (Request $request) {
        return $request->user();
    });
    Route::resource('users', UserController::class);
});
