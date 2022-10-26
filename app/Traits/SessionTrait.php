<?php

namespace App\Traits;

use App\Models\Session;

trait SessionTrait
{
    public function createSession($form_id, $session)
    {
        return Session::create([
            'form_id' => $form_id,
            'year' => $session['year'],
            'year' => [
                'start' => null,
                'end' => null
            ],
            'review' => [
                'start' => null,
                'end' => null
            ],
        ]);
    }
}
