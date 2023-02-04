@extends('reviewer.layouts.app')

@section('styles')
    <link rel="stylesheet" href="https://cdn.datatables.net/1.12.1/css/dataTables.bootstrap5.min.css">
@endsection

@section('content')
    <h3 class="text-dark mb-1">Give Review - Submission List</h3>

    <div class="card">
        <h4 class="card-header text-center">Submission for ARAHE{{ $form->session->year }}</h4>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-bordered" id="table_id">
                    <thead class="table-primary">
                        <tr>
                            <th>Registration ID</th>
                            <th>Name</th>
                            <th>Title</th>
                            <th>Status</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($submissions as $submission)
                            <tr>
                                <td style="width:50%"><a
                                        href="{{ route('reviewer.submission.review.view', ['id' => $submission->id]) }}">{{ $submission->registration->code }}</a>
                                </td>
                                <td>{{$submission->registration->participant->name}}</td>
                                <td>{{$submission->title}}</td>
                                <td>{{$submission->status_code === 'IR'}}</td>
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
