<?php

use App\Http\Controllers\Auth\ForgotPasswordController;
use App\Http\Controllers\Auth\ResetPasswordController;
use App\Http\Controllers\Auth\SignInController;
use App\Http\Controllers\Auth\SignUpController;
use App\Http\Controllers\Auth\SocialAuthController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\ThumbnailController;
use Illuminate\Support\Facades\Route;

Route::get('/', HomeController::class)->name('home');

Route::get('/storage/images/{dir}/{method}/{size}/{file}', ThumbnailController::class)
    ->where('method', 'resize|crop|fit')
    ->where('size', '\d+x\d+')
    ->where('file', '.+\.(jpg|jpeg|png|gif|webp)$')
    ->name('thumbnail');

Route::middleware('guest')->group(function () {
    Route::get('/login', [SignInController::class, 'page'])->name('login');
    Route::post('/login', [SignInController::class, 'handle'])->middleware('throttle:auth')->name('authenticate');
    Route::get('/signup', [SignUpController::class, 'page'])->name('signup');
    Route::post('/signup', [SignUpController::class, 'handle'])->middleware('throttle:auth')->name('register');
    Route::get('/forgot-password', [ForgotPasswordController::class, 'page'])->name('password.forgot');
    Route::post('/forgot-password', [ForgotPasswordController::class, 'handle'])->name('password.request');
    Route::get('/reset-password/{token}', [ResetPasswordController::class, 'page'])->name('password.reset');
    Route::post('/reset-password', [ResetPasswordController::class, 'handle'])->name('password.update');
});

Route::middleware('auth')->group(function () {
    Route::delete('/logout', [SignInController::class, 'logout'])->name('logout');
});


Route::get('/auth/socialite/github', [SocialAuthController::class, 'redirect'])->name('socialite.github');
Route::get('/auth/socialite/github/callback', [SocialAuthController::class, 'callback'])->name('socialite.github.callback');
