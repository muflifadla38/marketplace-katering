<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\MenuController;
use App\Http\Controllers\ProfileController;
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

Route::controller(AuthController::class)->middleware('guest')->group(function () {
    Route::get('/', 'login')->name('auth.login');
    Route::post('/', 'authenticate')->name('auth.authenticate');

    Route::get('register/{role}', 'register')->name('auth.register');
    Route::post('register/{role}', 'store')->name('auth.store');
});

Route::middleware('auth')->group(function () {
    Route::post('logout', [AuthController::class, 'logout'])->name('auth.logout');

    Route::get('home', [DashboardController::class, 'index'])->name('dashboard.index');

    Route::controller(ProfileController::class)->group(function () {
        Route::get('profile', 'index')->name('profile.index');
        Route::put('profile', 'update')->name('profile.update');
    });
    Route::get('users', [ProfileController::class, 'users'])->name('profile.users');

    Route::middleware('role:1')->group(function () {
        Route::get('menus/table', [MenuController::class, 'table'])->name('menus.table');
        Route::resource('menus', MenuController::class)->except(['create', 'edit']);
    });
});
