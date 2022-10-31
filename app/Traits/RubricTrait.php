<?php

namespace App\Traits;

use App\Models\Rubric;

trait RubricTrait
{
    public function checkRubricID($id)
    {
        return Rubric::where('id', $id)->exists();
    }

    public function getRubric($id)
    {
        return Rubric::find($id);
    }
}
