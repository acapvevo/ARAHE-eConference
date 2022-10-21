<?php

namespace App\Http\Controllers\Participant\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Auth\Events\Verified;
use Illuminate\Foundation\Auth\EmailVerificationRequest;

class VerifyEmailController extends Controller
{
    /**
     * Mark the authenticated participant's email address as verified.
     *
     * @param  \Illuminate\Foundation\Auth\EmailVerificationRequest  $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function __invoke(EmailVerificationRequest $request)
    {
        if ($request->user('participant')->hasVerifiedEmail()) {
            return redirect()->intended(route('participant.dashboard').'?verified=1');
        }

        if ($request->user('participant')->markEmailAsVerified()) {
            event(new Verified($request->user('participant')));
        }

        return redirect()->intended(route('participant.dashboard').'?verified=1');
    }
}
