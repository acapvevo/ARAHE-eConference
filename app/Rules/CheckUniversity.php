<?php

namespace App\Rules;

use Illuminate\Support\Facades\Http;
use Illuminate\Contracts\Validation\InvokableRule;

class CheckUniversity implements InvokableRule
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
        $response = Http::get('http://universities.hipolabs.com/search?name=');
        $universities = collect(json_decode($response->body()));

        $isUniversityNameAvailable = $universities->search(function ($university) use ($value) {
            return strtolower($university->name) === strtolower($value);
        });

        if(!$isUniversityNameAvailable){
            $fail('This University in not valid');
        }
    }
}
