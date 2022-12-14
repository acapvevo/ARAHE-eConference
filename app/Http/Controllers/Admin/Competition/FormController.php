<?php

namespace App\Http\Controllers\Admin\Competition;

use App\Models\Form;
use App\Traits\FormTrait;
use App\Traits\SessionTrait;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Traits\CategoryTrait;

class FormController extends Controller
{
    use SessionTrait, FormTrait, CategoryTrait;

    public function list()
    {
        $forms = Form::with('session')->get();

        $yearsAvailable = $this->getYearsAvailable();

        return view('admin.competition.form.list')->with([
            'forms' => $forms,
            'yearsAvailable' => $yearsAvailable,
        ]);
    }

    public function create(Request $request)
    {
        $request->validate([
            'session.year' => 'required|date_format:Y|unique:App\Models\Session,year',
            'copyOldForm' => 'nullable|integer|starts_with:1',
            'oldForm' => 'required_if:copyOldForm,1|integer|exists:App\Models\Form,id'
        ]);

        $form = Form::create();

        $this->createSession($form->id, $request->session);

        if($request->has('copyOldForm')){
            $this->copyForm($request->oldForm, $form);
        }

        $this->generateCategory($form);

        return redirect(route('admin.competition.form.list'))->with('success', 'Form for ' . $form->session->year . ' was created successfully');
    }

    public function view($id)
    {
        $form = Form::find($id);

        return view('admin.competition.form.view')->with([
            'form' => $form,
        ]);
    }
}
