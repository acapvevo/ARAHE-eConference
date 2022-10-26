<?php

namespace App\Http\Controllers\Reviewer\System;

use App\Models\Manual;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Storage;

class ManualController extends Controller
{
    public function view()
    {
        $manual = Manual::where('guard', 'reviewer')->first();

        return view('reviewer.system.manual.view')->with([
            'manual' => $manual,
        ]);
    }

    public function stream($id)
    {
        $manual = Manual::find($id);

        return Storage::response('manuals/' . $manual->file);
    }
}
