@extends('participant.layouts.app')

@section('styles')
@endsection

@section('content')
    <h3 class="text-dark mb-1">Submission - Submission Detail</h3>

    <div class="card">
        <div class="card-body">
            <div class="row pt-3 pb-3">
                <div class="col">
                    @if ($submission->status_code === 'WP')
                    <button type="button" class="btn btn-success float-end">
                        Proceed to Payment
                    </button>
                    @else
                    <button type="button" class="btn btn-primary float-end" data-bs-toggle="modal"
                        data-bs-target="#updateSubmissionModal" {{ $submission->checkEnableSubmit() ? '' : 'disabled' }}>
                        Update Submission
                    </button>
                    @endif
                </div>
            </div>
            <div class="table-responsive">
                <table class="table table-bordered" id="table_id">
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
                                <td><a target="_blank"
                                        href="{{ route('participant.competition.submission.download', ['filename' => $submission->paper, 'type' => 'paper']) }}">{{ $submission->paper }}</a>
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
                            <th class="text-center" colspan='2'>Review</th>
                        </tr>
                        <tr>
                            <th class='w-25'>Reviewer</th>
                            <td>{{ $submission->reviewer->participant->name ?? '' }}</td>
                        </tr>
                        <tr>
                            <th class='w-25'>Mark</th>
                            <td>{{ $submission->mark === 0 ? '' : $submission->mark }}</td>
                        </tr>
                        <tr>
                            <th class='w-25'>Comment</th>
                            <td>{!! $submission->comment ?? '' !!}</td>
                        </tr>
                        <tr>
                            <th class='w-25'>Paper with Correction</th>
                            @if (isset($submission->correction))
                                <td><a target="_blank"
                                        href="{{ route('participant.competition.submission.download', ['filename' => $submission->correction, 'type' => 'correction']) }}">{{ $submission->correction }}</a>
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

    <div class="modal fade" id="updateSubmissionModal" tabindex="-1" aria-labelledby="updateSubmissionModalLabel"
        aria-hidden="true">
        <div class="modal-dialog modal-xl">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title fs-5" id="updateSubmissionModalLabel">Update Submission</h4>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form action="{{ route('participant.competition.submission.update', ['id' => $submission->id]) }}"
                        method="post" id="updateSubmission" enctype="multipart/form-data">
                        @csrf
                        @method('PATCH')

                        <div class="mb-3">
                            <label for="title" class="form-label">Title</label>
                            <input type="text" class="form-control {{ $errors->has('title') ? 'is-invalid' : '' }}"
                                name="title" id="title" placeholder="Enter Paper title"
                                value="{{ old('title', $submission->title) }}">
                            @error('title')
                                <div class="invalid-feedback">
                                    {{ $message }}
                                </div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label for="abstract" class="form-label">Abstract</label>
                            <textarea class="form-control {{ $errors->has('abstract') ? 'is-invalid' : '' }}" rows="5" name="abstract"
                                id="abstract" placeholder="Enter Paper Abstract" required>{{ old('abstract', $submission->abstract) }}</textarea>
                            @error('abstract')
                                <div class="invalid-feedback">
                                    {{ $message }}
                                </div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label for="paper" class="form-label">Paper <small class="text-muted">(PDF only, Max:
                                    4MB)</small></label>
                            <input type="file" class="form-control {{ $errors->has('paper') ? 'is-invalid' : '' }}"
                                name="paper" id="paper" placeholder="Insert Paper" required>
                            @error('paper')
                                <div class="invalid-feedback">
                                    {{ $message }}
                                </div>
                            @enderror
                        </div>

                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                    <button type="submit" class="btn btn-primary" form="updateSubmission">Save</button>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('scripts')
@endsection
