<?php

namespace App\Http\Controllers\Participant\Auth;

use App\Http\Controllers\Controller;
use App\Models\Participant;
use Illuminate\Auth\Events\Registered;
use Illuminate\Auth\Notifications\VerifyEmail;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\URL;

class RegisteredUserController extends Controller
{
    /**
     * Display the registration view.
     *
     * @return \Illuminate\View\View
     */
    public function create()
    {
        return view('participant.auth.register');
    }

    /**
     * Handle an incoming registration request.
     *
     * @param  \App\Http\Requests\LoginRequest  $request
     * @return \Illuminate\Http\RedirectResponse
     *
     * @throws \Illuminate\Validation\ValidationException
     */
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:participants',
            'telephoneNumber' => 'required|string|max:255|unique:App\Models\Participant,telephoneNumber',
            'password' => 'required|string|confirmed|min:8',
        ]);

        Auth::guard('participant')->login($participant = Participant::create([
            'name' => $request->name,
            'email' => $request->email,
            'telephoneNumber' => $request->telephoneNumber,
            'password' => Hash::make($request->password),
            'login_at' =>Carbon::now(),
        ]));

        VerifyEmail::createUrlUsing(function ($notifiable) {
            return URL::temporarySignedRoute(
                'participant.verification.verify',
                Carbon::now()->addMinutes(Config::get('auth.verification.expire', 60)),
                [
                    'id' => $notifiable->getKey(),
                    'hash' => sha1($notifiable->getEmailForVerification()),
                ]
            );
        });

        event(new Registered($participant));

        return redirect(route('participant.dashboard'));
    }
}
