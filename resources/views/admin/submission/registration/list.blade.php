@extends('admin.layouts.app')

@section('styles')
    <link rel="stylesheet" href="https://cdn.datatables.net/1.12.1/css/dataTables.bootstrap5.min.css">
@endsection

@section('content')
    <h3 class="text-dark mb-1">Registration - Participant List</h3>

    <div class="card">
        <h4 class="card-header text-center">
            ARAHE {{ $form->session->year }}
        </h4>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-bordered" id="table_id">
                    <thead class="table-primary">
                        <tr>
                            <th>Participant</th>
                            <th>Registration ID</th>
                            <th>Register As</th>
                            <th>Status</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($registrations as $registration)
                            <tr>
                                <td style="width:30%"><a
                                        href="{{ route('admin.submission.registration.view', ['id' => $registration->id]) }}">{{ $registration->participant->name }}</a>
                                </td>
                                <td>{{ $registration->code }}</td>
                                <td>{{ strtoupper($registration->register_as) }}</td>
                                <td>{{ $registration->getStatusLabel() }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
@endsection

@section('scripts')
    <script src="https://cdn.datatables.net/1.12.1/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/1.12.1/js/dataTables.bootstrap5.min.js"></script>

    <script>
        $(document).ready(function() {
            $('#table_id').DataTable({
                "order": [],
                "autoWidth": false,
                "columns": [{
                        "width": "5%"
                    }, {
                        "width": "40%"
                    },
                    null,
                    null
                ]
            });
        });
    </script>
@endsection
