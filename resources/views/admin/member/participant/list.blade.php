@extends('admin.layouts.app')

@section('styles')
    <link rel="stylesheet" href="https://cdn.datatables.net/1.12.1/css/dataTables.bootstrap5.min.css">
@endsection

@section('content')
    <h3 class="text-dark mb-1">Participant Management - Participant List</h3>

    <div class="card">
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-bordered" id="table_id">
                    <thead class="table-primary">
                        <tr>
                            <th style="width:40%">Name</th>
                            <th>Email</th>
                            <th>Joined Since</th>
                            <th style="width:10%">Participation</th>
                            <th style="width:5%">Reviewer</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($participants as $participant)
                            <tr>
                                <td><a
                                        href="{{ route('admin.member.participant.view', ['id' => $participant->id]) }}">{{ $participant->name }}</a>
                                </td>
                                <td>{{$participant->email}}</td>
                                <td>{{$participant->getJoinedSince()}}</td>
                                <td>{{$participant->submissions->count()}} time(s)</td>
                                <td>{{isset($participant->reviewer) ? 'Yes' : 'No'}}</td>
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
