<?php

namespace App\Http\Controllers\Admin\User;

use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;

class ProfileController extends Controller
{
    public function view()
    {
        $user = Auth::guard('admin')->user();

        return view('admin.user.profile')->with('user', $user);
    }

    public function update(Request $request)
    {
        $user = Auth::guard('admin')->user();

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

        return redirect(route('admin.user.profile.view'))->with('success', 'Your User Profile Successfully updated');
    }
}
