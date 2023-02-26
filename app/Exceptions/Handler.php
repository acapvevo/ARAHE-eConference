<?php

namespace App\Exceptions;

use Throwable;
use Psr\Log\LogLevel;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class Handler extends ExceptionHandler
{
    /**
     * A list of exception types with their corresponding custom log levels.
     *
     * @var array<class-string<\Throwable>, \Psr\Log\LogLevel::*>
     */
    protected $levels = [
        //
    ];

    /**
     * A list of the exception types that are not reported.
     *
     * @var array<int, class-string<\Throwable>>
     */
    protected $dontReport = [
        //
    ];

    /**
     * A list of the inputs that are never flashed to the session on validation exceptions.
     *
     * @var array<int, string>
     */
    protected $dontFlash = [
        'current_password',
        'password',
        'password_confirmation',
    ];

    /**
     * Register the exception handling callbacks for the application.
     *
     * @return void
     */
    public function register()
    {
        $this->reportable(function (Throwable $e) {
            //
        });
    }

    public function render($request, $e)
    {
        if ($e instanceof ModelNotFoundException) {
            $e = new NotFoundHttpException($e->getMessage(), $e);
        }

        if ($e instanceof \Illuminate\Session\TokenMismatchException) {
            $route = '/';

            if($request->is('admin*')) {
                $route = route('admin.login');
            }
            else if($request->is('participant*')) {
                $route = route('participant.login');
            }
            else if($request->is('reviewer*')) {
                $route = route('reviewer.login');
            }

            return redirect($route)->with('error', __('auth.tokenCSRF'));
        }

        return parent::render($request, $e);
    }
}
