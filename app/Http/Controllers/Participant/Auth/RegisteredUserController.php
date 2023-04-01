<?php

namespace App\Http\Controllers\Participant\Auth;

use App\Models\Address;
use App\Models\Contact;
use App\Models\Emergency;
use App\Rules\CheckState;
use App\Models\Institution;
use App\Models\Participant;
use App\Rules\CheckCountry;
use Illuminate\Http\Request;
use App\Rules\CheckUniversity;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\URL;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Auth\Events\Registered;
use Illuminate\Support\Facades\Config;
use Illuminate\Auth\Notifications\VerifyEmail;

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
            'account.name' => 'required|string|max:255',
            'account.title' => 'required|string|exists:participant_title,code',
            'account.date_of_birth' => 'required|date',
            'account.email' => 'required|string|email|max:255|unique:participants,email',
            'account.password' => 'required|string|confirmed|min:8',
            'institution.university' => [
                'string',
                'required',
                'max:255'
            ],
            'institution.faculty' => 'required|string|max:255',
            'institution.department' => 'nullable|string|max:255',
            'contact.phoneNumber' => 'required|string|max:255|unique:App\Models\Contact,phoneNumber',
            'contact.faxNumber' => 'nullable|string|max:255|unique:App\Models\Contact,faxNumber',
            'address.lineOne' => 'required|string|max:255',
            'address.lineTwo' => 'required|string|max:255',
            'address.lineThree' => 'nullable|string|max:255',
            'address.city' => 'required|string|max:255',
            'address.postcode' => 'required|string|max:255',
            'address.state' => [
                'string',
                'required',
                'max:255'
            ],
            'address.country' => [
                'string',
                'required',
                'max:255'
            ],
            'emergency.name' => 'required|string|max:255',
            'emergency.email' => 'required|string|email|max:255',
            'emergency.phoneNumber' => 'required|string|max:255',
            'g-recaptcha-response' => 'recaptcha'
        ]);

        $participant = new Participant([
            'name' => $request->account['name'],
            'title' => $request->account['title'],
            'date_of_birth' => $request->account['date_of_birth'],
            'email' => $request->account['email'],
            'password' => Hash::make($request->account['password']),
            'login_at' => Carbon::now(),
        ]);

        $participant->save();

        $participant->contact()->save(new Contact([
            'phoneNumber' => $request->contact['phoneNumber'],
            'faxNumber' => $request->contact['faxNumber'] ?? null,
        ]));

        $participant->institution()->save(new Institution([
            'name' => $request->institution['university'],
            'faculty' => $request->institution['faculty'],
            'department' => $request->institution['department'] ?? null,
        ]));

        $participant->address()->save(new Address([
            'lineOne' => $request->address['lineOne'],
            'lineTwo' => $request->address['lineTwo'],
            'lineThree' => $request->address['lineThree'] ?? null,
            'city' => $request->address['city'],
            'postcode' => $request->address['postcode'],
            'state' => $request->address['state'],
            'country' => $request->address['country'],
        ]));

        $participant->emergency()->save(new Emergency([
            'name' => $request->emergency['name'],
            'email' => $request->emergency['email'],
            'phoneNumber' => $request->emergency['phoneNumber'],
        ]));

        Auth::guard('participant')->login($participant);

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
