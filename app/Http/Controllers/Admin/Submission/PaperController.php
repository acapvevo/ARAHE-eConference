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
        $submissions = Submission::all();

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
            'type' => 'required|string|in:paper,correction',
            'filename' => 'required|required'
        ]);

        $submission = Submission::find($request->submission_id);
        return $this->getPaper($request->type, $request->filename, $submission);
    }
}
