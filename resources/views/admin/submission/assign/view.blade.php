@extends('admin.layouts.app')

@section('styles')
    <link rel="stylesheet" href="https://cdn.datatables.net/1.12.1/css/dataTables.bootstrap5.min.css">
@endsection

@section('content')
    <h3 class="text-dark mb-1">Assign Reviewer - Submission Detail</h3>

    <div class="card">
        <div class="card-body">
            <div class="row pt-3 pb-3">
                <div class="col d-flex justify-content-end">
                    <button type="button" class="btn btn-primary" data-bs-toggle="modal"
                        data-bs-target="#assignReviewerModal">
                        Assign Reviewer
                    </button>
                    &nbsp;&nbsp;&nbsp;
                    <button type="submit" class="btn btn-danger" form="assignReviewer" value="reject" name="submit">
                        Reject Submission
                    </button>
                </div>
            </div>
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
                            <td><a target="_blank"
                                    href="{{ route('admin.submission.assign.download', ['filename' => $submission->paper]) }}">{{ $submission->paper }}</a>
                            </td>
                        </tr>
                        <tr>
                            <th class='w-25'>Status</th>
                            <td>{{ $submission->getStatusDescription() }}</td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <div class="modal fade" id="assignReviewerModal" tabindex="-1" aria-labelledby="assignReviewerModalLabel"
        aria-hidden="true">
        <div class="modal-dialog modal-xl">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title fs-5" id="assignReviewerModalLabel">Assign Reviewer</h4>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">

                    @error('reviewer_id')
                    <div class="card text-bg-danger mb-3">
                      <div class="card-body">
                        <p class="card-text text-white">Please Choose a Reviewer</p>
                      </div>
                    </div>
                    <div class="pt-3 pb-3"></div>
                    @enderror

                    <form action="{{ route('admin.submission.assign.update', ['id' => $submission->id]) }}" method="post"
                        id="assignReviewer">
                        @csrf
                        @method('PATCH')

                        <div class="table-responsive">
                            <table class="table table-bordered" id="table_id">
                                <thead class="table-primary">
                                    <tr>
                                        <th style="width:5%">#</th>
                                        <th>Reviewer</th>
                                        <th style="width:15%">No. of Submission Assigned for
                                            {{ $submission->form->session->year }}</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($reviewers as $index => $reviewer)
                                        @php
                                            $submissions = $reviewer->submissions->filter(function ($currentSubmission) use ($submission) {
                                                return $currentSubmission->form->session->id === $submission->form->session->id;
                                            });
                                        @endphp

                                        <tr>
                                            <td>
                                                <div class="form-check">
                                                    <input class="form-check-input" type="radio" name="reviewer_id"
                                                        id="reviewer_id{{ $index }}" value="{{ $reviewer->id }}">
                                                </div>
                                            </td>
                                            <td>{{ $reviewer->participant->name }}</td>
                                            <td class="text-center">{{ $reviewer->submissions->count() }}</td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>

                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                    <button type="submit" class="btn btn-primary" form="assignReviewer" name="submit"
                        value="assign">Assign</button>
                </div>
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
