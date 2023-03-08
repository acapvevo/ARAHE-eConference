<?php

namespace App\Http\Controllers\Admin\Submission;

use App\Traits\FormTrait;
use Illuminate\Http\Request;
use App\Traits\ReviewerTrait;
use App\Traits\SubmissionTrait;
use App\Http\Controllers\Controller;
use App\Mail\RegistrationCompleted;
use App\Models\Registration;
use Illuminate\Support\Facades\Mail;

class RegistrationController extends Controller
{
    use FormTrait, SubmissionTrait, ReviewerTrait;

    public function list()
    {
        $currentForm = $this->getCurrentForm();

        $registrationsNotApproved = Registration::with('participant')->where('form_id', $currentForm->id)->whereIn('status_code', ['WR', 'UR'])->orderBy('updated_at', 'DESC')->get();
        $registrationsApproved = Registration::with('participant')->where('form_id', $currentForm->id)->whereIntegerNotInRaw('id', $registrationsNotApproved->pluck('id'))->orderBy('updated_at', 'DESC')->get();

        $registrations = collect();
        $registrations = $registrations->merge($registrationsNotApproved);
        $registrations = $registrations->merge($registrationsApproved);

        return view('admin.submission.registration.list')->with([
            'registrations' => $registrations,
            'form' => $currentForm
        ]);
    }

    public function view($id)
    {
        $registration = Registration::find($id);

        return view('admin.submission.registration.view')->with([
            'registration' => $registration,
        ]);
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'decision' => 'required|in:DR,UR,RR'
        ]);

        $registration = Registration::find($id);

        $registration->status_code = $request->decision;

        Mail::to($registration->participant->email)->send(new RegistrationCompleted($registration));

        $registration->save();

        return redirect(route('admin.submission.registration.view', ['id' => $registration->id]))->with('success', 'Registration status for ' . $registration->code . ' has been updated');
    }

    public function download(Request $request)
    {
        $request->validate([
            'registration_id' => 'required|exists:App\Models\Registration,id'
        ]);

        $registration = Registration::find($request['registration_id']);

        return $registration->getProofFile();
    }
}
