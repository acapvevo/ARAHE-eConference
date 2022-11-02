<?php

namespace App\Http\Controllers\Admin\Payment;

use App\Models\Bill;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Tarsoft\Toyyibpay\ToyyibpayFacade as Toyyibpay;

class BillController extends Controller
{
    public function view($id)
    {
        $bill = Bill::find($id);
        $infoToyyibPay = Toyyibpay::getBill($bill->code);

        return view('admin.payment.bill.view')->with([
            'bill' => $bill,
            'infoToyyibPay' => $infoToyyibPay,
        ]);
    }
}
