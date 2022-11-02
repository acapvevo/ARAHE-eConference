<?php

namespace App\Http\Controllers\Participant\Competition;

use App\Traits\FormTrait;
use App\Models\Submission;
use Illuminate\Http\Request;
use App\Traits\SubmissionTrait;
use App\Http\Controllers\Controller;
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
        if (Submission::where('form_id', $form_id)->where('participant_id', $participant->id)->exists()) {
            $submission = Submission::where('form_id', $form_id)->where('participant_id', $participant->id)->first();
        } else {
            $submission = Submission::create([
                'form_id' => $form_id,
                'participant_id' => $participant->id,
                'status_code' => 'N',
            ]);
        }

        session(['submission_id' => $submission->id]);

        return view('participant.competition.submissions.view')->with([
            'submission' => $submission,
        ]);
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'abstract' => 'required|string',
            'title' => 'required|string',
            'paper' => [
                'required',
                'file',
                'mimes:pdf',
                'max:4096',
            ]
        ]);

        $submission = Submission::find($id);

        $submission->title = $request->title;
        $submission->abstract = $request->abstract;

        if($request->has('paper')){
            $submission->uploadPaper('paper', $request);
        }

        if($submission->status_code === 'N'){
            $submission->status_code = 'P';
        } else if($submission->status_code === 'C'){
            $submission->status_code = 'IR';
            $submission->deletePaper('correction');
            $submission->totalMark = 0;
            $submission->comment = null;
        }

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
