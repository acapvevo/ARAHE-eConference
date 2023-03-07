<?php

namespace App\Http\Controllers\Participant\User;

use App\Rules\CheckState;
use App\Rules\CheckCountry;
use Illuminate\Http\Request;
use App\Rules\CheckUniversity;
use Illuminate\Validation\Rule;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;

class ProfileController extends Controller
{
    public function view()
    {
        $user = Auth::guard('participant')->user();

        return view('participant.user.profile')->with('user', $user);
    }

    public function update(Request $request)
    {
        $user = Auth::guard('participant')->user();

        $request->validate([
            'account.name' => [
                'required',
                'string',
                Rule::unique('participants', 'name')->ignore($user->id),
            ],
            'account.email' => [
                'required',
                'email',
                'string',
                Rule::unique('participants', 'email')->ignore($user->id),
            ],
            'account.title' => 'required|string|exists:participant_title,code',
            'account.date_of_birth' => 'required|date',
            'institution.university' => [
                'required',
                'string',
                'max:255'
            ],
            'institution.faculty' => 'required|string|max:255',
            'institution.department' => 'nullable|string|max:255',
            'contact.phoneNumber' => [
                'required',
                'string',
                'max:255',
                Rule::unique('contacts', 'phoneNumber')->ignore($user->contact->id),
            ],
            'contact.faxNumber' => [
                'nullable',
                'string',
                'max:255',
                Rule::unique('contacts', 'faxNumber')->ignore($user->contact->id),
            ],
            'address.lineOne' => 'required|string|max:255',
            'address.lineTwo' => 'required|string|max:255',
            'address.lineThree' => 'nullable|string|max:255',
            'address.city' => 'required|string|max:255',
            'address.postcode' => 'required|string|max:255',
            'address.state' => [
                'required',
                'string',
                'max:255'
            ],
            'address.country' => [
                'required',
                'string',
                'max:255'
            ],
            'emergency.name' => 'required|string|max:255',
            'emergency.email' => 'required|string|email|max:255',
            'emergency.phoneNumber' => 'required|string|max:255',
        ]);

        $user->name = $request->account['name'];
        $user->title = $request->account['title'];
        $user->date_of_birth = $request->account['date_of_birth'];
        $user->email = $request->account['email'];

        $institution = $user->institution;

        $institution->name = $request->institution['university'];
        $institution->faculty = $request->institution['faculty'];
        $institution->department = $request->institution['department'] ?? null;

        $institution->save();

        $contact = $user->contact;

        $contact->phoneNumber = $request->contact['phoneNumber'];
        $contact->faxNumber = $request->contact['faxNumber'] ?? null;

        $contact->save();

        $address = $user->address;

        $address->lineOne = $request->address['lineOne'];
        $address->lineTwo = $request->address['lineTwo'];
        $address->lineThree = $request->address['lineThree'] ?? null;
        $address->city = $request->address['city'];
        $address->postcode = $request->address['postcode'];
        $address->state = $request->address['state'];
        $address->country = $request->address['country'];

        $address->save();

        $emergency = $user->emergency;

        $emergency->name = $request->emergency['name'];
        $emergency->email = $request->emergency['email'];
        $emergency->phoneNumber = $request->emergency['phoneNumber'];

        $emergency->save();

        if(isset($user->reviewer)){
            $user->reviewer->email = $request->account['email'];

            $user->reviewer->save();
        }

        $user->save();

        return redirect(route('participant.user.profile.view'))->with('success', 'Your User Profile Successfully updated');
    }
}
