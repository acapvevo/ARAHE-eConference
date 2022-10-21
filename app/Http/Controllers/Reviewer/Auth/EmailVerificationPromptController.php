<?php

namespace App\Http\Controllers\Reviewer\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class EmailVerificationPromptController extends Controller
{
    /**
     * Display the email verification prompt.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return mixed
     */
    public function __invoke(Request $request)
    {
        return $request->user('reviewer')->hasVerifiedEmail()
                    ? redirect()->intended(route('reviewer.dashboard'))
                    : view('reviewer.auth.verify-email');
    }
}
