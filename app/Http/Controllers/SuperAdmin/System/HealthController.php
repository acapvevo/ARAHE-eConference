<?php

namespace App\Http\Controllers\SuperAdmin\System;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class HealthController extends Controller
{
    public function view()
    {
        return view('super_admin.system.health.view');
    }
}
