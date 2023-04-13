<?php

namespace App\Http\Controllers\Participant\Payment;

use App\Models\Bill;
use App\Plugins\Stripes;
use App\Traits\PaymentTrait;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
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

        if (!$bill || $bill->summary->registration->participant_id !== Auth::guard('participant')->user()->id) {
            return view('participant.payment.record.unauthorize');
        }

        $checkoutSession = Stripes::getCheckoutSession($bill->checkoutSession_id);

        return view('participant.payment.record.view')->with([
            'bill' => $bill,
            'checkoutSession' => $checkoutSession,
        ]);
    }

    public function download(Request $request)
    {
        $request->validate([
            'id' => [
                'integer',
                'required',
                'exists:bills,id'
            ],
            'attribute' => 'required|string|in:proof,receipt'
        ]);

        $bill = Bill::find($request->id);
        $participant = Auth::guard('participant')->user();

        if ($bill->summary->registration->participant_id !== $participant->id) {
            $attribute = 'error';
        } else {
            $attribute = $request->attribute;
        }

        switch ($request->attribute) {
            case 'proof':
                return $bill->downloadProof();
                break;

            case 'receipt':
                return $bill->downloadReceipt();
                break;

            default:
                return redirect(route('participant.payment.record.list'))->with('error', 'Request cannot processed');
        }
    }
}
