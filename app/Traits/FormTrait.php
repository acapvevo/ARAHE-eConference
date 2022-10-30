<?php

namespace App\Traits;

use App\Models\Form;
use Illuminate\Support\Carbon;

trait FormTrait
{
    use SessionTrait;

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

    public function getYearsAvailable()
    {
        $currentYear = Carbon::now()->year;

        $yearsAvailable = collect();
        for($i = 1; $i <= 3; $i++) {
            if(!$this->checkSession($currentYear)){
                $yearsAvailable->push($currentYear);
            }

            $currentYear += 1;
        }

        return $yearsAvailable;
    }

    public function getCurrentForm()
    {
        $session = $this->getCurrentSession();

        return $session->form;
    }

    public function getForms()
    {
        return Form::all();
    }
}
