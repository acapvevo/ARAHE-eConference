<?php

namespace App\Http\Controllers\Admin\User;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Intervention\Image\Facades\Image;
use Illuminate\Support\Facades\Storage;

class PictureController extends Controller
{
    public function update(Request $request)
    {
        $user = Auth::guard('admin')->user();

        $request->validate([
            'ProfilePicture' => 'required|image|mimes:png,jpg,jpeg|max:2048'
        ]);

        $imageName = $user->id . '.' . $request->ProfilePicture->extension();
        $imagePath = "app\profile_picture\admin";

        $img = Image::make($request->ProfilePicture);
        if(!Storage::exists("profile_picture\admin")) {
            Storage::makeDirectory("profile_picture\admin"); //creates directory
        }
        $img->fit(300)->save(storage_path($imagePath . "\\" . $imageName));

        $user->image = $imageName;

        $user->save();

        return redirect(route('admin.user.setting.view'))->with('success', 'Your Profile Picture Successfully updated');
    }

    public function show()
    {
        $user = Auth::guard('admin')->user();

        return Storage::response('profile_picture/admin/' . $user->image);
    }
}
