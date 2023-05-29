<table>
    <thead>
        <tr>
            <th>Registration ID</th>
            <th>Participant</th>
            <th>Package</th>
            <th>Extras</th>
            <th>Hotel</th>
        </tr>
    </thead>
    <tbody>
        @foreach ($summaries as $summary)
            <tr>
                <td>{{ $summary->registration->code }}</td>
                <td>{{ $summary->registration->participant->name }}</td>
                <td>{{ $summary->getPackage()->code }}</td>
                <td>
                    @if ($summary->extras->isNotEmpty())
                        <ul>
                            @foreach ($summary->extras as $extra)
                                @php
                                    $extraInfo = DB::table('extras')
                                        ->where('id', $extra['id'])
                                        ->first();
                                    $options = collect(json_decode($extraInfo->options));
                                @endphp
                                <li> {{ $extraInfo->description }}
                                    {{ $options->isNotEmpty() ? ' - ' . $options[$extra['option']] : '' }}
                                </li>
                            @endforeach
                        </ul>
                    @else
                        Not Included
                    @endif
                </td>
                @if ($summary->getPackage()->fullPackage)
                    <td colspan="2">Included in Package
                        {{ $summary->getPackage()->code }}</td>
                @elseif (!$summary->hotel_id && !$summary->occupancy_id)
                    <td>Not Included</td>
                @else
                    <td>{{ $summary->getHotel()->code }} -
                        {{ $summary->getOccupancy()->type }}</td>
                @endif
            </tr>
        @endforeach
    </tbody>
</table>
