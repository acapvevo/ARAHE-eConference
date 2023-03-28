<?php

namespace App\Http\Controllers\Reviewer\Submission;

use App\Models\Mark;
use App\Traits\FormTrait;
use App\Traits\RubricTrait;
use Illuminate\Http\Request;
use App\Traits\SubmissionTrait;
use App\Traits\RegistrationTrait;
use App\Mail\SubmissionCorrection;
use App\Http\Controllers\Controller;
use App\Mail\SubmissionAccepted;
use App\Traits\RecordTrait;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;

class ReviewController extends Controller
{
    use FormTrait, RegistrationTrait, SubmissionTrait, RubricTrait, RecordTrait;

    public function list()
    {
        $currentForm = $this->getCurrentForm();
        $user = Auth::guard('reviewer')->user();

        $submissions = $this->getSubmissionByReviewerID($user->id)->filter(function ($submission) use ($currentForm) {
            return $submission->registration->form->id == $currentForm->id && $submission->status_code !== "A";
        })->sort(function ($a, $b) {
            if ($a->status_code === "IR")
                return 1;

            return ($a < $b) ? -1 : 1;
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
        $submission = $this->getSubmission($id);
        $form = $submission->registration->form;

        $request->validate([
            'comment' => 'required_if:submit,save|sometimes|string',
            'rubrics' => [
                'array',
                'required_if:submit,save',
                function ($attribute, $value, $fail) use ($form){
                    if(count($value) !== $form->rubrics->count())
                        $fail('Please complete the rubrics form');
                }
            ],
            'rubrics.*' => [
                'required',
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
                'mimes:pdf,doc,docx',
                'max:4096'
            ],
            'submit' => 'required|string|in:save,reject,return'
        ]);

        $record = $this->getRecordByReviewerIdAndFormId($submission->reviewer_id, $submission->registration->form_id);

        if ($request->submit === 'reject') {
            $submission->status_code = 'R';
            $submission->reviewer_id = null;
            $submission->deleteFile('correctionFile');
            $submission->totalMark = 0;
            $submission->comment = null;

            $record->reject += 1;
            $record->save();

            $submission->save();

            return redirect(route('reviewer.submission.review.list'))->with('success', 'Submission ' . $submission->registration->code . ' has been successfully rejected.');
        } else if ($request->submit === 'return') {
            $submission->status_code = 'P';
            $submission->reviewer_id = null;
            $submission->deleteFile('correctionFile');
            $submission->totalMark = 0;
            $submission->comment = null;

            $record->return += 1;
            $record->save();

            $submission->save();

            return redirect(route('reviewer.submission.review.list'))->with('success', 'Submission ' . $submission->registration->code . ' has been send to Admin to be re-appoint to other reviewer.');
        }

        $totalMark = 0;
        foreach ($request->rubrics as $id => $code) {
            if (Mark::where('rubric_id', $id)->where('submission_id', $submission->id)->exists())
                $mark = Mark::where('rubric_id', $id)->where('submission_id', $submission->id)->first();
            else {
                $mark = new Mark;

                $mark->rubric_id = $id;
                $mark->submission_id = $submission->id;
            }

            $mark->scale_code = $code;
            $mark->save();

            $totalMark += $mark->getScale()->mark;
        }

        $submission->totalMark = $totalMark;
        $submission->comment = $request->comment;

        if ($request->has('correction'))
            $submission->saveFile('Reviewed', 'correctionFile', $request->file('correction'));
        else {
            if ($submission->calculatePercentage() < 80)
                return back()->withInput()->withErrors([
                    'correction' => 'Need to upload correction if given mark is lower than 80%'
                ]);
        }

        if ($submission->calculatePercentage() >= 80) {
            $submission->status_code = 'A';
            $submission->deleteFile('correctionFile');

            $submission->setAcceptedDate();

            Mail::to($submission->registration->participant->email)->send(new SubmissionAccepted($submission));

            $record->reviewing -= 1;
            $record->accept += 1;
            $record->save();
        } else {
            $submission->status_code = 'C';

            Mail::to($submission->registration->participant->email)->send(new SubmissionCorrection($submission));
        }

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
