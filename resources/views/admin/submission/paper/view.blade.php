@extends('admin.layouts.app')

@section('styles')
    <link rel="stylesheet" href="https://cdn.datatables.net/1.12.1/css/dataTables.bootstrap5.min.css">
@endsection

@section('content')
    <h3 class="text-dark mb-1">Paper - Submission Detail</h3>

    <div class="card">
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-bordered">
                    <tbody>
                        <tr>
                            <th class='w-25'>Title</th>
                            <td>{{ $submission->title }}</td>
                        </tr>
                        <tr>
                            <th class='w-25'>Abstract</th>
                            <td>{{ $submission->abstract }}</td>
                        </tr>
                        <tr>
                            <th class='w-25'>Paper</th>
                            @if (isset($submission->paper))
                                <td>
                                    <form action="{{ route('admin.submission.paper.download') }}"
                                        method="post" target="_blank">
                                        @csrf
                                        <input type="hidden" name="type" value="paper">
                                        <input type="hidden" name="filename"
                                            value="{{ $submission->paper }}">
                                        <button type="submit" class="btn btn-link" name="submission_id"
                                            value="{{ $submission->id }}">{{ $submission->paper }}</button>
                                    </form>
                                </td>
                            @else
                                <td></td>
                            @endif
                        </tr>
                        <tr>
                            <th class='w-25'>Status</th>
                            <td>{{ $submission->getStatusDescription() }}</td>
                        </tr>
                        <tr>
                            <th class="text-center" colspan='2'>Participant</th>
                        </tr>
                        <tr>
                            <th class='w-25'>Name</th>
                            <td>{{ $submission->participant->name ?? '' }}</td>
                        </tr>
                        <tr>
                            <th class='w-25'>Email</th>
                            <td>{{ $submission->participant->email ?? '' }}</td>
                        </tr>
                        <tr>
                            <th class="text-center" colspan='2'>Review</th>
                        </tr>
                        <tr>
                            <th class='w-25'>Reviewer</th>
                            <td>{{ $submission->reviewer->participant->name ?? '' }}</td>
                        </tr>
                        <tr>
                            <th class='w-25'>Total Mark</th>
                            <td>{{ $submission->calculatePercentage() === 0 ? '' : number_format($submission->calculatePercentage(), 2) . '%' }}</td>
                        </tr>
                        <tr>
                            <th class='w-25'>Comment</th>
                            <td>{!! $submission->comment ?? '' !!}</td>
                        </tr>
                        <tr>
                            <th class='w-25'>Paper with Correction</th>
                            @if (isset($submission->correction))
                                <td>
                                    <form action="{{ route('admin.submission.paper.download') }}"
                                        method="post" target="_blank">
                                        @csrf
                                        <input type="hidden" name="type" value="correction">
                                        <input type="hidden" name="filename"
                                            value="{{ $submission->correction }}">
                                        <button type="submit" class="btn btn-link"
                                            name="submission_id"
                                            value="{{ $submission->id }}">{{ $submission->correction }}</button>
                                    </form>
                                </td>
                            @else
                                <td></td>
                            @endif
                        </tr>
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
                "autoWidth": false,
                "columns": [{
                        "width": "5%"
                    },
                    null,
                    {
                        "width": "30%"
                    }
                ]
            });
        });
    </script>
@endsection
