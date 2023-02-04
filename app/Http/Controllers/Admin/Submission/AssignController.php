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
        $registrations = $currentForm->registrations->filter(function($registration){
            return $registration->submission->status_code === "P";
        });

        return view('admin.submission.assign.list')->with([
            'form' => $currentForm,
            'registrations' => $registrations,
        ]);
    }

    public function view($id)
    {
        $submission = $this->getSubmission($id);
        $reviewers = $this->getReviewers()->where('active', true)->reject(function ($reviewer) use ($submission) {
            return $submission->registration->participant->id === $reviewer->participant->id;
        });

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

            return redirect(route('admin.submission.assign.list'))->with('success', 'Submission ' . $submission->registration->code . ' has been successfully rejected.');
        }

        $submission->reviewer_id = $request->reviewer_id;
        $submission->status_code = 'IR';

        $submission->save();

        return redirect(route('admin.submission.assign.list'))->with('success', 'Submission ' . $submission->registration->code . ' has successfully assigned to ' . $submission->reviewer->participant->name);
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
