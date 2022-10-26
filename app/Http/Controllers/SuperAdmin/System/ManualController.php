<?php

namespace App\Http\Controllers\SuperAdmin\System;

use App\Models\Manual;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Storage;

class ManualController extends Controller
{
    public function list()
    {
        $manuals = Manual::all();

        return view('super_admin.setting.manual.list')->with([
            'manuals' => $manuals,
        ]);
    }

    public function view($id)
    {
        $manual = Manual::find($id);

        return view('super_admin.setting.manual.view')->with([
            'manual' => $manual,
        ]);
    }

    public function stream($id)
    {
        $manual = Manual::find($id);

        return Storage::response('manuals/' . $manual->file);
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'file' => 'required|mimes:pdf|max:4096',
        ]);

        $manual = Manual::find($id);

        $fileName = $manual->formatNameWithoutSpace() . '.' . $request->file->extension();
        $request->file->storeAs('manuals', $fileName);

        $manual->file = $fileName;

        $manual->save();

        return redirect(route('super_admin.setting.manual.view', ['id' => $manual->id]))->with('success', $manual->name . ' successfully updated');
    }
}
