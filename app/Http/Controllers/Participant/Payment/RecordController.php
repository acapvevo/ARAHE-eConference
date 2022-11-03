<?php

namespace App\Http\Controllers\Participant\Payment;

use App\Models\Bill;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Traits\PaymentTrait;
use Illuminate\Support\Facades\Auth;

class RecordController extends Controller
{
    use PaymentTrait;

    public function list()
    {
        $participant = Auth::guard('participant')->user();
        $bills = Bill::whereIn('submission_id', $participant->submissions->pluck('id'))->get();

        return view('participant.payment.record.list')->with([
            'bills' => $bills,
        ]);
    }

    public function view($id)
    {
        $bill = Bill::find($id);
        $infoToyyibpay = $this->getBill($bill->code);
        $paymentLink = $this->billPaymentLink($bill->code);

        return view('participant.payment.record.view')->with([
            'bill' => $bill,
            'infoToyyibpay' => $infoToyyibpay,
            'paymentLink' => $paymentLink,
        ]);
    }

    public function receipt(Request $request)
    {

    }
}
