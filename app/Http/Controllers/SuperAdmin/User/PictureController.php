<?php

namespace App\Http\Controllers\SuperAdmin\User;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Intervention\Image\Facades\Image;
use Illuminate\Support\Facades\Storage;

class PictureController extends Controller
{
    public function update(Request $request)
    {
        $user = Auth::guard('super_admin')->user();

        $request->validate([
            'ProfilePicture' => 'required|image|mimes:png,jpg,jpeg|max:2048'
        ]);

        $imageName = $user->id . '.' . $request->ProfilePicture->extension();
        $imagePath = "app\profile_picture\super_admin";

        $img = Image::make($request->ProfilePicture);
        if(!Storage::exists("profile_picture\super_admin")) {
            Storage::makeDirectory("profile_picture\super_admin"); //creates directory
        }
        $img->fit(300)->save(storage_path($imagePath . "\\" . $imageName));

        $user->image = $imageName;

        $user->save();

        return redirect(route('super_admin.user.setting.view'))->with('success', 'Your Profile Picture Successfully updated');
    }

    public function show()
    {
        $user = Auth::guard('super_admin')->user();

        return Storage::response('profile_picture/super_admin/' . $user->image);
    }
}
