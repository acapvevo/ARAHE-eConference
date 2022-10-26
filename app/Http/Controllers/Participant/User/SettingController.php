<?php

namespace App\Http\Controllers\Participant\User;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class SettingController extends Controller
{
    public function view()
    {
        return view('participant.user.setting');
    }
}
