<?php

namespace App\Http\Controllers\Admin\Competition;

use App\Http\Controllers\Controller;
use App\Models\Extra;
use App\Traits\FormTrait;
use Illuminate\Http\Request;

class ExtraController extends Controller
{
    use FormTrait;

    public function create(Request $request)
    {
        $request->validate([
            'form_id' => "required|integer|exists:forms,id",
            'extra' => 'required|array',
            'extra.description' => 'required|string|max:255',
            'extra.code' => 'required|string|max:3',
            'extra.date' => 'required|date',
            'extra.option' => 'nullable|array',
            'extra.option.*' => 'required|string|max:255',
        ]);

        $form = $this->getForm($request->form_id);

        $extra = new Extra;

        $extra->form_id = $form->id;
        $extra->description = $request->extra['description'];
        $extra->code = $request->extra['code'];
        $extra->date = $request->extra['date'];
        $extra->options = $request->extra['option'] ?? [];

        $extra->save();

        return redirect(route('admin.competition.package.view', ['form_id' => $form->id]))->with('success', 'The Extra has been saved successfully');
    }

    public function view($id)
    {
        $extra = Extra::find($id);

        return view('admin.competition.extra.view')->with([
            'extra' => $extra
        ]);
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'description' => 'required|string|max:255',
            'code' => 'required|string|max:3',
            'date' => 'required|date',
            'option' => 'nullable|array',
            'option.*' => 'required|string|max:255',
        ]);

        $extra = Extra::find($id);

        $extra->description = $request->description;
        $extra->code = $request->code;
        $extra->date = $request->date;
        $extra->options = $request->option ?? [];

        $extra->save();

        return redirect(route('admin.competition.extra.view', ['id' => $extra->id]))->with('success', 'This Extra has been updated successfully');

    }

    public function delete(Request $request)
    {
        $request->validate([
            'form_id' => "required|integer|exists:forms,id",
            'extra_id' => 'required|array',
            'extra_id.*' => 'required|integer|exists:extras,id',
        ]);

        Extra::destroy($request->extra_id);

        return redirect(route('admin.competition.package.view', ['form_id' => $request->form_id]))->with('success', 'The chosen Extra has been deleted successfully');
    }
}
