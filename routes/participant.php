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
use App\Http\Controllers\Participant\Competition\PackageController;
use App\Http\Controllers\Participant\Competition\RegistrationController;
use App\Http\Controllers\Participant\Competition\SubmissionController;
use App\Http\Controllers\Participant\Payment\PayController;
use App\Http\Controllers\Participant\Payment\RecordController;

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

        //User Management Routes
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

        Route::prefix('competition')->name('competition.')->group(function () {
            Route::prefix('registration')->name('registration.')->group(function () {
                Route::get('', [RegistrationController::class, 'list'])->name('list');
                Route::get('/{form_id}', [RegistrationController::class, 'view'])->name('view');
                Route::post('', [RegistrationController::class, 'create'])->name('create');
                Route::get('/category/{id}', [RegistrationController::class, 'category'])->name('category');
                Route::post('/category', [RegistrationController::class, 'categories'])->name('categories');
                Route::post('/participants', [RegistrationController::class, 'participants'])->name('participants');
                Route::post('/participant', [RegistrationController::class, 'participant'])->name('participant');
                Route::patch('/{id}', [RegistrationController::class, 'update'])->name('update');
                Route::post('/download', [RegistrationController::class, 'download'])->name('download');
            });

            Route::prefix('package')->name('package.')->group(function () {
                Route::post('', [PackageController::class, 'create'])->name('create');
                Route::post('/fee', [PackageController::class, 'fee'])->name('fee');
                Route::patch('', [PackageController::class, 'update'])->name('update');
            });

            Route::prefix('submission')->name('submission.')->group(function () {
                Route::get('', [SubmissionController::class, 'list'])->name('list');
                Route::get('/{registration_id}', [SubmissionController::class, 'view'])->name('view');
                Route::post('', [SubmissionController::class, 'create'])->name('create');
                Route::patch('/{id}', [SubmissionController::class, 'update'])->name('update');
                Route::post('/download', [SubmissionController::class, 'download'])->name('download');
            });
        });

        Route::prefix('payment')->name('payment.')->group(function () {
            Route::prefix('pay')->name('pay.')->group(function () {
                Route::post('', [PayController::class, 'main'])->name('main');
                Route::get('/success', [PayController::class, 'success'])->name('success');
                Route::get('/cancel', [PayController::class, 'cancel'])->name('cancel');
                // Route::get('/review/{id}', [PayController::class, 'review'])->name('review');
            });

            Route::prefix('record')->name('record.')->group(function () {
                Route::get('', [RecordController::class, 'list'])->name('list');
                Route::get('/{id}', [RecordController::class, 'view'])->name('view');
                Route::post('/receipt', [RecordController::class, 'receipt'])->name('receipt');
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
