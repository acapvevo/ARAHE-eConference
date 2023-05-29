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
                </td>
                @if ($summary->getPackage()->fullPackage)
                    <td>Included in Package
                        {{ $summary->getPackage()->code }}</td>
                @else
                    <td>{{ $summary->getHotel()->code }} - {{ $summary->getOccupancy()->type }}</td>
                @endif
            </tr>
        @endforeach
    </tbody>
</table>
