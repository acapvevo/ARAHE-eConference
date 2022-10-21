<?php

namespace App\Http\Controllers\Reviewer\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Auth\Events\Verified;
use Illuminate\Foundation\Auth\EmailVerificationRequest;

class VerifyEmailController extends Controller
{
    /**
     * Mark the authenticated reviewer's email address as verified.
     *
     * @param  \Illuminate\Foundation\Auth\EmailVerificationRequest  $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function __invoke(EmailVerificationRequest $request)
    {
        if ($request->user('reviewer')->hasVerifiedEmail()) {
            return redirect()->intended(route('reviewer.dashboard').'?verified=1');
        }

        if ($request->user('reviewer')->markEmailAsVerified()) {
            event(new Verified($request->user('reviewer')));
        }

        return redirect()->intended(route('reviewer.dashboard').'?verified=1');
    }
}
