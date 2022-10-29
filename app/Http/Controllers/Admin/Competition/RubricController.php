<?php

namespace App\Http\Controllers\Admin\Competition;

use App\Http\Controllers\Controller;
use App\Models\Rubric;
use Illuminate\Http\Request;

class RubricController extends Controller
{
    public function create(Request $request)
    {
        $request->validate([
            'form_id' => 'required|integer|exists:App\Models\Form,id',
            'description' => 'required|string',
            'mark' => 'required|integer|min:1|max:5'
        ]);

        $rubric = Rubric::create([
            'form_id' => $request->form_id,
            'description' => $request->description,
            'mark' => $request->mark,
        ]);

        return redirect(route('admin.competition.form.view', ['id' => $rubric->form->id]))->with('success', 'The Rubric was successfully created');
    }

    public function view($id)
    {
        $rubric = Rubric::find($id);

        return view('admin.competition.rubric.view')->with([
            'rubric' => $rubric,
        ]);
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'description' => 'required|string',
            'mark' => 'required|integer|min:1|max:5'
        ]);

        $rubric = Rubric::find($id);

        $rubric->description = $request->description;
        $rubric->mark = $request->mark;

        $rubric->save();

        return redirect(route('admin.competition.rubric.view', ['id' => $rubric->id]))->with('success', 'This Rubric has been successfully updated');
    }

    public function delete(Request $request)
    {
        $request->validate([
            'form_id' => 'required|integer|exists:App\Models\Form,id',
            'rubric_id' => 'required|array',
            'rubric_id.*' => 'integer|exists:App\Models\Rubric,id',
        ]);

        Rubric::destroy($request->rubric_id);

        return redirect(route('admin.competition.form.view', ['id' => $request->form_id]))->with('success', 'The chosen Rubric(s) was successfully deleted');
    }
}
