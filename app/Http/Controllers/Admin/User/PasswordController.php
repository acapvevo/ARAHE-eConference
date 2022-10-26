<?php

namespace App\Http\Controllers\Admin\User;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class PasswordController extends Controller
{
    public function update(Request $request)
    {
        $user = Auth::guard('admin')->user();

        $request->validate([
            'password' => 'required|string|confirmed|min:8',
        ]);

        $user->password = Hash::make($request->password);

        $user->save();

        return redirect(route('admin.user.setting.view'))->with('success', 'Your Password Successfully updated');
    }
}
