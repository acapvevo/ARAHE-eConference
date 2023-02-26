<?php

namespace App\Http\Controllers\Reviewer\User;

use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;

class ProfileController extends Controller
{
    public function view()
    {
        $user = Auth::guard('reviewer')->user();

        return view('reviewer.user.profile')->with('user', $user);
    }

    public function update(Request $request)
    {
        $user = Auth::guard('reviewer')->user();

        $request->validate([
            'account.name' => [
                'required',
                'string',
                Rule::unique('participants', 'name')->ignore($user->participant->id),
            ],
            'account.email' => [
                'required',
                'email',
                'string',
                Rule::unique('participants', 'email')->ignore($user->participant->id),
            ],
            'account.title' => 'required|string|exists:participant_title,code',
            'account.date_of_birth' => 'required|date',
            'account.type' => 'required|string|exists:participant_type,code',
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
                Rule::unique('contacts', 'phoneNumber')->ignore($user->participant->contact->id),
            ],
            'contact.faxNumber' => [
                'nullable',
                'string',
                'max:255',
                Rule::unique('contacts', 'faxNumber')->ignore($user->participant->contact->id),
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

        $user->email = $request->account['email'];
        $user->save();

        $participant = $user->participant;

        $participant->name = $request->account['name'];
        $participant->title = $request->account['title'];
        $participant->date_of_birth = $request->account['date_of_birth'];
        $participant->type = $request->account['type'];
        $participant->email = $request->account['email'];

        $participant->save();

        $institution = $user->participant->institution;

        $institution->name = $request->institution['university'];
        $institution->faculty = $request->institution['faculty'];
        $institution->department = $request->institution['department'] ?? null;

        $institution->save();

        $contact = $user->participant->contact;

        $contact->phoneNumber = $request->contact['phoneNumber'];
        $contact->faxNumber = $request->contact['faxNumber'] ?? null;

        $contact->save();

        $address = $user->participant->address;

        $address->lineOne = $request->address['lineOne'];
        $address->lineTwo = $request->address['lineTwo'];
        $address->lineThree = $request->address['lineThree'] ?? null;
        $address->city = $request->address['city'];
        $address->postcode = $request->address['postcode'];
        $address->state = $request->address['state'];
        $address->country = $request->address['country'];

        $address->save();

        $emergency = $user->participant->emergency;

        $emergency->name = $request->emergency['name'];
        $emergency->email = $request->emergency['email'];
        $emergency->phoneNumber = $request->emergency['phoneNumber'];

        $emergency->save();

        return redirect(route('reviewer.user.profile.view'))->with('success', 'Your User Profile Successfully updated');
    }
}
