<table>
    <thead>
        <tr>
            <th>Registration ID</th>
            <th>Title</th>
            <th>Name</th>
            <th>Package</th>
            <th>Amount Paid</th>
            <th>Payment Date</th>
        </tr>
    </thead>
    <tbody>
        @foreach ($registrations as $registration)
            <tr>
                <td>{{ $registration->code }}</td>
                <td>{{ $registration->participant->getTitle() }}</td>
                <td>{{ $registration->participant->name }}</td>
                <td>{{ $registration->summary->getPackage()->code }}</td>
                <td>{{ $registration->summary->getFormalOutputTotal() }}</td>
                <td>{{ $registration->summary->getSuccessPaidBill()->getPayCompleteAt() }}</td>
            </tr>
        @endforeach
    </tbody>
</table>
