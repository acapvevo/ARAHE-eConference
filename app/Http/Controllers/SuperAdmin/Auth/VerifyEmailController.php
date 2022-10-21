<?php

namespace App\Http\Controllers\SuperAdmin\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Auth\Events\Verified;
use Illuminate\Foundation\Auth\EmailVerificationRequest;

class VerifyEmailController extends Controller
{
    /**
     * Mark the authenticated super_admin's email address as verified.
     *
     * @param  \Illuminate\Foundation\Auth\EmailVerificationRequest  $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function __invoke(EmailVerificationRequest $request)
    {
        if ($request->user('super_admin')->hasVerifiedEmail()) {
            return redirect()->intended(route('super_admin.dashboard').'?verified=1');
        }

        if ($request->user('super_admin')->markEmailAsVerified()) {
            event(new Verified($request->user('super_admin')));
        }

        return redirect()->intended(route('super_admin.dashboard').'?verified=1');
    }
}
