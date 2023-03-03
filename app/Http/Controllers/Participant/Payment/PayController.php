<?php

namespace App\Http\Controllers\Participant\Payment;

use Stripe\Webhook;
use App\Models\Bill;
use App\Plugins\Stripes;
use App\Traits\SummaryTrait;
use Illuminate\Http\Request;
use UnexpectedValueException;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use App\Traits\BillTrait;
use Stripe\Exception\SignatureVerificationException;

class PayController extends Controller
{
    use SummaryTrait, BillTrait;

    public function main(Request $request)
    {
        $request->validate([
            'summary_id' => 'required|numeric|exists:summaries,id',
            'price_id' => 'required|array',
            'price_id.*' => [
                'required',
                'string',
                function ($attribute, $value, $fail) {
                    if (!DB::table('fees')->where('price_id', $value)->exists() && !DB::table('rates')->where('price_id', $value)->exists())
                        $fail('Please Try Again');
                }
            ]
        ]);

        $summary = $this->getSummary($request->summary_id);

        $bill = new Bill([
            'summary_id' => $summary->id,
        ]);

        $line_items = [];
        foreach ($request->price_id as $price_id) {
            $line_items[] = [
                'price' => $price_id,
                'quantity' => 1
            ];
        }

        $checkoutSession = Stripes::createCheckoutSession($line_items, $summary);

        $bill->checkoutSession_id = $checkoutSession->id;
        $bill->pay_attempt_at = Carbon::now();
        $bill->save();

        return redirect()->away($checkoutSession->url);
    }

    public function success(Request $request)
    {
        $request->validate([
            'session_id' => 'required|string|exists:bills,checkoutSession_id'
        ]);

        $bill = $this->getBillByCheckoutSessionId($request->session_id);
        $bill->status = 1;
        $bill->pay_complete_at = Carbon::now();
        $bill->save();

        $registration = $bill->summary->registration;
        $registration->status_code = 'AR';
        $registration->save();

        return redirect(route('participant.competition.registration.view', ['form_id' => $bill->summary->registration->form->id]))->with('success', 'Your payment successfully completed. See you at the conference');
    }

    public function cancel(Request $request)
    {
        $request->validate([
            'session_id' => 'required|string|exists:bills,checkoutSession_id'
        ]);

        $bill = $this->getBillByCheckoutSessionId($request->session_id);
        $bill->status = 4;
        $bill->save();

        return redirect(route('participant.competition.registration.view', ['form_id' => $bill->summary->registration->form->id]))->with('error', 'Your payment has been cancelled');
    }

    public function webhook()
    {
        $endpoint_secret = env('STRIPE_WEBHOOK_SECRET');

        $payload = @file_get_contents('php://input');
        $sig_header = $_SERVER['HTTP_STRIPE_SIGNATURE'];
        $event = null;

        try {
            $event = Webhook::constructEvent(
                $payload,
                $sig_header,
                $endpoint_secret
            );
        } catch (UnexpectedValueException $e) {
            // Invalid payload
            return response()->noContent(400);
        } catch (SignatureVerificationException $e) {
            // Invalid signature
            return response()->noContent(400);
        }

        // Handle the event
        switch ($event->type) {
            case 'checkout.session.completed':
                $session = $event->data->object;

                $bill = $this->getBillByCheckoutSessionId($session->id);
                if ($bill) {
                    $bill->status = 5;
                    $bill->pay_confirm_at = Carbon::now();
                    $bill->save();

                    $registration = $bill->summary->registration;
                    $registration->status_code = 'AR';
                    $registration->save();
                    // Send email to customer
                }
                break;
            case 'checkout.session.expired':
                $session = $event->data->object;

                $bill = $this->getBillByCheckoutSessionId($session->id);
                if ($bill) {
                    $bill->status = 3;
                    $bill->save();
                }
                break;

                // ... handle other event types
            default:
                return response()->noContent(400);
        }

        return response()->noContent(200);
    }
}
