<?php

declare(strict_types=1);

use App\Http\Controllers\ProfileController;
use App\Livewire\Pages\MembereExamInvitation;
use App\Models\ExamInvitation;
use App\Models\MemberQuiz;
use Illuminate\Support\Facades\Route;
use Stancl\Tenancy\Middleware\InitializeTenancyByDomain;
use Stancl\Tenancy\Middleware\PreventAccessFromCentralDomains;
/*
|--------------------------------------------------------------------------
| Tenant Routes
|--------------------------------------------------------------------------
|
| Here you can register the tenant routes for your application.
| These routes are loaded by the TenantRouteServiceProvider.
|
| Feel free to customize them however you want. Good luck!
|
*/

Route::middleware([
    'web',
    InitializeTenancyByDomain::class,
    PreventAccessFromCentralDomains::class,
])->group(function () {
    // require __DIR__ . '/auth.php';
    // require __DIR__ . '/filament.php';
    Route::get('/', function () {
        // return 'This is your multi-tenant application. The id of the current tenant is ' . tenant('id');
        return view('app-overview-for-members');

    });
    // Route::get('/members/exam-invitation/{code}/accept', function ($code) {
    //     $parts = explode('___', $code);
    //     list($token, $id) = $parts;
    //     $invitation = ExamInvitation::find($id);
    //     if ($token == $invitation->token) {
    //         $invitation->is_accepted = true;
    //         $invitation->save();
    //         $data = ['member_id' => $invitation->member_id, 'quiz_id' => $invitation->quiz_id];
    //         $cc = MemberQuiz::create($data);
    //         return response()->json($cc);
    //     }
    // });

    // Route::get('/members/exam-invitation/{code}', function ($code) {
    //     return view('member-exam-invitation');

    // });


    Route::get('/members/exam-invitation/{code}', MembereExamInvitation::class)->name('members.exam-invitation');


    // Route::get('/members/exam-invitation/{code}/decline', function ($code) {
    //     $parts = explode('___', $code);
    //     list($token, $id) = $parts;
    //     $invitation = ExamInvitation::find($id);
    //     $invitation->is_accepted = false;
    //     $invitation->save();
    //     return response()->json(['sdfd' => 'scc']);
    // });
    Route::get('/dashboard', function () {
        return view('dashboard');
    })->middleware(['auth', 'verified'])->name('dashboard');
    Route::middleware('auth')->group(function () {
        Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
        Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
        Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
    });
});
Route::middleware([
    'api',
    InitializeTenancyByDomain::class,
    PreventAccessFromCentralDomains::class,
])->prefix('/api/v1')
    ->group(function () {
        // todo add admin middleware
        Route::prefix('/admin')->group((
            function () {
                require __DIR__ . '/tenant/api/v1/admin/api.php';
            }
        ));
        // todo add member middleware
        Route::prefix('/member')->group((
            function () {
                require __DIR__ . '/tenant/api/v1/member/api.php';
            }
        ));
    });
