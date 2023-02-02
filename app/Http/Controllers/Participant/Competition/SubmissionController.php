<?php

namespace App\Http\Controllers\Participant\Competition;

use App\Traits\FormTrait;
use App\Models\Submission;
use Illuminate\Http\Request;
use App\Traits\SubmissionTrait;
use Illuminate\Validation\Rule;
use App\Http\Controllers\Controller;
use App\Models\Category;
use Illuminate\Support\Facades\Auth;

class SubmissionController extends Controller
{
    use FormTrait, SubmissionTrait;

    public function list()
    {
        $forms = $this->getForms()->sortByDesc('year');
        $participant = Auth::guard('participant')->user();

        foreach ($forms as $form) {
            if (Submission::where('form_id', $form->id)->where('participant_id', $participant->id)->exists()) {
                $submission = Submission::where('form_id', $form->id)->where('participant_id', $participant->id)->first();
                $form->setAttribute('submission', $submission);
            }
        }

        return view('participant.competition.submissions.list')->with([
            'forms' => $forms,
        ]);
    }

    public function view($form_id)
    {
        $participant = Auth::guard('participant')->user();
        $submissionQuery = Submission::where('form_id', $form_id)->where('participant_id', $participant->id);

        if ($submissionQuery->exists()) {
            $submission = $submissionQuery->first();
        } else {
            $submission = new Submission([
                'form_id' => $form_id,
                'participant_id' => $participant->id,
                'status_code' => 'NR',
            ]);

            $submission->generateNewRegistrationID();
        }

        return view('participant.competition.submissions.view')->with([
            'submission' => $submission,
        ]);
    }

    public function register(Request $request)
    {
        $request->validate([
            'form_id' => 'required|integer|exists:Form,id',
            'participant_id' => 'required|string|exists:Participant,id',
            'registration_id' => 'required|string',
            'register_as' => 'required|string',
            'category_id' => 'required|string|exists:Category,id',
            'proof' => [
                'sometimes',
                Rule::requiredIf(function () {
                    $category = Category::find(request('category_id'));

                    return $category->needProof;
                }),
                'file',
                'mimes:jpg,png,jpeg,pdf',
                'max:4096',
            ]
        ]);

        $submission = new Submission;

        $submission->form_id = $request->form_id;
        $submission->participant_id = $request->participant_id;
        $submission->registration_id = $request->registration_id;
        $submission->register_as = $request->register_as;
        $submission->category_id = $request->category_id;

        // safe proof if available
        if ($request->file('proof')) {
            $fileName = preg_replace('/\s+/', '_', $submission->participant_id . $request->file('proof')->extension());
            $registration_id_filename = str_replace('-', '_', $submission->registration_id);
            $request->file('proof')->storeAs('ARAHE' . $submission->form->session->year . '/' . $registration_id_filename, $fileName);
            $submission->proof = $fileName;
        }

        $submission->status = 'N';

        $submission->save();

        return redirect(route('participant.competition.submission.view', ['form_id' => $submission->form->id]))->with('success', 'This Submission has successfully updated');
    }

    public function download(Request $request)
    {
        $request->validate([
            'submission_id' => 'required|integer|exists:App\Models\Submission,id',
            'type' => 'required|string|in:paper,correction',
            'filename' => 'required|required'
        ]);

        $submission = Submission::find($request->submission_id);
        return $this->getPaper($request->type, $request->filename, $submission);
    }
}
