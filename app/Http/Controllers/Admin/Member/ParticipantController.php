<?php

namespace App\Http\Controllers\Admin\Member;

use App\Models\Participant;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Traits\ReviewerTrait;
use App\Traits\SubmissionTrait;

class ParticipantController extends Controller
{
    use ReviewerTrait, SubmissionTrait;

    public function list()
    {
        $participants = Participant::all()->load('submissions', 'reviewer');

        return view('admin.member.participant.list')->with([
            'participants' => $participants,
        ]);
    }

    public function view($id)
    {
        $participant = Participant::find($id);

        return view('admin.member.participant.view')->with([
            'participant' => $participant,
        ]);
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'submit' => 'required|string|in:reviewer,inactive',
        ]);

        $participant = Participant::find($id);

        if($request->submit === 'reviewer'){
            $this->upgradeToReviewer($participant);
            $message = 'This participant has been successfully upgraded to a Reviewer';
        } else if($request->submit === 'inactive'){
            $message = 'This participant has been successfully updated to as inactive';
        }

        $participant->save();

        return redirect(route('admin.member.participant.view', array('id' => $participant->id)))->with('success', $message);
    }

    public function download(Request $request)
    {
        $request->validate([
            'submission_id' => 'required|integer|exists:App\Models\Submission,id',
            'type' => 'required|string|in:paper,correction',
            'filename' => 'required|required'
        ]);

        $submission = $this->getSubmission($request->submission_id);
        return $this->getPaper($request->type, $request->filename, $submission);
    }
}
