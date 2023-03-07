<?php

namespace App\Traits;

use App\Models\Bill;

trait BillTrait
{
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
}
