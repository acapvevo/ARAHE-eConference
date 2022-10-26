<?php

namespace App\Http\Controllers\Reviewer\User;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class SettingController extends Controller
{
    public function view()
    {
        return view('reviewer.user.setting');
    }
}
