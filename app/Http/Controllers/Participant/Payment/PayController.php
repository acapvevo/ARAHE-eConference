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

        $response = $this->createBill($submission->form->category->code, (object)[
            'billName' => 'ARAHE' . $submission->form->session->year . ' - ' . $submission->participant->name,
            'billDescription' => 'Payment for participation ARAHE' . $submission->form->session->year . ' from ' . $submission->participant->name,
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

        $bill->pay_attempt_at = Carbon::now();
        $bill->code = $response[0]->BillCode;

        $bill->save();

        return redirect()->away($this->billPaymentLink($bill->code));
    }

    public function callback(Request $request)
    {
        $request->validate([
            'order_id' => 'required|string|exists:App\Models\Bill,code'
        ]);

        $bill = Bill::where('code', $request->billcode)->first();

        dd($request->all(), $bill);
    }

    public function return(Request $request)
    {
        $request->validate([
            'order_id' => 'required|string|exists:App\Models\Bill,code'
        ]);

        $bill = Bill::where('code', $request->billcode)->first();

        dd($request->query->all(), $bill);
    }
}
