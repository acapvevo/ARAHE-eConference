<?php

namespace App\Traits;

use App\Models\Registration;
use Illuminate\Support\Facades\Http;

trait RegistrationTrait
{
    public function getRegistrationByParticipantID($participant_id)
    {
        return Registration::with(['form'])->where('participant_id', $participant_id)->get();
    }

    public function getRegistration($id)
    {
        return Registration::find($id);
    }

    public function getRegistrationsCompletedByFormID($form_id)
    {
        return Registration::where('status_code', 'AR')->where('form_id', $form_id)->get();
    }
}
