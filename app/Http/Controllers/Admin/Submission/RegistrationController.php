<?php

namespace App\Http\Controllers\Admin\Submission;

use App\Traits\BillTrait;
use App\Traits\FormTrait;
use App\Models\Registration;
use App\Traits\SummaryTrait;
use Illuminate\Http\Request;
use App\Traits\ReviewerTrait;
use Illuminate\Support\Carbon;
use App\Traits\SubmissionTrait;
use App\Exports\RegistrationExport;
use App\Mail\RegistrationCompleted;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Validator;

class RegistrationController extends Controller
{
    use FormTrait, SubmissionTrait, ReviewerTrait, BillTrait, SummaryTrait;

    public function list()
    {
        $currentForm = $this->getCurrentForm();

        $registrationsNotApproved = Registration::with('participant')->where('form_id', $currentForm->id)->whereIn('status_code', ['WR', 'UR'])->orderBy('updated_at', 'DESC')->get();
        $registrationsApproved = Registration::with('participant')->where('form_id', $currentForm->id)->whereIntegerNotInRaw('id', $registrationsNotApproved->pluck('id'))->orderBy('updated_at', 'DESC')->get();

        $registrations = collect();
        $registrations = $registrations->merge($registrationsNotApproved);
        $registrations = $registrations->merge($registrationsApproved);

        $summaries = $this->getSummariesByFormID($currentForm->id);

        $acommadationSummaries = $summaries->filter(function ($summary) {
            return ($summary->hotel_id && $summary->occupancy_id) || $summary->getPackage()->fullPackage;
        });
        $extraSummaries = $summaries->filter(function ($summary) {
            return $summary->extras->isNotEmpty();
        });

        return view('admin.submission.registration.list')->with([
            'registrations' => $registrations->sortByDesc('updated_at'),
            'form' => $currentForm,
            'summaries' => $summaries,
            'acommadationSummaries' => $acommadationSummaries,
            'extraSummaries' => $extraSummaries
        ]);
    }

    public function export(Request $request)
    {
        $request->validate([
            'form_id' => 'required|integer|exists:forms,id'
        ]);

        $form = $this->getForm($request->form_id);

        return (new RegistrationExport($form->id))->download('Participant_List_ARAHE' . $form->session->year . '.xlsx');
    }

    public function view($id)
    {
        $validation = Validator::make(['registration_id' => $id], [
            'registration_id' => 'required|integer|exists:registrations,id',
        ]);

        if ($validation->fails())
            return redirect(route('admin.submission.registration.list'))->with('error', 'Please Try Again');

        $registration = Registration::find($id);

        return view('admin.submission.registration.view')->with([
            'registration' => $registration,
        ]);
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'decision' => 'required|in:DR,UR,RR,UP,NR',
            'date' => 'required_if:decision,UP|date|before_or_equal:tomorrow',
            'time' => 'required_if:decision,UP|date_format:H:i',
            'timezone' => 'required_if:decision,UP|timezone',
            'proof' => 'required_if:decision,UP|file|mimes:jpg,jpeg,png,pdf|max:2048',
        ]);

        $registration = Registration::find($id);

        if ($request->decision === 'UP' && $registration->summary) {
            $bill = $this->createBill();

            $bill->summary_id = $registration->summary->id;
            $bill->pay_attempt_at = Carbon::createFromFormat('Y-m-d H:i', $request->date . $request->time, $request->timezone)->setTimezone('UTC');
            $bill->pay_complete_at = $bill->pay_attempt_at;
            $bill->saveProof($request->file('proof'));

            $this->paymentSucceed($bill, true);

            $message = "Payment for Registration " . $registration->code . ' has been updated';
        } else {
            $registration->status_code = $request->decision;

            Mail::to($registration->participant->email)->send(new RegistrationCompleted($registration));

            if($registration->summary && $registration->status_code == 'NR'){
                $registration->status_code = "PR";
            }

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
