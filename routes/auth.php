<?php

use App\Http\Controllers\Auth\VerifyEmailController;
use Illuminate\Support\Facades\Route;
use Livewire\Volt\Volt;

Route::middleware('guest')->group(function () {
    Volt::route('register', 'pages.auth.register')
        ->name('register');

    Volt::route('login', 'pages.auth.login')
        ->name('login');

    Volt::route('forgot-password', 'pages.auth.forgot-password')
        ->name('password.request');

    Volt::route('reset-password/{token}', 'pages.auth.reset-password')
        ->name('password.reset');
});

use App\Http\Controllers\Auth\RegisterController;
use App\Http\Controllers\Auth\LoginController;

// POST handlers for register and login (Volt handles the GET pages)
Route::middleware('guest')->group(function () {
    Route::post('register', [RegisterController::class, 'register'])->name('register.post');
    Route::post('login', [LoginController::class, 'login'])->name('login.post');
});

Route::post('logout', [LoginController::class, 'logout'])->name('logout');

Route::middleware('auth')->group(function () {
    Volt::route('verify-email', 'pages.auth.verify-email')
        ->name('verification.notice');

    Route::get('verify-email/{id}/{hash}', VerifyEmailController::class)
        ->middleware(['signed', 'throttle:6,1'])
        ->name('verification.verify');

    Volt::route('confirm-password', 'pages.auth.confirm-password')
        ->name('password.confirm');
});
