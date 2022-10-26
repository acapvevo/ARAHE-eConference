<?php

namespace App\Http\Controllers\Admin\System;

use App\Models\Manual;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Storage;

class ManualController extends Controller
{
    public function view()
    {
        $manual = Manual::where('guard', 'admin')->first();

        return view('admin.system.manual.view')->with([
            'manual' => $manual,
        ]);
    }

    public function stream($id)
    {
        $manual = Manual::find($id);

        return Storage::response('manuals/' . $manual->file);
    }
}
