@extends('participant.layouts.app')

@section('styles')
    <link rel="stylesheet" href="https://cdn.datatables.net/1.12.1/css/dataTables.bootstrap5.min.css">
@endsection

@section('content')
    <h3 class="text-dark mb-1">Submission - Form List</h3>

    <div class="card">
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-bordered" id="table_id">
                    <thead class="table-primary">
                        <tr>
                            <th>Year</th>
                            <th>Title</th>
                            <th>Status</th>
                            <th>Date Accepted</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($registrations as $registration)
                            <tr>
                                <td><a href="{{route('participant.competition.submission.view', ['registration_id' => $registration->id])}}">{{ $registration->form->session->year }}</a></td>
                                <td>{{isset($registration->submission->title) ? $registration->submission->title : 'No Submission'}}</td>
                                <td>{{isset($registration->submission->status_code) ? $registration->submission->getStatusLabel() : 'No Submission'}}</td>
                                <td>{{isset($registration->submission->acceptedDate) ? $registration->submission->acceptedDate->translatedFormat('j F Y') : ''}}</td>
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
            $('#table_id').DataTable();
        });
    </script>
@endsection
