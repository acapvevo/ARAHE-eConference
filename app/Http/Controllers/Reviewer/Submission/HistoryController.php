<?php

namespace App\Http\Controllers\Reviewer\Submission;

use Illuminate\Http\Request;
use App\Traits\SubmissionTrait;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;

class HistoryController extends Controller
{
    use SubmissionTrait;

    public function list()
    {
        $user = Auth::guard('reviewer')->user();

        $submissions = $this->getSubmissionByReviewerID($user->id)->filter(function ($submission) {
            return $submission->status_code === "A";
        });

        return view('reviewer.submission.history.list')->with([
            'submissions' => $submissions,
        ]);
    }

    public function view($id)
    {
        $submission = $this->getSubmission($id);

        if(!$submission || $submission->reviewer_id !== Auth::guard('reviewer')->user()->id){
            return view('reviewer.submission.history.unauthorize');
        }

        return view('reviewer.submission.history.view')->with([
            'submission' => $submission,
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
