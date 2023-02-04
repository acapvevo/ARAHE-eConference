<?php

namespace App\Http\Controllers\Admin\Submission;

use App\Traits\FormTrait;
use Illuminate\Http\Request;
use App\Traits\ReviewerTrait;
use App\Traits\SubmissionTrait;
use App\Http\Controllers\Controller;

class AssignController extends Controller
{
    use FormTrait, SubmissionTrait, ReviewerTrait;

    public function list()
    {
        $currentForm = $this->getCurrentForm();

        return view('admin.submission.assign.list')->with([
            'form' => $currentForm,
        ]);
    }

    public function view($id)
    {
        $submission = $this->getSubmission($id);
        $reviewers = $this->getReviewers()->where('active', true)->reject(function ($reviewer) use ($submission) {
            if(isset($submission->reviewer))
                return $submission->reviewer->id === $reviewer->id;
        });

        session(['submission_id' => $submission->id]);

        return view('admin.submission.assign.view')->with([
            'submission' => $submission,
            'reviewers' => $reviewers,
        ]);
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'submit' => 'required|in:assign,reject',
            'reviewer_id' => 'required_if:submit,assign|integer|exists:App\Models\Reviewer,id',
        ]);

        $submission = $this->getSubmission($id);

        if($request->submit === 'reject'){
            $submission->status_code = 'R';
            $submission->comment = 'rejected by Admin';
            $submission->save();

            return redirect(route('admin.submission.assign.list'))->with('success', 'Submission from ' . $submission->participant->name . 'has been successfully rejected.');
        }

        $submission->reviewer_id = $request->reviewer_id;
        $submission->status_code = 'IR';

        $submission->save();

        return redirect(route('admin.submission.assign.list'))->with('success', 'Submission for ' . $submission->participant->name . ' has successfully assigned to ' . $submission->reviewer->name);
    }

    public function reject(Request $request, $id)
    {
        $request->validate([
            'comment' => 'required|string',
        ]);

        $submission = $this->getSubmission($id);

        $submission->comment = $request->comment;
        $submission->status_code = 'R';

        $submission->save();

        return redirect(route('admin.submission.assign.list', ['id' => $submission->id]))->with('success', 'Submission for ' . $submission->participant->name . ' has successfully rejected');
    }

    public function download($filename)
    {
        $submission = $this->getSubmission(session('submission_id'));
        return $this->getPaper('paper',$filename, $submission);
    }
}
