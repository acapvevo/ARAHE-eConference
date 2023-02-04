<?php

namespace App\Http\Controllers\Participant\Competition;

use App\Models\Category;
use App\Models\Submission;
use Illuminate\Http\Request;
use App\Traits\SubmissionTrait;
use Illuminate\Validation\Rule;
use App\Traits\RegistrationTrait;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;

class SubmissionController extends Controller
{
    use RegistrationTrait, SubmissionTrait;

    public function list()
    {
        $participant = Auth::guard('participant')->user();
        $registrations = $this->getRegistrationByParticipantID($participant->id)->sortByDesc(function($registration){
            return $registration->form->session->year;
        });

        return view('participant.competition.submissions.list')->with([
            'registrations' => $registrations,
        ]);
    }

    public function view($registration_id)
    {
        $registration = $this->getRegistration($registration_id);

        if (isset($registration->submission)) {
            $submission = $registration->submission;
        } else {
            $submission = new Submission([
                'registration_id' => $registration_id,
                'status_code' => 'N',
            ]);
        }

        return view('participant.competition.submissions.view')->with([
            'submission' => $submission,
        ]);
    }

    public function create(Request $request)
    {
        $request->validate([
            'registration_id' => 'required|string|exists:App\Models\Registration,id',
            'title' => 'required|string|unique:App\Models\Submission,title',
            'authors' => 'required|array',
            'authors.*.name' => 'required|string|distinct',
            'authors.*.email' => 'required|string|distinct',
            'coAuthors' => 'required|array',
            'coAuthors.*.name' => 'required|string|distinct',
            'coAuthors.*.email' => 'required|string|distinct',
            'keywords' => 'required|string',
            'presenter' => 'required|string',
            'abstract' => 'required|string',
            'abstractFile' => 'required|file|mimes:pdf,docx,doc|max:4096',
            'paperFile' => 'required|file|mimes:pdf,docx,doc|max:4096',
        ]);

        $submission = new Submission;

        $submission->registration_id = $request->registration_id;
        $submission->title = $request->title;
        $submission->authors = $request->authors;
        $submission->coAuthors = $request->coAuthors;
        $submission->presenter = $request->presenter;
        $submission->abstract = $request->abstract;
        $submission->keywords = $request->keywords;

        $submission->saveFile('Submission', 'abstractFile', $request->file('abstractFile'));
        $submission->saveFile('Submission', 'paperFile', $request->file('paperFile'));

        $submission->status_code = 'P';
        $submission->setSubmitDate();

        $submission->save();

        return redirect(route('participant.competition.submission.view', ['registration_id' => $submission->registration->id]))->with('success', 'This Submission has successfully created');
    }

    public function download(Request $request)
    {
        $request->validate([
            'submission_id' => 'required|integer|exists:App\Models\Submission,id',
            'type' => 'required|string|in:paperFile,correctionFile,abstractFile',
            'filename' => 'required|string'
        ]);

        $submission = Submission::find($request->submission_id);
        return $this->getPaper($request->filename, $submission);
    }
}
