<?php

namespace App\Http\Controllers\Participant\Payment;

use App\Models\Bill;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Plugins\Stripes;
use App\Traits\PaymentTrait;
use Illuminate\Support\Facades\Auth;

class RecordController extends Controller
{
    public function list()
    {
        $participant = Auth::guard('participant')->user();
        $bills = $participant->getBills();

        return view('participant.payment.record.list')->with([
            'bills' => $bills,
        ]);
    }

    public function view($id)
    {
        $bill = Bill::find($id);
        $checkoutSession = Stripes::getCheckoutSession($bill->checkoutSession_id);

        return view('participant.payment.record.view')->with([
            'bill' => $bill,
            'checkoutSession' => $checkoutSession,
        ]);
    }

    public function receipt(Request $request)
    {

    }
}
