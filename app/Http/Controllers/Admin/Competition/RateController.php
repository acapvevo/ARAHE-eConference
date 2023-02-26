<?php

namespace App\Http\Controllers\Admin\Competition;

use App\Models\Rate;
use App\Traits\FormTrait;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class RateController extends Controller
{
    use FormTrait;

    public function update(Request $request)
    {
        $request->validate([
            'form_id' => "required|integer|exists:forms,id",
            'rates' => 'required|array',
            'rates.*.occupancy_id' => 'required|integer|exists:App\Models\Occupancy,id',
            'rates.*.hotel_id' => 'required|integer|exists:App\Models\Hotel,id',
            'rates.*.amount' => 'required|integer|min:1',
        ]);

        $form = $this->getForm($request->form_id);

        foreach ($request['rates'] as $rateInput) {
            $rate = Rate::where('occupancy_id', $rateInput['occupancy_id'])->where('hotel_id', $rateInput['hotel_id'])->first();

            if (!$rate) {
                $rate = new Rate;

                $rate->occupancy_id = $rateInput['occupancy_id'];
                $rate->hotel_id = $rateInput['hotel_id'];
            }

            $rate->amount = $rateInput['amount'];
            $rate->save();
        }

        return redirect(route('admin.competition.package.view', ['form_id' => $form->id]))->with('success', 'Hotel Rate Structure for Year ' . $form->session->year . ' was updated successfully');
    }
}
