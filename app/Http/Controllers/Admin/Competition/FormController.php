<?php

namespace App\Http\Controllers\Admin\Competition;

use App\Models\Fee;
use App\Models\Form;
use App\Models\Category;
use App\Models\Duration;
use App\Traits\FormTrait;
use App\Traits\SessionTrait;
use Illuminate\Http\Request;
use App\Traits\CategoryTrait;
use App\Http\Controllers\Controller;

class FormController extends Controller
{
    use SessionTrait, FormTrait, CategoryTrait;

    public function list()
    {
        $forms = Form::with(['session'])->get();

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
            'session.congress.start' => 'required|date',
            'session.congress.end' => 'required|date',
            'session.registration.start' => 'required|date',
            'session.registration.end' => 'required|date',
            'session.submission.start' => 'required|date|same:session.registration.start',
            'session.submission.end' => 'required|date|before:session.congress.start',
            'copyOldForm' => 'nullable|integer|starts_with:1',
            'oldForm' => 'required_if:copyOldForm,1|integer|exists:App\Models\Form,id'
        ]);

        $form = Form::create();

        $this->createSession($form->id, $request->session);

        if ($request->has('copyOldForm')) {
            $this->copyForm($request->oldForm, $form);
        }

        return redirect(route('admin.competition.form.list'))->with('success', 'Form for ' . $form->session->year . ' was created successfully');
    }

    public function view($id)
    {
        $form = Form::find($id);

        return view('admin.competition.form.view')->with([
            'form' => $form,
        ]);
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'session.congress.start' => 'required|date',
            'session.congress.end' => 'required|date',
            'session.registration.start' => 'required|date',
            'session.registration.end' => 'required|date',
            'session.submission.start' => 'required|date|same:session.registration.start',
            'session.submission.end' => 'required|date|before:session.congress.start',
        ]);

        $form = Form::find($id);
        $session = $form->session;

        $session->congress = $request->session['congress'];
        $session->registration = $request->session['registration'];
        $session->submission = $request->session['submission'];

        $session->save();

        return redirect(route('admin.competition.form.view', $form->id))->with('success', 'Form Details for ' . $form->session->year . ' was updated successfully');
    }
}
