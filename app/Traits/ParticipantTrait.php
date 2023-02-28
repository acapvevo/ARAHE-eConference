<?php

namespace App\Traits;

use App\Models\Participant;

trait ParticipantTrait
{
    public function searchParticipant($searchTerm, $currentParticipant)
    {
        return Participant::select('name AS text', 'id')->whereNot('id', $currentParticipant->id)->where('name', 'LIKE', '%' . $searchTerm . '%')
            ->orderBy('name', 'asc')->paginate(10);
    }

    public function getParticipant($id)
    {
        return Participant::find($id);
    }
}
