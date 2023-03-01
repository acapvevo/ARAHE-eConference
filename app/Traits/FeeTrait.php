<?php

namespace App\Traits;

use App\Models\Fee;

trait FeeTrait
{
    public function getFee($id)
    {
        return Fee::find($id);
    }
}
