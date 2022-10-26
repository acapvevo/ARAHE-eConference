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
            'name' => 'required|string',
            'email' => [
                'required',
                'email',
                'string',
                Rule::unique('users')->ignore($user->id),
            ]
        ]);

        $user->name = $request->name;
        $user->email = $request->email;

        $user->save();

        return redirect(route('participant.user.profile.view'))->with('success', 'Your User Profile Successfully updated');
    }
}
