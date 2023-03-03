<?php

namespace App\Traits;

use App\Models\Summary;

trait SummaryTrait
{
    public function getSummary($id)
    {
        return Summary::find($id);
    }
}
