<?php

namespace App\Traits;

use App\Models\Bill;
use App\Plugins\Stripes;
use Barryvdh\DomPDF\Facade\Pdf as PDF;
use App\Mail\PaymentCompleted;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Mail;

trait BillTrait
{
    public function createBill()
    {
        return new Bill;
    }

    public function getBill($id)
    {
        return Bill::find($id);
    }

    public function checkBillBySummary($summary_id)
    {
        return Bill::where('summary_id', $summary_id)->exists();
    }

    public function getBillByCheckoutSessionId($session_id)
    {
        return Bill::where('checkoutSession_id', $session_id)->first();
    }

    public function getBillByPaymentIntentId($payment_intent_id)
    {
        return Bill::where('payment_intent_id', $payment_intent_id)->first();
    }

    public function paymentSucceed($bill)
    {
        $bill->status = 5;
        $bill->pay_confirm_at = Carbon::now();

        $pdf = PDF::loadView('pdf.payment.receipt', [
            'bill' => $bill,
            'imageBase64' => 'data:image/png;base64, ' . base64_encode(file_get_contents(public_path('assets/favicon/android-chrome-512x512.png')))
        ]);
        $pdf->save($bill->getReceiptFilepath(), 'local');

        Mail::to($bill->summary->registration->participant->email)->send(new PaymentCompleted($bill));

        $bill->save();

        $registration = $bill->summary->registration;
        $registration->status_code = 'PW';
        $registration->save();
    }

    public function paymentFailed($bill)
    {
        $bill->status = 3;

        $bill->save();
    }

    public function paymentExpired($bill)
    {
        $bill->status = 6;

        $bill->save();
    }

    public function paymentCancelled($bill)
    {
        Stripes::exprireCheckoutSession($bill->checkoutSession_id);
        $bill->status = 4;

        $bill->save();
    }
}
