<?php

namespace App\Http\Controllers\Participant\Competition;

use App\Http\Controllers\Controller;
use App\Models\Package;
use App\Models\Summary;
use App\Traits\FeeTrait;
use App\Traits\RateTrait;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class PackageController extends Controller
{
    use FeeTrait, RateTrait;

    public function fee(Request $request)
    {
        $request->validate([
            'fee' => 'required|integer|exists:fees,id'
        ]);

        $fee = $this->getFee($request->fee);

        return response()->json([
            'category' => $fee->parent
        ]);
    }

    public function create(Request $request)
    {
        $request->validate([
            'registration_id' => 'required|exists:App\Models\Registration,id',
            'package' => 'required|array',
            'package.fee' => 'required|integer|exists:fees,id',
            'extra' => 'sometimes|array',
            'extra.*' => 'required|array',
            'extra.*.fee' => [
                'sometimes',
                'integer',
                'exists:fees,id',
                function ($attribute, $value, $fail) use ($request) {
                    $index = explode('.', $attribute)[1];
                    $fee = $this->getFee($value);
                    $option = $request->get('extra')[$index]['option'] ?? null;

                    if ($fee->parent->options->count()) {
                        if (!$option)
                            $fail('Please choose an option if you interested to include this extra package');
                        else if (!$fee->parent->options->has($option))
                            $fail('Please choose the options again');
                    }
                },
            ],
            'extra.*.option' => [
                'sometimes',
                'integer',
                'required_with:extra.*.fee',
                Rule::requiredIf(function ()  use ($request) {
                    $mainPackage = Package::find($request->package['fee']);

                    return $mainPackage->fullPackage;
                }),
            ],
            'hotel' => 'sometimes|array',
            'hotel.rate' => 'sometimes|integer|exists:rates,id',
        ]);
        $summary = new Summary;

        $summary->registration_id = $request->registration_id;
        $summary->total = 0;

        $packageFee = $this->getFee($request->package['fee']);
        $summary->duration_id = $packageFee->duration->id;
        $summary->package_id = $packageFee->parent->id;
        $summary->total += $packageFee->amount;

        $summary->extras = [];
        foreach ($request->extra ?? [] as $extra) {
            $extraFee = $this->getFee($extra['fee'] ?? 0);

            if ($extraFee) {
                $summary->extras[] = [
                    'id' => $extraFee->parent->id,
                    'option' => isset($extra['option']) ? ($extra['option'] - 1) : null,
                ];
                $summary->total += $extraFee->amount;
            }
        }

        if ($request->hotel['rate'] ?? null) {
            $hotelRate = $this->getRate($request->hotel['rate']);
            $summary->hotel_id = $hotelRate->hotel->id;
            $summary->occupancy_id = $hotelRate->occupancy->id;
            $summary->total += $hotelRate->amount;
        }

        $summary->locality = $summary->getDuration()->getLocality()->code;

        $summary->save();

        $registration = $summary->registration;

        $registration->status_code = 'PR';

        $registration->save();

        return redirect(route('participant.competition.registration.view', ['form_id' => $summary->registration->form->id]))->with('success', 'Your package choices has been succesfully saved');
    }

    public function update(Request $request)
    {
        $request->validate([
            'summary_id' => 'required|exists:App\Models\Summary,id',
            'package' => 'required|array',
            'package.fee' => 'required|integer|exists:fees,id',
            'extra' => 'sometimes|array',
            'extra.*' => 'required|array',
            'extra.*.fee' => [
                'sometimes',
                'integer',
                'exists:fees,id',
                function ($attribute, $value, $fail) use ($request) {
                    $index = explode('.', $attribute)[1];
                    $fee = $this->getFee($value);
                    $option = $request->get('extra')[$index]['option'] ?? null;

                    if ($fee->parent->options->count()) {
                        if (!$option)
                            $fail('Please choose an option if you interested to include this extra package');
                        else if (!$fee->parent->options->has($option))
                            $fail('Please choose the options again');
                    }
                },
            ],
            'extra.*.option' => [
                'nullable',
                'integer',
            ],
            'hotel' => 'sometimes|array',
            'hotel.rate' => 'sometimes|integer|exists:rates,id',
        ]);

        $summary = Summary::find($request->summary_id);

        $summary->total = 0;

        $packageFee = $this->getFee($request->package['fee']);
        $summary->duration_id = $packageFee->duration->id;
        $summary->package_id = $packageFee->parent->id;
        $summary->total += $packageFee->amount;

        $summary->extras = [];
        foreach ($request->extra ?? [] as $extra) {
            $extraFee = $this->getFee($extra['fee'] ?? 0);

            if ($extraFee) {
                $summary->extras[] = [
                    'id' => $extraFee->parent->id,
                    'option' => isset($extra['option']) ? ($extra['option'] - 1) : null,
                ];
                $summary->total += $extraFee->amount;
            }
        }

        if ($request->hotel['rate'] ?? null) {
            $hotelRate = $this->getRate($request->hotel['rate']);
            $summary->hotel_id = $hotelRate->hotel->id;
            $summary->occupancy_id = $hotelRate->occupancy->id;
            $summary->total += $hotelRate->amount;
        }

        $summary->locality = $summary->getDuration()->getLocality()->code;

        $summary->save();

        return redirect(route('participant.competition.registration.view', ['form_id' => $summary->registration->form->id]))->with('success', 'Your package choices has been succesfully updated');
    }
}
