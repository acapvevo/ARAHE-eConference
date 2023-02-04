<?php

namespace App\Http\Controllers\Reviewer\Submission;

use App\Models\Mark;
use App\Traits\FormTrait;
use App\Traits\RubricTrait;
use Illuminate\Http\Request;
use App\Traits\SubmissionTrait;
use App\Http\Controllers\Controller;
use App\Traits\RegistrationTrait;
use Illuminate\Support\Facades\Auth;

class ReviewController extends Controller
{
    use FormTrait, RegistrationTrait, SubmissionTrait, RubricTrait;

    public function list()
    {
        $currentForm = $this->getCurrentForm();
        $user = Auth::guard('reviewer')->user();

        $submissions = $this->getSubmissionByReviewerID($user->id)->filter(function ($submission) use ($currentForm) {
            return $submission->registration->form->id == $currentForm->id && $submission->status_code == 'IR';
        });

        return view('reviewer.submission.review.list')->with([
            'form' => $currentForm,
            'submissions' => $submissions,
        ]);
    }

    public function view($id)
    {
        $submission = $this->getSubmission($id);

        session(['submission_id' => $submission->id]);

        return view('reviewer.submission.review.view')->with([
            'submission' => $submission,
        ]);
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'comment' => 'required_if:submit,save|nullable|string',
            'rubrics' => 'array|required_if:submit,save',
            'rubrics.*' => [
                'required_if:submit,save',
                'string',
                'exists:scale,code',
                function ($attribute, $value, $fail) {
                    $rubric_id = explode('.', $attribute)[1];

                    if (!$this->checkRubricID($rubric_id))
                        $fail('Please Try Again');
                }
            ],
            'correction' => [
                'nullable',
                'file',
                'mimes:pdf',
                'max:4096'
            ],
            'submit' => 'required|string|in:save,reject'
        ]);

        $submission = $this->getSubmission($id);

        if ($request->submit === 'reject') {
            $submission->status_code = 'P';
            $submission->reviewer_id = null;
            $submission->deleteFile('correctionFile');
            $submission->totalMark = 0;
            $submission->comment = null;

            $submission->save();

            return redirect(route('reviewer.submission.review.list'))->with('success', 'Submission ' . $submission->registration->code . ' has been successfully rejected.');
        }

        $totalMark = 0;
        foreach ($request->rubrics as $id => $value) {
            if (Mark::where('rubric_id', $id)->where('submission_id', $submission->id)->exists())
                $mark = Mark::where('rubric_id', $id)->where('submission_id', $submission->id)->first();
            else {
                $mark = new Mark;

                $mark->rubric_id = $id;
                $mark->submission_id = $submission->id;
            }

            $mark->scale_code = $value;
            $mark->save();

            $totalMark += $mark->getScale()->mark;
        }

        $submission->totalMark = $totalMark;
        $submission->comment = $request->comment;

        if ($request->has('correction'))
            $submission->saveFile('Correction', 'correctionFile', $request->file('correction'));
        else {
            if ($submission->calculatePercentage() < 80)
                return back()->withInput()->withErrors([
                    'correction' => 'Need to upload correction if given mark is lower than 80%'
                ]);
        }

        if ($submission->calculatePercentage() >= 80) {
            $submission->status_code = 'WP';
            $submission->deleteFile('correctionFile');

            $submission->setAcceptedDate();
        } else
            $submission->status_code = 'C';

        $submission->save();

        return redirect(route('reviewer.submission.review.list'))->with('success', 'Review for Submission ' . $submission->registration->code . ' has been successfully recorded.');
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
