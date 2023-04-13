<?php

namespace App\Http\Controllers\Admin\Submission;

use App\Traits\BillTrait;
use App\Traits\FormTrait;
use App\Models\Registration;
use Illuminate\Http\Request;
use App\Traits\ReviewerTrait;
use Illuminate\Support\Carbon;
use App\Traits\SubmissionTrait;
use App\Mail\RegistrationCompleted;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Mail;

class RegistrationController extends Controller
{
    use FormTrait, SubmissionTrait, ReviewerTrait, BillTrait;

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
            'decision' => 'required|in:DR,UR,RR,UP',
            'pay_attempt_at' => 'required_if:decision,UP|date_format:Y-m-d\TH:i|before_or_equal:tomorrow',
            'timezone' => 'required_if:decision,UP|timezone',
            'proof' => 'required_if:decision,UP|file|mimes:jpg,jpeg,png,pdf|max:2048',
        ]);

        $registration = Registration::find($id);

        if ($request->decision === 'UP' && $registration->summary) {
            $bill = $this->createBill();

            $bill->summary_id = $registration->summary->id;
            $bill->pay_attempt_at = Carbon::parse($request->pay_attempt_at, $request->timezone)->setTimezone('UTC');
            $bill->pay_complete_at = $bill->pay_attempt_at;
            $bill->saveProof($request->file('proof'));

            $this->paymentSucceed($bill);

            $message = "Payment for Registration " . $registration->code . ' has been updated';
        } else {
            $registration->status_code = $request->decision;

            Mail::to($registration->participant->email)->send(new RegistrationCompleted($registration));

            $registration->save();

            $message = 'Registration status for ' . $registration->code . ' has been updated';
        }

        return redirect(route('admin.submission.registration.view', ['id' => $registration->id]))->with('success', $message);
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
