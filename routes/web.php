<?php
use App\Http\Controllers\RequestCompanyAccountController;
use Illuminate\Support\Facades\Route;
/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/
Route::get('/', function () {
    return view('app-overview');
})->name('home-page');
Route::get('/request-company-account', [RequestCompanyAccountController::class, 'index'])->name('request-company-account');
Route::post('/request-company-account', [RequestCompanyAccountController::class, 'saveCompanyAccountRequest'])->name('save-request-company-account');
