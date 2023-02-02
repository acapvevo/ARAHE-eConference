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
            'session.submission.start' => 'required|date',
            'session.submission.end' => 'required|date',
            'session.registration.start' => 'required|date',
            'session.registration.end' => 'required|date',
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
            'categories' => 'array|required',
            'categories.*.name' => 'required|string',
            'categories.*.needProof' => 'sometimes|nullable|boolean',
            'durations' => 'array|required',
            'durations.*.name' => 'required|string',
            'durations.*.start' => 'required|date',
            'durations.*.end' => 'required|date',
        ]);

        $form = Form::find($id);

        //Category
        if (count($request['categories']) >= $form->categories->count()) {
            for ($i = 0; $i < count($request['categories']); $i++) {

                if ($form->categories->has($i)) {
                    $category = $form->categories[$i];
                } else {
                    $category = new Category;
                    $category->form_id = $form->id;
                }

                $category->name = $request['categories'][$i]['name'];
                $category->needProof = $request['categories'][$i]['needProof'] ?? false;
                $category->save();
            }
        } else {
            for ($i = 0; $i < count($request['categories']); $i++) {

                $category = $form->categories[$i];
                $category->name = $request['categories'][$i]['name'];
                $category->needProof = $request['categories'][$i]['needProof'] ?? false;
                $category->save();
            }

            for ($i = (count($request['categories']) - 1); $i < $form->categories->count(); $i++) {
                $category = $form->categories[$i];
                $category->delete();
            }
        }

        //Duration
        if (count($request['durations']) >= $form->durations->count()) {
            for ($i = 0; $i < count($request['durations']); $i++) {

                if ($form->durations->has($i)) {
                    $duration = $form->durations[$i];
                } else {
                    $duration = new Duration;
                    $duration->form_id = $form->id;
                }

                $duration->name = $request['durations'][$i]['name'];
                $duration->start = $request['durations'][$i]['start'];
                $duration->end = $request['durations'][$i]['end'];
                $duration->save();
            }
        } else {
            for ($i = 0; $i < count($request['durations']); $i++) {

                $duration = $form->durations[$i];
                $duration->name = $request['durations'][$i]['name'];
                $duration->start = $request['durations'][$i]['start'];
                $duration->end = $request['durations'][$i]['end'];
                $duration->save();
            }

            for ($i = (count($request['durations']) - 1); $i < $form->durations->count(); $i++) {
                $duration = $form->durations[$i];
                $duration->delete();
            }
        }

        return redirect(route('admin.competition.form.view', $form->id))->with('success', 'Form for ' . $form->session->year . ' was updated successfully');
    }

    public function modify(Request $request, $id)
    {
        $request->validate([
            'fee' => 'required|array',
            'fee.*.category_id' => 'required|integer|exists:App\Models\Category,id',
            'fee.*.duration_id' => 'required|integer|exists:App\Models\Duration,id',
            'fee.*.amount' => 'required|integer|min:1',
        ]);

        $form = Form::find($id);

        foreach($request['fee'] as $feeInput){
            $fee = Fee::where('category_id', $feeInput['category_id'])->where('duration_id', $feeInput['duration_id'])->first();

            if(!$fee){
                $fee = new Fee;
                $fee->category_id = $feeInput['category_id'];
                $fee->duration_id = $feeInput['duration_id'];
            }

            $fee->amount = $feeInput['amount'];
            $fee->save();
        }

        return redirect(route('admin.competition.form.view', $form->id))->with('success', 'Fee Structure for ' . $form->session->year . ' was updated successfully');
    }
}
