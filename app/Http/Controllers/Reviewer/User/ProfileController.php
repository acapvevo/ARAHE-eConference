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
            'name' => [
                'required',
                'string',
                Rule::unique('participants')->ignore($user->id),
            ],
            'email' => [
                'required',
                'email',
                'string',
                Rule::unique('reviewers')->ignore($user->id),
            ],
            'telephoneNumber' => [
                'required',
                'string',
                Rule::unique('participants')->ignore($user->id),
            ]
        ]);

        $user->participant->name = $request->name;
        $user->email = $user->participant->email = $request->email;
        $user->participant->telephoneNumber = $request->telephoneNumber;

        $user->save();
        $user->participant->save();

        return redirect(route('reviewer.user.profile.view'))->with('success', 'Your User Profile Successfully updated');
    }
}
