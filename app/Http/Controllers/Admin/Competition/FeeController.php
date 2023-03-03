<?php

namespace App\Http\Controllers\Admin\Competition;

use App\Models\Fee;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Plugins\Stripes;
use App\Traits\FormTrait;

class FeeController extends Controller
{
    use FormTrait;

    public function update(Request $request)
    {
        $request->validate([
            'form_id' => "required|integer|exists:forms,id",
            'fee' => 'required|array',
            'fee.*.package_id' => 'required|integer|exists:App\Models\Package,id',
            'fee.*.duration_id' => 'required|integer|exists:App\Models\Duration,id',
            'fee.*.amount' => 'required|integer|min:1',
        ]);

        $form = $this->getForm($request->form_id);

        foreach ($request['fee'] as $feeInput) {
            $fee = Fee::where('parent_type', 'App\Models\Package')->where('parent_id', $feeInput['package_id'])->where('duration_id', $feeInput['duration_id'])->first();

            if (!$fee) {
                $fee = new Fee;

                $fee->parent_type = 'App\Models\Package';
                $fee->parent_id = $feeInput['package_id'];
                $fee->duration_id = $feeInput['duration_id'];
            }

            if (!$fee->product_id) {
                $name = '[' . $fee->duration->getLocality()->name . '] Package_' . $fee->parent->code . '-Duration_' . $fee->duration->name;
                $description = $fee->parent->description;
                $product = Stripes::createProduct($name, $description);

                $fee->product_id = $product->id;
            }

            $oldAmount = $fee->amount;
            $fee->amount = $feeInput['amount'];

            if (!$fee->price_id || $oldAmount != $fee->amount) {
                $currency = $fee->duration->getLocality()->stripe_currency;

                $fee->price_id = Stripes::createPrice($fee->amount, $currency, $fee->product_id);
            }

            $fee->save();
        }

        return redirect(route('admin.competition.package.view', ['form_id' => $form->id]))->with('success', 'Fee Structure for Year ' . $form->session->year . ' was updated successfully');
    }

    public function modify(Request $request)
    {
        $request->validate([
            'form_id' => "required|integer|exists:forms,id",
            'extraFee' => 'required|array',
            'extraFee.*.extra_id' => 'required|integer|exists:App\Models\Extra,id',
            'extraFee.*.duration_id' => 'required|integer|exists:App\Models\Duration,id',
            'extraFee.*.amount' => 'required|integer|min:1',
        ]);

        $form = $this->getForm($request->form_id);

        foreach ($request['extraFee'] as $feeInput) {
            $fee = Fee::where('parent_type', 'App\Models\Extra')->where('parent_id', $feeInput['extra_id'])->where('duration_id', $feeInput['duration_id'])->first();

            if (!$fee) {
                $fee = new Fee;

                $fee->parent_type = 'App\Models\Extra';
                $fee->parent_id = $feeInput['extra_id'];
                $fee->duration_id = $feeInput['duration_id'];
            }

            if (!$fee->product_id) {
                $name = '[' . $fee->duration->getLocality()->name . '] Package_' . $fee->parent->code . '-Duration_' . $fee->duration->name;
                $description = 'Including ' . $fee->parent->description . ' in overall trip for ARAHE conference';
                $product = Stripes::createProduct($name, $description);

                $fee->product_id = $product->id;
            }

            $oldAmount = $fee->amount;
            $fee->amount = $feeInput['amount'];

            if (!$fee->price_id || $oldAmount != $fee->amount) {
                $currency = $fee->duration->getLocality()->stripe_currency;

                $fee->price_id = Stripes::createPrice($fee->amount, $currency, $fee->product_id);
            }

            $fee->save();
        }

        return redirect(route('admin.competition.package.view', ['form_id' => $form->id]))->with('success', 'Extra Fee Structure for Year ' . $form->session->year . ' was updated successfully');
    }
}
