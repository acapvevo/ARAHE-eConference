<?php

namespace App\Http\Controllers\SuperAdmin\Auth;

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
        return $request->user('super_admin')->hasVerifiedEmail()
                    ? redirect()->intended(route('super_admin.dashboard'))
                    : view('super_admin.auth.verify-email');
    }
}
