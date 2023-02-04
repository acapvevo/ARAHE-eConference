<?php

namespace App\Rules;

use Illuminate\Support\Facades\Http;
use Illuminate\Contracts\Validation\InvokableRule;

class CheckCountry implements InvokableRule
{
    /**
     * Run the validation rule.
     *
     * @param  string  $attribute
     * @param  mixed  $value
     * @param  \Closure(string): \Illuminate\Translation\PotentiallyTranslatedString  $fail
     * @return void
     */
    public function __invoke($attribute, $value, $fail)
    {
        $response = Http::withHeaders([
            'X-CSCAPI-KEY' => 'VFR4VTdzUEk0THk1cEVJRkN0dkRCaWZwRWxpSjl0aGIyYktWTUgwTg==',
        ])->get('https://api.countrystatecity.in/v1/countries');

        $countries = collect(json_decode($response->body()));

        $isCountryNameAvailable = $countries->search(function ($country) use ($value) {
            return strtolower($country->name) === strtolower($value);
        });

        if (!$isCountryNameAvailable) {
            $fail('This country in not valid');
        }
    }
}
