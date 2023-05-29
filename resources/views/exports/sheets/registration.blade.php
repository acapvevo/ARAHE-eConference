<table>
    <thead>
        <tr>
            <th rowspan="2">Registration ID</th>
            <th rowspan="2">Register As</th>
            <th rowspan="2">Dietary Preference</th>
            <th rowspan="2">Title</th>
            <th rowspan="2">Name</th>
            <th rowspan="2">Date of Birth</th>
            <th rowspan="2">Email</th>
            <th colspan="3">Institution</th>
            <th colspan="7">Address</th>
            <th colspan="2">Contact</th>
            <th colspan="3">Emergency Person</th>
        </tr>
        <tr>
            <th>University</th>
            <th>Faculty</th>
            <th>Department</th>
            <th>Line 1</th>
            <th>Line 2</th>
            <th>Line 3</th>
            <th>City</th>
            <th>Postcode</th>
            <th>State</th>
            <th>Country</th>
            <th>Phone Number</th>
            <th>Fax Number</th>
            <th>Name</th>
            <th>Email</th>
            <th>Phone Number</th>
        </tr>
    </thead>
    <tbody>
        @foreach ($registrations as $registration)
            <tr>
                <td>{{ $registration->code }}</td>
                <td>{{ $registration->getType()->name }}</td>
                <td>{{ $registration->getDietary()->name }}</td>
                <td>{{ $registration->participant->getTitle() }}</td>
                <td>{{ $registration->participant->name }}</td>
                <td>{{ $registration->participant->date_of_birth->format('j F Y') }}</td>
                <td>{{ $registration->participant->email }}</td>
                <td>{{ $registration->participant->institution->name }}</td>
                <td>{{ $registration->participant->institution->faculty }}</td>
                <td>{{ $registration->participant->institution->department }}</td>
                <td>{{ $registration->participant->address->lineOne }}</td>
                <td>{{ $registration->participant->address->lineTwo }}</td>
                <td>{{ $registration->participant->address->lineThree }}</td>
                <td>{{ $registration->participant->address->city }}</td>
                <td>{{ $registration->participant->address->postcode }}</td>
                <td>{{ $registration->participant->address->state }}</td>
                <td>{{ $registration->participant->address->country }}</td>
                <td>{{ $registration->participant->contact->phoneNumber }}</td>
                <td>{{ $registration->participant->contact->faxNumber }}</td>
                <td>{{ $registration->participant->emergency->name }}</td>
                <td>{{ $registration->participant->emergency->email }}</td>
                <td>{{ $registration->participant->emergency->phoneNumber }}</td>
            </tr>
        @endforeach
    </tbody>
</table>
