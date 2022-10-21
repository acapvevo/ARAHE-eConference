<?php

use App\Http\Controllers\Reviewer\Auth\AuthenticatedSessionController;
use App\Http\Controllers\Reviewer\Auth\ConfirmablePasswordController;
use App\Http\Controllers\Reviewer\Auth\EmailVerificationNotificationController;
use App\Http\Controllers\Reviewer\Auth\EmailVerificationPromptController;
use App\Http\Controllers\Reviewer\Auth\NewPasswordController;
use App\Http\Controllers\Reviewer\Auth\PasswordResetLinkController;
use App\Http\Controllers\Reviewer\Auth\RegisteredUserController;
use App\Http\Controllers\Reviewer\Auth\VerifyEmailController;
use App\Http\Controllers\Reviewer\DashboardController;
use Illuminate\Support\Facades\Route;

Route::prefix('reviewer')->name('reviewer.')->group(function () {

    Route::get('/', [DashboardController::class, 'index'])
        ->middleware('auth:reviewer');

    Route::get('/dashboard', [DashboardController::class, 'index'])
        ->middleware('auth:reviewer')
        ->name('dashboard');

    Route::get('/register', [RegisteredUserController::class, 'create'])
        ->middleware('guest:reviewer')
        ->name('register');

    Route::post('/register', [RegisteredUserController::class, 'store'])
        ->middleware('guest:reviewer');

    Route::get('/login', [AuthenticatedSessionController::class, 'create'])
        ->middleware('guest:reviewer')
        ->name('login');

    Route::post('/login', [AuthenticatedSessionController::class, 'store'])
        ->middleware('guest:reviewer');

    Route::get('/forgot-password', [PasswordResetLinkController::class, 'create'])
        ->middleware('guest:reviewer')
        ->name('password.request');

    Route::post('/forgot-password', [PasswordResetLinkController::class, 'store'])
        ->middleware('guest:reviewer')
        ->name('password.email');

    Route::get('/reset-password/{token}', [NewPasswordController::class, 'create'])
        ->middleware('guest:reviewer')
        ->name('password.reset');

    Route::post('/reset-password', [NewPasswordController::class, 'store'])
        ->middleware('guest:reviewer')
        ->name('password.update');

    Route::get('/verify-email', [EmailVerificationPromptController::class, '__invoke'])
        ->middleware('auth:reviewer')
        ->name('verification.notice');

    Route::get('/verify-email/{id}/{hash}', [VerifyEmailController::class, '__invoke'])
        ->middleware(['auth:reviewer', 'signed', 'throttle:6,1'])
        ->name('verification.verify');

    Route::post('/email/verification-notification', [EmailVerificationNotificationController::class, 'store'])
        ->middleware(['auth:reviewer', 'throttle:6,1'])
        ->name('verification.send');

    Route::get('/confirm-password', [ConfirmablePasswordController::class, 'show'])
        ->middleware('auth:reviewer')
        ->name('password.confirm');

    Route::post('/confirm-password', [ConfirmablePasswordController::class, 'store'])
        ->middleware('auth:reviewer');

    Route::post('/logout', [AuthenticatedSessionController::class, 'destroy'])
        ->middleware('auth:reviewer')
        ->name('logout');
});
