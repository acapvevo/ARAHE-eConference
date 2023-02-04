<?php

namespace App\Http\Controllers\Participant\Competition;

use App\Models\Category;
use App\Traits\FormTrait;
use App\Models\Submission;
use App\Models\Registration;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class RegistrationController extends Controller
{
    use FormTrait;

    public function list()
    {
        $forms = $this->getForms()->sortByDesc('year');
        $participant = Auth::guard('participant')->user();

        foreach ($forms as $form) {
            if (Registration::where('form_id', $form->id)->where('participant_id', $participant->id)->exists()) {
                $registration = Registration::where('form_id', $form->id)->where('participant_id', $participant->id)->first();
                $form->setAttribute('registration', $registration);
            }
        }

        return view('participant.competition.registrations.list')->with([
            'forms' => $forms,
        ]);
    }

    public function view($form_id)
    {
        $participant = Auth::guard('participant')->user();
        $registrationQuery = Registration::where('form_id', $form_id)->where('participant_id', $participant->id);

        if ($registrationQuery->exists()) {
            $registration = $registrationQuery->first();
        } else {
            $registration = new Registration([
                'form_id' => $form_id,
                'participant_id' => $participant->id,
                'status_code' => 'NR',
            ]);

            $registration->generateCode();
        }

        return view('participant.competition.registrations.view')->with([
            'registration' => $registration,
        ]);
    }

    public function category($id)
    {
        return response()->json(Category::find($id));
    }

    public function create(Request $request)
    {
        $request->validate([
            'form_id' => 'required|exists:App\Models\Form,id',
            'code' => 'required|unique:App\Models\Registration,code',
            'register_as' => 'required|in:presenter,participant',
            'category_id' => 'required|exists:App\Models\Category,id',
            'proof' => [
                '',
                'file',
                'mimes:pdf,jpg,jpeg,png',
                'max:2048',
                Rule::requiredIf(function () use ($request) {
                    $category = Category::find($request->category_id);

                    return $category->needProof;
                })
            ],
        ]);

        $participant = Auth::guard('participant')->user();
        $registration = new Registration($request->all());
        $registration->participant_id = $participant->id;
        $registration->status_code = 'WR';

        if ($request->hasFile('proof')) {
            $fileName = str_replace('-', '_', $registration->code) . '.' . $request->file('proof')->getClientOriginalExtension();
            $filePath = 'ARAHE' . $registration->form->session->year. '/registration';

            $request->file('proof')->storeAs($filePath, $fileName);

            $registration->proof = $fileName;
        }

        $registration->save();

        return redirect()->route('participant.competition.registration.view', ['form_id' => $request['form_id']])->with([
            'success' => 'Registration created successfully.',
        ]);
    }

    public function download(Request $request)
    {
        $request->validate([
            'registration_id' => 'required|exists:App\Models\Registration,id',
            'filename' => 'required|exists:App\Models\Registration,proof'
        ]);

        $registration = Registration::find($request['registration_id']);
        $filePath = 'ARAHE' . $registration->form->session->year. '/registration/' . $registration->proof;

        return Storage::response($filePath);
    }
}
