<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\DashboardController;

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

Route::controller(AuthController::class)->middleware('guest')->group(function () {
    Route::get('/', 'login')->name('auth.login');
    Route::post('/', 'authenticate')->name('auth.authenticate');

    Route::get('register', 'register')->name('auth.register');
    Route::post('register', 'store')->name('auth.store');
});

Route::middleware('auth')->group(function () {
    Route::post('logout', [AuthController::class, 'logout'])->name('auth.logout');

    Route::get('dashboard', [DashboardController::class, 'index'])->name('dashboard.index');

    Route::controller(ProfileController::class)->group(function () {
        Route::get('profile', 'index')->name('profile.index');
        Route::put('profile', 'update')->name('profile.update');
    });

    // Route::resource('users', UserController::class)->except(['create', 'edit']);

    // Route::controller(ActivityLogController::class)->middleware('features:activity-log')->group(function () {
    //     Route::get('activity-logs/table', 'table')->name('activity-logs.table');
    //     Route::resource('activity-logs', ActivityLogController::class)->only(['index', 'show']);
    // });
});
