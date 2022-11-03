<?php

namespace App\Http\Controllers\Participant\Payment;

use App\Models\Bill;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use App\Traits\SubmissionTrait;
use App\Http\Controllers\Controller;
use App\Traits\PaymentTrait;

class PayController extends Controller
{
    use SubmissionTrait, PaymentTrait;

    public function main(Request $request)
    {
        $request->validate([
            'submission_id' => 'required|integer|exists:App\Models\Submission,id'
        ]);

        $submission = $this->getSubmission($request->submission_id);

        if (isset($submission->bill))
            $bill = $submission->bill;
        else
            $bill = Bill::make([
                'submission_id' => $submission->id,
                'amount' => $submission->form->category->standardAmount * 100,
            ]);

        if(!isset($bill->code)){
            $response = $this->createBill($submission->form->category->code, (object)[
                'billName' => 'ARAHE' . $submission->form->session->year . ' - ' . $submission->participant->name,
                'billDescription' => 'Payment for ARAHE' . $submission->form->session->year . ' participation from ' . $submission->participant->name,
                'billPriceSetting' => 1,
                'billPayorInfo' => 1,
                'billAmount' => $bill->amount,
                'billExternalReferenceNo' => $bill->id,
                'billTo' => $submission->participant->name,
                'billEmail' => $submission->participant->email,
                'billPhone' => $submission->participant->telephoneNumber,
                'billReturnUrl' => route('participant.payment.pay.return'),
                'billCallbackUrl' => route('participant.payment.pay.callback'),
            ]);
            $bill->code = $response->BillCode;
        }

        $bill->pay_attempt_at = Carbon::now();

        $bill->save();

        return redirect()->away($this->billPaymentLink($bill->code));
    }

    public function return(Request $request)
    {
        $request->validate([
            'billcode' => 'required|string|exists:App\Models\Bill,code'
        ]);

        $bill = Bill::where('code', $request->billcode)->first();

        $bill->pay_complete_at = Carbon::now();
        $bill->status = $request->status_id;

        $bill->save();

        if($bill->status == 1){
            $bill->submission->status_code = 'A';
            $bill->submission->save();

            $message = 'Your Payment has been successfully completed';
            $key = 'success';
        } else if($bill->status == 2){
            $message = 'Your Payment was still in pending';
            $key = 'warning';
        }
        else{
            $message = 'Your Payment was failed, please try again';
            $key = 'error';
        }

        return redirect(route('participant.competition.submission.view', ['form_id' => $bill->submission->form->id]))->with($key, $message);
    }

    public function callback(Request $request)
    {
        $request->validate([
            'billcode' => 'required|string|exists:App\Models\Bill,code'
        ]);

        $bill = Bill::where('code', $request->billcode)->first();

        $bill->pay_complete_at = $request->transaction_time;
        $bill->status = $request->status;

        $bill->save();
    }
}
