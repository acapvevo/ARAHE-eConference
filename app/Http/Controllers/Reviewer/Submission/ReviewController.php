<?php

namespace App\Http\Controllers\Reviewer\Submission;

use App\Traits\FormTrait;
use App\Traits\RubricTrait;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Traits\SubmissionTrait;

class ReviewController extends Controller
{
    use FormTrait, SubmissionTrait, RubricTrait;

    public function list()
    {
        $currentForm = $this->getCurrentForm();

        return view('reviewer.submission.review.list')->with([
            'form' => $currentForm,
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
            'submit' => 'required|in:save,reject',
            'comment' => 'required_if:submit,save|nullable|string',
            'rubrics' => 'array|nullable',
            'rubrics.*' => [
                'required',
                function ($attribute, $value, $fail) {
                    $rubric_id = explode('.', $attribute)[1];

                    if(!$this->checkRubricID($rubric_id))
                        $fail('Please Try Again');
                }
            ]
        ]);

        $submission = $this->getSubmission($id);

        if($request->submit === 'reject'){
            $submission->status_code = 'R';
            $submission->save();

            return redirect(route('reviewer.submission.review.list'))->with('success', 'Submission from ' . $submission->participant->name . 'has been successfully rejected.');
        }

        $totalMark = 0;
        foreach ($request->rubrics as $id => $value){
            $rubric = $this->getRubric($id);

            $totalMark += $rubric->mark;
        }

        $submission->mark = $totalMark;
        $submission->comment = $request->comment;

        if($submission->calculatePercentage() >= 80)
            $submission->status_code = 'WP';
        else
            $submission->status_code = 'C';

        $submission->save();

        return redirect(route('reviewer.submission.review.list'))->with('success', 'Review for Submission from ' . $submission->participant->name . 'has been successfully recorded.');
    }

    public function download($filename)
    {
        $submission = $this->getSubmission(session('submission_id'));
        return $this->getPaper($filename, $submission);
    }
}
