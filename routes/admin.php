<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\Payment\BillController;
use App\Http\Controllers\Admin\User\PictureController;
use App\Http\Controllers\Admin\User\ProfileController;
use App\Http\Controllers\Admin\User\SettingController;
use App\Http\Controllers\Admin\System\ManualController;
use App\Http\Controllers\Admin\User\PasswordController;
use App\Http\Controllers\Admin\Competition\FeeController;
use App\Http\Controllers\Admin\Member\ReviewerController;
use App\Http\Controllers\Admin\Auth\NewPasswordController;
use App\Http\Controllers\Admin\Auth\VerifyEmailController;
use App\Http\Controllers\Admin\Competition\FormController;
use App\Http\Controllers\Admin\Payment\CategoryController;
use App\Http\Controllers\Admin\Submission\PaperController;
use App\Http\Controllers\Admin\Submission\AssignController;
use App\Http\Controllers\Admin\Competition\RubricController;
use App\Http\Controllers\Admin\Member\ParticipantController;
use App\Http\Controllers\Admin\Auth\RegisteredUserController;
use App\Http\Controllers\Admin\Competition\PackageController;
use App\Http\Controllers\Admin\Auth\PasswordResetLinkController;
use App\Http\Controllers\Admin\Auth\ConfirmablePasswordController;
use App\Http\Controllers\Admin\Auth\AuthenticatedSessionController;
use App\Http\Controllers\Admin\Auth\EmailVerificationPromptController;
use App\Http\Controllers\Admin\Auth\EmailVerificationNotificationController;
use App\Http\Controllers\Admin\Competition\ExtraController;
use App\Http\Controllers\Admin\Competition\HotelController;
use App\Http\Controllers\Admin\Competition\RateController;
use App\Http\Controllers\Admin\Submission\RegistrationController;

Route::prefix('admin')->name('admin.')->group(function () {

    Route::middleware('guest:admin')->group(function () {
        // Route::get('/register', [RegisteredUserController::class, 'create'])
        //     ->name('register');

        // Route::post('/register', [RegisteredUserController::class, 'store']);

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

    Route::middleware('auth:admin')->group(function () {
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

        // Route::middleware('verified:admin.verification.notice')->group(function () {
        Route::get('/', [DashboardController::class, 'index'])->name('dashboard');

        Route::prefix('dashboard')->name('dashboard.')->group(function () {
            Route::get('', [DashboardController::class, 'index'])->name('index');
            Route::post('/statistic', [DashboardController::class, 'statistic'])->name('statistic');
        });

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
            Route::prefix('form')->name('form.')->group(function () {
                Route::get('', [FormController::class, 'list'])->name('list');
                Route::post('', [FormController::class, 'create'])->name('create');
                Route::get('/{id}', [FormController::class, 'view'])->name('view');
                Route::patch('/{id}', [FormController::class, 'update'])->name('update');
                Route::patch('/modify/{id}', [FormController::class, 'modify'])->name('modify');
            });

            Route::prefix('rubric')->name('rubric.')->group(function () {
                Route::post('', [RubricController::class, 'create'])->name('create');
                Route::get('/{id}', [RubricController::class, 'view'])->name('view');
                Route::patch('/{id}', [RubricController::class, 'update'])->name('update');
                Route::delete('', [RubricController::class, 'delete'])->name('delete');
            });

            Route::prefix('package')->name('package.')->group(function () {
                Route::get('/{form_id}', [PackageController::class, 'view'])->name('view');
                Route::patch('', [PackageController::class, 'update'])->name('update');
                Route::patch('/modify', [PackageController::class, 'modify'])->name('modify');
            });

            Route::prefix('fee')->name('fee.')->group(function () {
                Route::patch('/update', [FeeController::class, 'update'])->name('update');
                Route::patch('/modify', [FeeController::class, 'modify'])->name('modify');
            });

            Route::prefix('extra')->name('extra.')->group(function () {
                Route::post('', [ExtraController::class, 'create'])->name('create');
                Route::get('/{id}', [ExtraController::class, 'view'])->name('view');
                Route::patch('/{id}', [ExtraController::class, 'update'])->name('update');
                Route::delete('', [ExtraController::class, 'delete'])->name('delete');
            });

            Route::prefix('hotel')->name('hotel.')->group(function () {
                Route::patch('', [HotelController::class, 'update'])->name('update');
            });

            Route::prefix('rate')->name('rate.')->group(function () {
                Route::patch('', [RateController::class, 'update'])->name('update');
            });
        });

        Route::prefix('submission')->name('submission.')->group(function () {
            Route::prefix('registration')->name('registration.')->group(function () {
                Route::get('', [RegistrationController::class, 'list'])->name('list');
                Route::get('/{id}', [RegistrationController::class, 'view'])->name('view');
                Route::patch('/{id}', [RegistrationController::class, 'update'])->name('update');
                Route::post('/download', [RegistrationController::class, 'download'])->name('download');
            });

            Route::prefix('assign')->name('assign.')->group(function () {
                Route::get('', [AssignController::class, 'list'])->name('list');
                Route::get('/{id}', [AssignController::class, 'view'])->name('view');
                Route::patch('/{id}', [AssignController::class, 'update'])->name('update');
                Route::post('/download', [AssignController::class, 'download'])->name('download');
            });

            Route::prefix('paper')->name('paper.')->group(function () {
                Route::get('', [PaperController::class, 'list'])->name('list');
                Route::get('/{id}', [PaperController::class, 'view'])->name('view');
                Route::post('/download', [PaperController::class, 'download'])->name('download');
            });
        });

        Route::prefix('member')->name('member.')->group(function () {
            Route::prefix('participant')->name('participant.')->group(function () {
                Route::get('', [ParticipantController::class, 'list'])->name('list');
                Route::get('/{id}', [ParticipantController::class, 'view'])->name('view');
                Route::patch('/{id}', [ParticipantController::class, 'update'])->name('update');
                Route::post('/download', [ParticipantController::class, 'download'])->name('download');
            });

            Route::prefix('reviewer')->name('reviewer.')->group(function () {
                Route::get('', [ReviewerController::class, 'list'])->name('list');
                Route::get('/{id}', [ReviewerController::class, 'view'])->name('view');
                Route::patch('/{id}', [ReviewerController::class, 'update'])->name('update');
            });
        });


        Route::prefix('payment')->name('payment.')->group(function () {
            Route::prefix('bill')->name('bill.')->group(function () {
                Route::get('', [BillController::class, 'list'])->name('list');
                Route::get('/{id}', [BillController::class, 'view'])->name('view');
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
