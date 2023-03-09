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
            'submission' =>  $session['submission'],
            'registration' => $session['registration'],
            'congress' => $session['congress'],
        ]);
    }

    public function checkSession($year)
    {
        return Session::where('year', $year)->exists();
    }

    public function getCurrentSession()
    {
        $currentYear = Carbon::now()->year;
        $latestSession = Session::latest()->first();

        if($latestSession->year === ($currentYear + 1))
            return $latestSession;
        else if ($this->checkSession($currentYear))
            return $this->getSessionByYear($currentYear);

        return $$latestSession;
    }

    public function getSessionByYear($year)
    {
        return Session::where('year', $year)->first();
    }
}
