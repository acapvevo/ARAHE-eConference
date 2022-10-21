<?php

namespace App\Http\Controllers\Participant\Auth;

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
        return $request->user('participant')->hasVerifiedEmail()
                    ? redirect()->intended(route('participant.dashboard'))
                    : view('participant.auth.verify-email');
    }
}
