<?php

namespace App\Http\Controllers\Admin\Submission;

use App\Models\Submission;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Traits\SubmissionTrait;

class PaperController extends Controller
{
    use SubmissionTrait;

    public function list()
    {
        $submissions = Submission::with(['registration.form', 'registration.participant', 'reviewer', 'reviewer.participant'])->get()->sortByDesc(function($submission){
            return $submission->submitDate;
        });

        return view('admin.submission.paper.list')->with([
            'submissions' => $submissions
        ]);
    }

    public function view($id)
    {
        $submission = Submission::find($id);

        return view('admin.submission.paper.view')->with([
            'submission' => $submission
        ]);
    }

    public function download(Request $request)
    {
        $request->validate([
            'submission_id' => 'required|integer|exists:App\Models\Submission,id',
            'type' => 'required|string|in:paperFile,correctionFile,abstractFile',
            'filename' => 'required|string'
        ]);

        $submission = $this->getSubmission($request->submission_id);
        return $this->getPaper($request->filename, $submission);
    }
}
