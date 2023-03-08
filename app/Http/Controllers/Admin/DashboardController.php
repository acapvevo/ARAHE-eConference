<?php

namespace App\Http\Controllers\Admin;

use App\Traits\FormTrait;
use App\Traits\SessionTrait;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class DashboardController extends Controller
{
    use FormTrait;

    public function index()
    {
        $form =  $this->getCurrentForm();

        $totalPayment = $form->getTotalPaymentDone();

        return view('admin.dashboard')->with([
            'form' => $form,
            'totalPayment' => $totalPayment,
        ]);
    }

    public function statistic(Request $request)
    {
        $request->validate([
            'form_id' => 'required|integer|exists:forms,id',
        ]);

        $form = $this->getForm($request->form_id);

        $registrationStatistic = $form->getRegistrationStatistic();
        $submissionStatistic = $form->getSubmissionStatistic();
        $paymentStatistic = $form->getPaymentStatistic();
        $sessionMonths = $form->getMonthsSession();

        return response()->json([
            'registrationStatistic' => $registrationStatistic,
            'submissionStatistic' => $submissionStatistic,
            'paymentStatistic' => $paymentStatistic,
            'sessionMonths' => $sessionMonths,
        ]);
    }
}
