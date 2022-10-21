<?php

namespace App\Http\Controllers\SuperAdmin\User;

use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;

class ProfileController extends Controller
{
    public function view()
    {
        $user = Auth::guard('super_admin')->user();

        return view('super_admin.user.profile')->with('user', $user);
    }

    public function update(Request $request)
    {
        $user = Auth::guard('super_admin')->user();

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

        return redirect(route('super_admin.user.profile.view'))->with('success', 'Your User Profile Successfully updated');
    }
}
