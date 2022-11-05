<?php

namespace App\Http\Controllers\Admin\Payment;

use App\Models\Bill;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Traits\PaymentTrait;

class BillController extends Controller
{
    use PaymentTrait;

    public function view($id)
    {
        $bill = Bill::find($id);
        $infoToyyibPay = $this->getBill($bill->code);

        return view('admin.payment.bill.view')->with([
            'bill' => $bill,
            'infoToyyibPay' => $infoToyyibPay,
        ]);
    }
}
