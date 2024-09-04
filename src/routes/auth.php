<?php

use App\Http\Controllers\Auth\AuthController;
use App\Http\Controllers\Auth\ForgotPasswordController;
use App\Http\Controllers\Auth\NewPasswordController;
use Illuminate\Support\Facades\Route;

Route::group(['prefix' => 'auth', 'as' => 'auth.'], function () {
    Route::post('/login', [AuthController::class, 'login'])->name('login');
    Route::get('/me', [AuthController::class, 'me'])->name('me');
    Route::post('/refresh-token', [AuthController::class, 'refresh'])->name('refresh');
    Route::get('/logout', [AuthController::class, 'logout'])->name('logout');
    Route::post('/forgot-password', [ForgotPasswordController::class, 'store'])->name('password.email');
    Route::put('/reset-password', [NewPasswordController::class, 'store'])->name('password.store');
    Route::post('/register', [AuthController::class, 'register'])->name('register');
});
