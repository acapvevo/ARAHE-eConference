<?php

namespace App\Traits;

use App\Models\Reviewer;

trait ReviewerTrait
{
    public function getReviewers()
    {
        return Reviewer::with(['participant', 'submissions'])->get();
    }

    public function upgradeToReviewer($participant)
    {
        Reviewer::create([
            'participant_id' => $participant->id,
            'password' => $participant->password,
            'email' => $participant->email,
        ]);
    }
}
