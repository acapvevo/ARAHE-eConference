<?php

namespace App\Http\Controllers\Admin\Payment;

use App\Models\Bill;
use App\Plugins\Stripes;
use App\Traits\PaymentTrait;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class BillController extends Controller
{
    public function list()
    {
        $bills = Bill::orderBy('created_at', 'desc')->get();

        return view('admin.payment.bill.list')->with([
            'bills' => $bills,
        ]);
    }

    public function view($id)
    {
        $bill = Bill::find($id);
        $checkoutSession = Stripes::getCheckoutSession($bill->checkoutSession_id);

        return view('admin.payment.bill.view')->with([
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

        switch ($request->attribute) {
            case 'proof':
                return $bill->downloadProof();
                break;

            case 'receipt':
                return $bill->downloadReceipt();
                break;

            default:
                return redirect(route('admin.payment.bill.view', ['id' => $bill->id]))->with('error', 'Request cannot processed');
        }
    }
}
