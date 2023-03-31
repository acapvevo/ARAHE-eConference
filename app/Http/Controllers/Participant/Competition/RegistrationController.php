<?php

namespace App\Http\Controllers\Participant\Competition;

use App\Models\Category;
use App\Traits\FormTrait;
use App\Models\Registration;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use App\Http\Controllers\Controller;
use App\Traits\ParticipantTrait;
use Illuminate\Support\Facades\Auth;

class RegistrationController extends Controller
{
    use FormTrait, ParticipantTrait;

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
        if($this->formExists($form_id)){
            return redirect()->route('participant.competition.registration.list')->with('error', 'Registration Not Found');
        }

        $participant = Auth::guard('participant')->user();
        $registrationQuery = Registration::with(['category.packages.fees', 'form.extras.fees'])->where('form_id', $form_id)->where('participant_id', $participant->id);

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

    public function categories(Request $request)
    {
        $request->validate([
            'form_id' => 'required|exists:App\Models\Form,id',
            'locality_code' => 'required|exists:locality,code',
        ]);

        $categories = Category::where('locality', $request->locality_code)->where('form_id', $request->form_id)->get();

        return response()->json($categories);
    }

    public function participants(Request $request)
    {
        $request->validate([
            'term' => 'required'
        ]);

        $user = Auth::guard('participant')->user();

        $participants = $this->searchParticipant($request->term, $user);

        return response()->json([
            "results" => $participants->items(),
            "pagination" => [
                "more" => $participants->hasMorePages()
            ]
        ]);
    }

    public function participant(Request $request)
    {
        $request->validate([
            'id' => 'required|exists:App\Models\Participant,id',
        ]);

        $participant = $this->getParticipant($request->id);

        return response()->json($participant);
    }

    public function create(Request $request)
    {
        $request->validate([
            'form_id' => 'required|exists:App\Models\Form,id',
            'code' => 'required|unique:App\Models\Registration,code',
            'type' => 'required|string|exists:participant_type,code',
            'category' => 'required|exists:App\Models\Category,id',
            'proof' => [
                'sometimes',
                'file',
                'mimes:pdf,jpg,jpeg,png',
                'max:2048',
                Rule::requiredIf(function () use ($request) {
                    $category = Category::find($request->category);

                    return $category->needProof;
                })
            ],
            'link' => 'sometimes|integer|exists:App\Models\Participant,id',
            'dietary' => 'required|string|exists:dietary_preference,code'
        ]);

        $participant = Auth::guard('participant')->user();

        $registration = new Registration;

        $registration->participant_id = $participant->id;
        $registration->form_id = $request->form_id;
        $registration->code = $request->code;
        $registration->type = $request->type;
        $registration->category_id = $request->category;
        $registration->dietary = $request->dietary;

        if ($request->hasFile('proof')) {
            $fileName = str_replace('-', '_', $registration->code) . '.' . $request->file('proof')->getClientOriginalExtension();
            $filePath = 'ARAHE' . $registration->form->session->year . '/registration';

            $request->file('proof')->storeAs($filePath, $fileName);

            $registration->proof = $fileName;
        }

        $registration->link = $request->link ?? null;
        $registration->status_code = 'WR';

        $registration->save();

        return redirect()->route('participant.competition.registration.view', ['form_id' => $request['form_id']])->with([
            'success' => 'Registration created successfully.',
        ]);
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'type' => 'required|string|exists:participant_type,code',
            'category' => 'required|exists:App\Models\Category,id',
            'proof' => [
                Rule::requiredIf(function () use ($request) {
                    $category = Category::find($request->category);

                    return $category->needProof;
                }),
                'file',
                'mimes:pdf,jpg,jpeg,png',
                'max:2048',
            ],
            'link' => 'sometimes|integer|exists:App\Models\Participant,id',
            'dietary' => 'required|string|exists:dietary_preference,code'
        ]);

        $registration = Registration::find($id);

        $registration->type = $request->type;
        $registration->category_id = $request->category;

        if ($request->hasFile('proof')) {
            $fileName = str_replace('-', '_', $registration->code) . '.' . $request->file('proof')->getClientOriginalExtension();
            $filePath = 'ARAHE' . $registration->form->session->year . '/registration';

            $request->file('proof')->storeAs($filePath, $fileName);

            $registration->proof = $fileName;
        } else {
            $registration->proof = null;
        }

        $registration->link = $request->link ?? null;
        $registration->dietary = $request->dietary;
        $registration->status_code = 'WR';

        $registration->save();

        return redirect()->route('participant.competition.registration.view', ['form_id' => $registration->form->id])->with([
            'success' => 'Registration updated successfully.',
        ]);
    }

    public function download(Request $request)
    {
        $request->validate([
            'registration_id' => 'required|exists:App\Models\Registration,id',
        ]);

        $registration = Registration::find($request['registration_id']);

        return $registration->getProofFile();
    }
}
