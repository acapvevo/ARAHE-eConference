<?php

namespace App\Http\Controllers\Participant\User;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Intervention\Image\Facades\Image;
use Illuminate\Support\Facades\Storage;

class PictureController extends Controller
{
    public function update(Request $request)
    {
        $user = Auth::guard('participant')->user();

        $request->validate([
            'profilePicture' => 'required|image|mimes:png,jpg,jpeg|max:2048'
        ]);

        $imageName = $user->id . '.' . $request->profilePicture->extension();
        $imagePath = "app/profile_picture/participant";

        $img = Image::make($request->profilePicture);
        if(!Storage::exists("profile_picture/participant")) {
            Storage::makeDirectory("profile_picture/participant"); //creates directory
        }
        $img->fit(300)->save(storage_path($imagePath . "/" . $imageName));

        $user->image = $imageName;

        $user->save();

        return redirect(route('participant.user.setting.view'))->with('success', 'Your Profile Picture Successfully updated');
    }

    public function show()
    {
        $user = Auth::guard('participant')->user();

        return Storage::response('profile_picture/participant/' . $user->image);
    }
}
