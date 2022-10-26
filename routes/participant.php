<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Participant\DashboardController;
use App\Http\Controllers\Participant\User\PictureController;
use App\Http\Controllers\Participant\User\ProfileController;
use App\Http\Controllers\Participant\User\SettingController;
use App\Http\Controllers\Participant\System\ManualController;
use App\Http\Controllers\Participant\User\PasswordController;
use App\Http\Controllers\Participant\Auth\NewPasswordController;
use App\Http\Controllers\Participant\Auth\VerifyEmailController;
use App\Http\Controllers\Participant\Auth\RegisteredUserController;
use App\Http\Controllers\Participant\Auth\PasswordResetLinkController;
use App\Http\Controllers\Participant\Auth\ConfirmablePasswordController;
use App\Http\Controllers\Participant\Auth\AuthenticatedSessionController;
use App\Http\Controllers\Participant\Auth\EmailVerificationPromptController;
use App\Http\Controllers\Participant\Auth\EmailVerificationNotificationController;

Route::prefix('participant')->name('participant.')->group(function () {

    Route::middleware('guest:participant')->group(function () {
        Route::get('/register', [RegisteredUserController::class, 'create'])
            ->name('register');

        Route::post('/register', [RegisteredUserController::class, 'store']);

        Route::get('/login', [AuthenticatedSessionController::class, 'create'])
            ->name('login');

        Route::post('/login', [AuthenticatedSessionController::class, 'store']);

        Route::get('/forgot-password', [PasswordResetLinkController::class, 'create'])
            ->name('password.request');

        Route::post('/forgot-password', [PasswordResetLinkController::class, 'store'])
            ->name('password.email');

        Route::get('/reset-password/{token}', [NewPasswordController::class, 'create'])
            ->name('password.reset');

        Route::post('/reset-password', [NewPasswordController::class, 'store'])
            ->name('password.update');
    });

    Route::middleware('auth:participant')->group(function () {
        Route::get('/verify-email', [EmailVerificationPromptController::class, '__invoke'])
            ->name('verification.notice');

        Route::get('/verify-email/{id}/{hash}', [VerifyEmailController::class, '__invoke'])
            ->middleware(['signed', 'throttle:6,1'])
            ->name('verification.verify');

        Route::post('/email/verification-notification', [EmailVerificationNotificationController::class, 'store'])
            ->middleware(['throttle:6,1'])
            ->name('verification.send');

        Route::get('/confirm-password', [ConfirmablePasswordController::class, 'show'])
            ->name('password.confirm');

        Route::post('/confirm-password', [ConfirmablePasswordController::class, 'store']);

        Route::post('/logout', [AuthenticatedSessionController::class, 'destroy'])
            ->name('logout');

        // Route::middleware('verified:participant.verification.notice')->group(function () {
        Route::get('/', [DashboardController::class, 'index']);

        Route::get('/dashboard', [DashboardController::class, 'index'])
            ->name('dashboard');

        //Pengurusan Pengguna Routes
        Route::prefix('user')->name('user.')->group(function () {

            //Profile
            Route::prefix('profile')->name('profile.')->group(function () {
                Route::get('', [ProfileController::class, 'view'])->name('view');
                Route::patch('', [ProfileController::class, 'update'])->name('update');
            });

            //Setting
            Route::prefix('setting')->name('setting.')->group(function () {
                Route::get('', [SettingController::class, 'view'])->name('view');
            });

            //Password
            Route::prefix('password')->name('password.')->group(function () {
                Route::patch('', [PasswordController::class, 'update'])->name('update');
            });

            //Profile Picture
            Route::prefix('picture')->name('picture.')->group(function () {
                Route::patch('', [PictureController::class, 'update'])->name('update');
                Route::get('', [PictureController::class, 'show'])->name('show');
            });
        });

        Route::prefix('system')->name('system.')->group(function () {
            Route::prefix('manual')->name('manual.')->group(function () {
                Route::get('', [ManualController::class, 'view'])->name('view');
                Route::get('/stream/{id}', [ManualController::class, 'stream'])->name('stream');
            });
        });
        // });
    });
});
