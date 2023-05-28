<?php

namespace App\Traits;

use App\Models\Summary;

trait SummaryTrait
{
    public function getSummary($id)
    {
        return Summary::find($id);
    }

    public function getSummariesByFormID($form_id)
    {
        return Summary::all()->filter(function ($summary) use ($form_id) {
            return $summary->registration->form_id == $form_id;
        });
    }
}
