<?php

namespace App\Traits;

use App\Models\Session;
use Illuminate\Support\Carbon;

trait SessionTrait
{
    public function createSession($form_id, $session)
    {
        return Session::create([
            'form_id' => $form_id,
            'year' => (int) $session['year'],
            'submission' => [
                'start' => null,
                'end' => null
            ],
            'review' => [
                'start' => null,
                'end' => null
            ],
        ]);
    }

    public function checkSession($year)
    {
        return Session::where('year', $year)->exists();
    }

    public function getCurrentSession()
    {
        $currentYear = Carbon::now()->year;

        if($this->checkSession($currentYear))
            return $this->getSessionByYear($currentYear);

        return Session::latest()->first();;
    }

    public function getSessionByYear($year)
    {
        return Session::where('year', $year)->first();
    }
}
