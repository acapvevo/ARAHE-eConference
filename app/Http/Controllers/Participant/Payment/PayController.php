<?php

namespace App\Http\Controllers\Participant\Payment;

use Stripe\Webhook;
use App\Models\Bill;
use App\Plugins\Stripes;
use App\Traits\BillTrait;
use Carbon\CarbonImmutable;
use App\Traits\SummaryTrait;
use Illuminate\Http\Request;
use UnexpectedValueException;
use App\Mail\PaymentCompleted;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Mail;
use Barryvdh\DomPDF\Facade\Pdf as PDF;
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

        $bill->pay_attempt_at = CarbonImmutable::now();

        $checkoutSession = Stripes::createCheckoutSession($line_items, $summary, CarbonImmutable::now());
        $bill->checkoutSession_id = $checkoutSession->id;

        $bill->save();

        return redirect()->away($checkoutSession->url);
    }

    public function success(Request $request)
    {
        $request->validate([
            'session_id' => 'required|string|exists:bills,checkoutSession_id'
        ]);

        $bill = $this->getBillByCheckoutSessionId($request->session_id);
        $bill->status = 5;
        $bill->pay_complete_at = Carbon::now();

        $bill->save();

        $registration = $bill->summary->registration;
        $registration->status_code = 'PW';
        $registration->save();

        return redirect(route('participant.competition.registration.view', ['form_id' => $bill->summary->registration->form->id]))->with('success', 'Your payment successfully completed. See you at the conference');
    }

    public function cancel(Request $request)
    {
        $request->validate([
            'session_id' => 'required|string|exists:bills,checkoutSession_id'
        ]);

        $bill = $this->getBillByCheckoutSessionId($request->session_id);
        $this->paymentCancelled($bill);

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
            return response()->json([
                'message' => 'Invalid payload'
            ], 400);
        } catch (SignatureVerificationException $e) {
            // Invalid signature
            return response()->json([
                'message' => 'Invalid signature'
            ], 400);
        }

        // Handle the event
        switch ($event->type) {
            case 'checkout.session.completed':
                $checkout_session = $event->data->object;

                $bill = $this->getBillByCheckoutSessionId($checkout_session->id);

                // Check if the order is paid (for example, from a card payment)
                //
                // A delayed notification payment will have an `unpaid` status, as
                // you're still waiting for funds to be transferred from the customer's
                // account.
                if ($checkout_session->payment_status == 'paid' && $bill) {
                    $this->paymentSucceed($bill);
                }
                $status = 'payment successful';

                break;

            case 'checkout.session.async_payment_succeeded':
                $checkout_session = $event->data->object;

                $bill = $this->getBillByCheckoutSessionId($checkout_session->id);

                // Fulfill the purchase
                if ($bill) {
                    $this->paymentSucceed($bill);
                }
                $status = 'payment successful';

                break;

            case 'checkout.session.async_payment_failed':
                $checkout_session = $event->data->object;

                $bill = $this->getBillByCheckoutSessionId($checkout_session->id);
                if ($bill) {
                    $this->paymentFailed($bill);
                }
                $status = 'payment failed';

                break;

            case 'checkout.session.expired':
                $checkout_session = $event->data->object;

                $bill = $this->getBillByCheckoutSessionId($checkout_session->id);
                if ($bill) {
                    $this->paymentExpired($bill);
                }
                $status = 'payment expired';

                break;

                // ... handle other event types
            default:
                return response()->json([
                    'message' => 'Invalid event'
                ], 400);
        }

        return response()->json([
            'message' => 'Webhook Successfully Processed',
            'status' => $status
        ], 200);
    }

    public function review($id)
    {
        $bill = Bill::find($id);

        $pdf = PDF::loadView('pdf.payment.receipt', [
            'bill' => $bill,
            'checkoutSession' => Stripes::getCheckoutSession($bill->checkoutSession_id),
            'imageBase64' => 'data:image/png;base64, ' . base64_encode(file_get_contents(public_path('assets/favicon/android-chrome-512x512.png')))
        ])->setPaper('a4', 'potrait');
        $pdf->save($bill->getReceiptFilepath(), 'local');

        return view('pdf.payment.receipt')->with([
            'bill' => $bill,
            'checkoutSession' => Stripes::getCheckoutSession($bill->checkoutSession_id),
            'imageBase64' => 'data:image/png;base64, ' . base64_encode(file_get_contents(public_path('assets/favicon/android-chrome-512x512.png')))
        ]);
    }
}
