<?php

namespace App\Http\Controllers\Admin\Competition;

use App\Models\Form;
use App\Traits\FormTrait;
use App\Traits\SessionTrait;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class FormController extends Controller
{
    use SessionTrait, FormTrait;

    public function list()
    {
        $forms = Form::all();

        return view('admin.competition.form.list')->with([
            'forms' => $forms,
        ]);
    }

    public function create(Request $request)
    {
        $request->validate([
            'sesi.year' => 'required|date_format:Y|unique:App\Models\Session,year',
            'copyOldForm' => ''
        ]);

        $form = Form::create();

        $this->createSession($form->id, $request->session);

        if($request->has('copyOldForm')){
            $this->copyForm($request->form_id, $form);
        }

        return redirect(route('admin.competition.form.list'))->with('success', 'Form for ' . $form->session->year . 'was created successfully');
    }

    public function view($id)
    {
        $form = Form::find($id);

        return view('admin.competition.form.view')->with([
            'form' => $form,
        ]);
    }
}
