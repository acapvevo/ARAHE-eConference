<?php

namespace App\Http\Controllers\Admin\Competition;

use App\Models\Hotel;
use App\Models\Occupancy;
use App\Traits\FormTrait;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class HotelController extends Controller
{
    use FormTrait;

    public function update(Request $request)
    {
        $request->validate([
            'form_id' => "required|integer|exists:forms,id",
            'hotels' => 'required|array',
            'hotels.*.locality' => 'required|string|exists:locality,code',
            'hotels.*.description' => 'required|string|max:255',
            'hotels.*.code' => 'required|string|max:3',
            'hotels.*.checkIn' => 'required|date',
            'hotels.*.checkOut' => 'required|date',
            'occupancies' => 'required|array',
            'occupancies.*.locality' => 'required|string|exists:locality,code',
            'occupancies.*.type' => 'required|string|max:255',
            'occupancies.*.number' => 'required|integer|min:1',
            'occupancies.*.bookBefore' => 'required|date',
        ]);

        $form = $this->getForm($request->form_id);

        //Hotel
        if (count($request['hotels']) >= $form->hotels->count()) {
            for ($i = 0; $i < count($request['hotels']); $i++) {

                if ($form->hotels->has($i)) {
                    $hotel = $form->hotels[$i];
                } else {
                    $hotel = new Hotel;
                    $hotel->form_id = $form->id;
                }

                $hotel->locality = $request['hotels'][$i]['locality'];
                $hotel->description = $request['hotels'][$i]['description'];
                $hotel->code = $request['hotels'][$i]['code'];
                $hotel->checkIn = $request['hotels'][$i]['checkIn'];
                $hotel->checkOut = $request['hotels'][$i]['checkOut'];

                $hotel->save();
            }
        } else {
            for ($i = 0; $i < count($request['hotels']); $i++) {

                $hotel = $form->hotels[$i];

                $hotel->locality = $request['hotels'][$i]['locality'];
                $hotel->description = $request['hotels'][$i]['description'];
                $hotel->code = $request['hotels'][$i]['code'];
                $hotel->checkIn = $request['hotels'][$i]['checkIn'];
                $hotel->checkOut = $request['hotels'][$i]['checkOut'];

                $hotel->save();
            }

            for ($i = (count($request['hotels']) - 1); $i < $form->hotels->count(); $i++) {
                $hotel = $form->hotels[$i];
                $hotel->delete();
            }
        }

        $form->updateHotelsInStripe();

        //Occupancy
        if (count($request['occupancies']) >= $form->occupancies->count()) {
            for ($i = 0; $i < count($request['occupancies']); $i++) {

                if ($form->occupancies->has($i)) {
                    $occupancy = $form->occupancies[$i];
                } else {
                    $occupancy = new Occupancy;
                    $occupancy->form_id = $form->id;
                }

                $occupancy->locality = $request['occupancies'][$i]['locality'];
                $occupancy->type = $request['occupancies'][$i]['type'];
                $occupancy->number = $request['occupancies'][$i]['number'];
                $occupancy->bookBefore = $request['occupancies'][$i]['bookBefore'];

                $occupancy->save();
            }
        } else {
            for ($i = 0; $i < count($request['occupancies']); $i++) {

                $occupancy = $form->occupancies[$i];

                $occupancy->locality = $request['occupancies'][$i]['locality'];
                $occupancy->type = $request['occupancies'][$i]['type'];
                $occupancy->number = $request['occupancies'][$i]['number'];
                $occupancy->bookBefore = $request['occupancies'][$i]['bookBefore'];

                $occupancy->save();
            }

            for ($i = (count($request['occupancies']) - 1); $i < $form->occupancies->count(); $i++) {
                $occupancy = $form->occupancies[$i];
                $occupancy->delete();
            }
        }

        $form->updateOccupansiesInStripe();

        return redirect(route('admin.competition.package.view', ['form_id' => $form->id]))->with('success', 'Hotel and Occupancy details for Year ' . $form->session->year . ' was updated successfully');
    }
}
