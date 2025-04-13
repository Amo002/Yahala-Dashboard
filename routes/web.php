<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\WelcomeController;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\MerchantController;
use App\Http\Controllers\Admin\RoleController;
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
        Route::post('/merchants', [MerchantController::class, 'store'])->name('merchants.store');

        //users
        Route::get('/users/{user}/manage', [UserController::class, 'manage'])->name('users.manage');
        Route::post('/users/{user}/assign-role', [UserController::class, 'assignRole'])->name('users.assignRole');
        Route::put('/users/{user}', [UserController::class, 'update'])->name('users.update');
        Route::delete('/users/{user}/unassign-role', [UserController::class, 'unassignRole'])->name('users.unassignRole');



        // Roles
        Route::get('roles', [RoleController::class, 'index'])->name('roles.index');
        Route::post('/roles', [RoleController::class, 'store'])->name('roles.store');
        Route::get('/roles/{role}/manage', [RoleController::class, 'manage'])->name('roles.manage');
        Route::post('/roles/{role}/permissions', [RoleController::class, 'assignPermissions'])->name('roles.assignPermissions');
        Route::put('/roles/{role}', [RoleController::class, 'update'])->name('roles.update');
        Route::delete('/roles/{role}', [RoleController::class, 'destroy'])->name('roles.destroy');
    });


    Route::post(' /logout', [LogoutController::class, 'destroy'])->name('logout')->middleware('auth');
});
