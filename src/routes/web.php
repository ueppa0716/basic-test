<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AttendanceManagementController;
use App\Http\Controllers\RegisteredUserController;
use App\Http\Controllers\AuthenticatedSessionController;
use Illuminate\Foundation\Auth\EmailVerificationRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Auth;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

// 認証済みユーザーのためのルート
Route::middleware(['auth', 'verified'])->group(function () {
    Route::get('/', [AttendanceManagementController::class, 'index']);
    Route::post('/work_start', [AttendanceManagementController::class, 'work_start']);
    Route::post('/work_end', [AttendanceManagementController::class, 'work_end']);
    Route::post('/break_start', [AttendanceManagementController::class, 'break_start']);
    Route::post('/break_end', [AttendanceManagementController::class, 'break_end']);
    Route::get('/attendance', [AttendanceManagementController::class, 'search']);
    Route::get('/search', [AttendanceManagementController::class, 'search']);
    Route::get('/profile', function () {
        // 確認済みのユーザーのみがこのルートにアクセス可能
    });
});

// コメントアウトされたルート（必要に応じて使用）
/*
Route::get('/email/verify', function () {
    return view('auth.verify-email');
})->middleware('auth')->name('verification.notice');

Route::get('/email/verify/{id}/{hash}', [AuthenticatedSessionController::class, 'verify'])
    ->middleware(['auth', 'signed'])
    ->name('verification.verify');

Route::post('/email/verification-notification', function (Request $request) {
    $request->user()->sendEmailVerificationNotification();
    return back()->with('message', 'Verification link sent!');
})->middleware(['auth', 'throttle:6,1'])->name('verification.send');

// テストメール送信ルート
Route::get('/test-email', function () {
    Mail::raw('This is a test email.', function ($message) {
        $message->to('test@example.com')
            ->subject('Test Email');
    });

    return 'Email sent!';
});
Auth::routes(['verify' => true]);
Route::get('/register', [RegisteredUserController::class, 'create']);
Route::post('/register', [RegisteredUserController::class, 'store']);
Route::get('/login', [AuthenticatedSessionController::class, 'store']);
*/