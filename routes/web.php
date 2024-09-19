<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\HomeController;
use Illuminate\Support\Facades\Route;

Route::get('/', [HomeController::class, 'index'])->name('home');

Route::middleware('guest')->group(function () {
    Route::get('/login', [AuthController::class, 'login'])->name('login');
    Route::post('/login', [AuthController::class, 'authenticate'])->middleware('throttle:auth')->name('authenticate');
    Route::get('/signup', [AuthController::class, 'signup'])->name('signup');
    Route::post('/signup', [AuthController::class, 'register'])->middleware('throttle:auth')->name('register');
    Route::get('/forgot-password', [AuthController::class, 'forgotPassword'])->name('password.forgot');
    Route::post('/forgot-password', [AuthController::class, 'sendResetLinkEmail'])->name('password.request');
    Route::get('/reset-password/{token}', [AuthController::class, 'resetPassword'])->name('password.reset');
    Route::post('/reset-password', [AuthController::class, 'updatePassword'])->name('password.update');
});

Route::middleware('auth')->group(function () {
    Route::delete('/logout', [AuthController::class, 'logout'])->name('logout');
});


Route::get('/auth/socialite/github', [AuthController::class, 'github'])->name('socialite.github');
Route::get('/auth/socialite/github/callback', [AuthController::class, 'githubCallback'])->name('socialite.github.callback');
