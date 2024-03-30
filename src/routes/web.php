<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AttendanceManagementController;
use App\Http\Controllers\RegisteredUserController;
use App\Http\Controllers\AuthenticatedSessionController;

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

Route::middleware('auth')->group(function () {
    Route::get('/', [AttendanceManagementController::class, 'index']);
    Route::post('/work_start', [AttendanceManagementController::class, 'work_start']);
    Route::post('/work_end', [AttendanceManagementController::class, 'work_end']);
    Route::post('/break_start', [AttendanceManagementController::class, 'break_start']);
    Route::post('/break_end', [AttendanceManagementController::class, 'break_end']);
    Route::get('/attendance/{id}', [AttendanceManagementController::class, 'search']);
});

// Route::get('/register', [RegisteredUserController::class, 'create']);
// Route::post('/register', [RegisteredUserController::class, 'store']);

// Route::get('/login', [AuthenticatedSessionController::class, 'store']);
