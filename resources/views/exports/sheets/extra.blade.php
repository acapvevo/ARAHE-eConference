<table>
    <thead>
        <tr>
            <th>Registration ID</th>
            <th>Participant</th>
            <th>Extras</th>
        </tr>
    </thead>
    <tbody>
        @foreach ($summaries as $summary)
            <tr>
                <td>{{ $summary->registration->code }}</td>
                <td>{{ $summary->registration->participant->name }}</td>
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
            </tr>
        @endforeach
    </tbody>
</table>
