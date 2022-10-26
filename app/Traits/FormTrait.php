<?php

namespace App\Traits;

use App\Models\Form;

trait FormTrait
{
    public function copyForm($oldFormID, $newForm)
    {
        $oldForm = Form::find($oldFormID);

        $newRubrics = collect();

        foreach($oldForm->rubrics as $rubric){
            $newRubrics->push($rubric->replicate([
                'form_id'
            ]));
        }

        $newForm->rubrics()->saveMany($newRubrics);
    }
}
