@extends('admin.layouts.app')

@section('styles')
    <link rel="stylesheet" href="https://cdn.datatables.net/1.12.1/css/dataTables.bootstrap5.min.css">
@endsection

@section('content')
    <h3 class="text-dark mb-1">Reviewer Management - Reviewer List</h3>

    <div class="card">
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-bordered" id="table_id">
                    <thead class="table-primary">
                        <tr>
                            <th style="width:40%">Name</th>
                            <th>Email</th>
                            <th>Hired Since</th>
                            <th>No. of Submission Reviewed</th>
                            <th style="width:5%">Active</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($reviewers as $reviewer)
                            <tr>
                                <td><a
                                        href="{{ route('admin.member.reviewer.view', ['id' => $reviewer->id]) }}">{{ $reviewer->participant->name }}</a>
                                </td>
                                <td>{{$reviewer->email}}</td>
                                <td>{{$reviewer->getHiredSince()}}</td>
                                <td>{{$reviewer->submissions->count()}}</td>
                                <td>{{$reviewer->active ? 'Yes' : 'No'}}</td>
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
