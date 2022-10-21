<?php

use App\Http\Controllers\Participant\Auth\AuthenticatedSessionController;
use App\Http\Controllers\Participant\Auth\ConfirmablePasswordController;
use App\Http\Controllers\Participant\Auth\EmailVerificationNotificationController;
use App\Http\Controllers\Participant\Auth\EmailVerificationPromptController;
use App\Http\Controllers\Participant\Auth\NewPasswordController;
use App\Http\Controllers\Participant\Auth\PasswordResetLinkController;
use App\Http\Controllers\Participant\Auth\RegisteredUserController;
use App\Http\Controllers\Participant\Auth\VerifyEmailController;
use App\Http\Controllers\Participant\DashboardController;
use Illuminate\Support\Facades\Route;

Route::prefix('participant')->name('participant.')->group(function () {

    Route::get('/', [DashboardController::class, 'index'])
        ->middleware('auth:participant');

    Route::get('/dashboard', [DashboardController::class, 'index'])
        ->middleware('auth:participant')
        ->name('dashboard');

    Route::get('/register', [RegisteredUserController::class, 'create'])
        ->middleware('guest:participant')
        ->name('register');

    Route::post('/register', [RegisteredUserController::class, 'store'])
        ->middleware('guest:participant');

    Route::get('/login', [AuthenticatedSessionController::class, 'create'])
        ->middleware('guest:participant')
        ->name('login');

    Route::post('/login', [AuthenticatedSessionController::class, 'store'])
        ->middleware('guest:participant');

    Route::get('/forgot-password', [PasswordResetLinkController::class, 'create'])
        ->middleware('guest:participant')
        ->name('password.request');

    Route::post('/forgot-password', [PasswordResetLinkController::class, 'store'])
        ->middleware('guest:participant')
        ->name('password.email');

    Route::get('/reset-password/{token}', [NewPasswordController::class, 'create'])
        ->middleware('guest:participant')
        ->name('password.reset');

    Route::post('/reset-password', [NewPasswordController::class, 'store'])
        ->middleware('guest:participant')
        ->name('password.update');

    Route::get('/verify-email', [EmailVerificationPromptController::class, '__invoke'])
        ->middleware('auth:participant')
        ->name('verification.notice');

    Route::get('/verify-email/{id}/{hash}', [VerifyEmailController::class, '__invoke'])
        ->middleware(['auth:participant', 'signed', 'throttle:6,1'])
        ->name('verification.verify');

    Route::post('/email/verification-notification', [EmailVerificationNotificationController::class, 'store'])
        ->middleware(['auth:participant', 'throttle:6,1'])
        ->name('verification.send');

    Route::get('/confirm-password', [ConfirmablePasswordController::class, 'show'])
        ->middleware('auth:participant')
        ->name('password.confirm');

    Route::post('/confirm-password', [ConfirmablePasswordController::class, 'store'])
        ->middleware('auth:participant');

    Route::post('/logout', [AuthenticatedSessionController::class, 'destroy'])
        ->middleware('auth:participant')
        ->name('logout');
});
