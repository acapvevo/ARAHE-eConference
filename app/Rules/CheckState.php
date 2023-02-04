<?php

namespace App\Rules;

use Illuminate\Support\Facades\Http;
use Illuminate\Contracts\Validation\InvokableRule;

class CheckState implements InvokableRule
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
        ])->get('https://api.countrystatecity.in/v1/states');

        $states = collect(json_decode($response->body()));

        $isStateNameAvailable = $states->search(function ($state) use ($value) {
            return strtolower($state->name) === strtolower($value);
        });

        if(!$isStateNameAvailable){
            $fail('This state in not valid');
        }
    }
}
