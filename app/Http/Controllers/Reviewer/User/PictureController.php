<?php

namespace App\Http\Controllers\Reviewer\User;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Intervention\Image\Facades\Image;
use Illuminate\Support\Facades\Storage;

class PictureController extends Controller
{
    public function update(Request $request)
    {
        $user = Auth::guard('reviewer')->user()->participant;

        $request->validate([
            'ProfilePicture' => 'required|image|mimes:png,jpg,jpeg|max:2048'
        ]);

        $imageName = $user->id . '.' . $request->ProfilePicture->extension();
        $imagePath = "app\profile_picture/participant";

        $img = Image::make($request->ProfilePicture);
        if(!Storage::exists("profile_picture/participant")) {
            Storage::makeDirectory("profile_picture/participant"); //creates directory
        }
        $img->fit(300)->save(storage_path($imagePath . "/" . $imageName));

        $user->image = $imageName;

        $user->save();

        return redirect(route('reviewer.user.setting.view'))->with('success', 'Your Profile Picture Successfully updated');
    }

    public function show()
    {
        $user = Auth::guard('reviewer')->user();

        return Storage::response('profile_picture/participant/' . $user->participant->image);
    }
}
