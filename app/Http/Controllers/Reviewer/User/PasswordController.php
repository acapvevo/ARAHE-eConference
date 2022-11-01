<?php

namespace App\Http\Controllers\Reviewer\User;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class PasswordController extends Controller
{
    public function update(Request $request)
    {
        $user = Auth::guard('reviewer')->user();

        $request->validate([
            'password' => 'required|string|confirmed|min:8',
        ]);

        $user->password = $user->participant->password = Hash::make($request->password);

        $user->save();
        $user->participant->save();

        return redirect(route('reviewer.user.setting.view'))->with('success', 'Your Password Successfully updated');
    }
}
