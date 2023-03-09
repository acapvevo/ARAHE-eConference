<?php

namespace App\Http\Controllers\Reviewer;

use App\Traits\RecordTrait;
use App\Http\Controllers\Controller;
use App\Traits\FormTrait;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    use RecordTrait, FormTrait;

    public function index()
    {
        $reviewer = Auth::guard('reviewer')->user();
        $form = $this->getCurrentForm();
        $record = $this->getRecordByReviewerIdAndFormId($reviewer->id, $form->id);
        $record->save();

        $submissions = $reviewer->submissions->where(function ($submission) use ($form){
            return $submission->registration->form_id == $form->id && $submission->status_code == 'IR';
        })->values();

        return view('reviewer.dashboard')->with([
            'record' => $record,
            'form' => $form,
            'submissions' => $submissions,
        ]);
    }
}
