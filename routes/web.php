<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\WelcomeController;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\MerchantController;
use App\Http\Controllers\Admin\UserController;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Auth\LogoutController;


Route::middleware('no-cache')->group(function () {


    Route::get('/', [WelcomeController::class, 'index'])->name('welcome');

    Route::get('/login', [LoginController::class, 'show'])->name('login');
    Route::post('/login', [LoginController::class, 'store'])->name('login.store');

    Route::middleware(['auth'])->prefix('admin')->name('admin.')->group(function () {
        Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
        Route::get('/users', [UserController::class, 'index'])->name('users.index');
        Route::get('/merchants', [MerchantController::class, 'index'])->name('merchants.index');
        Route::post('/merchants', [\App\Http\Controllers\Admin\MerchantController::class, 'store'])->name('merchants.store');
    });


    Route::post('/logout', [LogoutController::class, 'destroy'])->name('logout')->middleware('auth');
});
