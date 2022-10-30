<?php

namespace App\Traits;

use App\Models\Reviewer;

trait ReviewerTrait
{
    public function getReviewers()
    {
        return Reviewer::all();
    }
}
