<?php

namespace App\Http\Controllers\Reviewer\Submission;

use App\Models\Mark;
use App\Traits\FormTrait;
use App\Traits\RubricTrait;
use Illuminate\Http\Request;
use App\Traits\SubmissionTrait;
use App\Http\Controllers\Controller;

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
            'comment' => 'required|nullable|string',
            'rubrics' => 'array|required',
            'rubrics.*' => [
                'required',
                'string',
                'exists:scale,code',
                function ($attribute, $value, $fail) {
                    $rubric_id = explode('.', $attribute)[1];

                    if(!$this->checkRubricID($rubric_id))
                        $fail('Please Try Again');
                }
            ],
            'correction' => [
                'nullable',
                'file',
                'mimes:pdf',
                'max:4096'
            ]
        ]);

        $submission = $this->getSubmission($id);

        $totalMark = 0;
        foreach($request->rubrics as $id => $value) {
            if(Mark::where('rubric_id', $id)->where('submission_id', $submission->id)->exists())
                $mark = Mark::where('rubric_id', $id)->where('submission_id', $submission->id)->first();
            else{
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

        if($request->has('correction'))
            $submission->uploadPaper('correction', $request);
        else{
            if($submission->calculatePercentage() < 80)
                return back()->withInput()->withErrors([
                    'correction' => 'Need to upload correction if given mark is lower than 80%'
                ]);
        }

        if($submission->calculatePercentage() >= 80){
            $submission->status_code = 'WP';
            $submission->deletePaper('correction');
        }
        else
            $submission->status_code = 'C';

        $submission->save();

        return redirect(route('reviewer.submission.review.list'))->with('success', 'Review for Submission from ' . $submission->participant->name . 'has been successfully recorded.');
    }

    public function download($filename)
    {
        $submission = $this->getSubmission(session('submission_id'));
        return $this->getPaper('paper', $filename, $submission);
    }
}
