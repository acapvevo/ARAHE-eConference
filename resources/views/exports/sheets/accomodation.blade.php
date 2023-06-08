<table>
    <thead>
        <tr>
            <th>Registration ID</th>
            <th>Participant</th>
            <th>Code</th>
            <th>Occupancy</th>
            <th>Accompany</th>
        </tr>
    </thead>
    <tbody>
        @foreach ($summaries as $summary)
            <tr>
                <td>{{ $summary->registration->code }}</td>
                <td>{{ $summary->registration->participant->name }}</td>
                @if ($summary->getPackage()->fullPackage)
                    <td colspan="2">Included in Package
                        {{ $summary->getPackage()->code }}</td>
                    <td style="display: none"></td>
                @else
                    <td>{{ $summary->getHotel()->code }}</td>
                    <td>{{ $summary->getOccupancy()->type }}</td>
                @endif
                <td>
                    @if ($summary->registration->linkParticipant)
                        {{ $summary->registration->linkParticipant->getRegistrationIDByFormID($summary->registration->form_id) }}
                    @else
                        N/A
                    @endif
                </td>
            </tr>
        @endforeach
    </tbody>
</table>
