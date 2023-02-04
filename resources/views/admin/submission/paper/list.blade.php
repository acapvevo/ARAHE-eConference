@extends('admin.layouts.app')

@section('styles')
    <link rel="stylesheet" href="https://cdn.datatables.net/1.12.1/css/dataTables.bootstrap5.min.css">
@endsection

@section('content')
    <h3 class="text-dark mb-1">Paper - Paper List</h3>

    <div class="card">
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-bordered" id="table_id">
                    <thead class="table-primary">
                        <tr>
                            <th style="width:5%">Year</th>
                            <th>Title</th>
                            <th>Participant</th>
                            <th>Reviewer</th>
                            <th>Status</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($submissions as $submission)
                            <tr>
                                <td>{{$submission->form->session->year}}</td>
                                <td style="width:30%"><a
                                        href="{{ route('admin.submission.paper.view', ['id' => $submission->id]) }}">{{ $submission->title }}</a>
                                </td>
                                <td>{{ $submission->participant->name }}</td>
                                <td>{{ isset($submission->reviewer) ? $submission->reviewer->participant->name : 'No Assigned Reviewer' }}
                                </td>
                                <td>{{ $submission->getStatusLabel() }}</td>
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
