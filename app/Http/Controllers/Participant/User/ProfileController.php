<?php

namespace App\Http\Controllers\Participant\User;

use Illuminate\Http\Request;
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
            'name' => [
                'required',
                'string',
                Rule::unique('participants')->ignore($user->id),
            ],
            'email' => [
                'required',
                'email',
                'string',
                Rule::unique('participants')->ignore($user->id),
            ],
            'telephoneNumber' => [
                'required',
                'string',
                Rule::unique('participants')->ignore($user->id),
            ]
        ]);

        $user->name = $request->name;
        $user->email = $request->email;
        $user->telephoneNumber = $request->telephoneNumber;

        if(isset($user->reviewer)){
            $user->reviewer->email = $request->email;

            $user->reviewer->save();
        }

        $user->save();

        return redirect(route('participant.user.profile.view'))->with('success', 'Your User Profile Successfully updated');
    }
}
