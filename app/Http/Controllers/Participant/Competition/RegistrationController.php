<?php

namespace App\Http\Controllers\Participant\Competition;

use App\Models\Submission;
use App\Models\Registration;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;

class RegistrationController extends Controller
{
    public function list()
    {
        $forms = $this->getForms()->sortByDesc('year');
        $participant = Auth::guard('participant')->user();

        foreach ($forms as $form) {
            if (Registration::where('form_id', $form->id)->where('participant_id', $participant->id)->exists()) {
                $registration = Registration::where('form_id', $form->id)->where('participant_id', $participant->id)->first();
                $form->setAttribute('registration', $registration);
            }
        }

        return view('participant.competition.registrations.list')->with([
            'forms' => $forms,
        ]);
    }
}
