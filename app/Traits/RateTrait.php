<?php

namespace App\Traits;

use App\Models\Rate;

trait RateTrait
{
    public function getRate($id)
    {
        return Rate::find($id);
    }
}
