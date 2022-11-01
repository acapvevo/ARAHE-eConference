@extends('reviewer.layouts.app')

@section('styles')
    <link href="{{ asset('lib/summernote/summernote-lite.css') }}" rel="stylesheet">
@endsection

@section('content')
    <h3 class="text-dark mb-1">Give Review - Submission Detail</h3>

    <div class="card">
        <div class="card-body">
            <div class="row pt-3 pb-3">
                <div class="col d-flex justify-content-end">
                    <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#giveReviewModal">
                        Give Review
                    </button>
                    &nbsp;&nbsp;&nbsp;
                    <button type="submit" class="btn btn-danger" form="giveReview" value="reject" name="submit">
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
                                    href="{{ route('reviewer.submission.review.download', ['filename' => $submission->paper]) }}">{{ $submission->paper }}</a>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <div class="modal fade" id="giveReviewModal" tabindex="-1" aria-labelledby="giveReviewModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-xl">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title fs-5" id="giveReviewModalLabel">Give Review</h4>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">

                    <form action="{{ route('reviewer.submission.review.update', ['id' => $submission->id]) }}"
                        method="post" id="giveReview" enctype="multipart/form-data">
                        @csrf
                        @method('PATCH')

                        <ul class="nav nav-tabs nav-justified" id="myTab" role="tablist">
                            <li class="nav-item" role="presentation">
                                <button class="nav-link active" id="rubric-tab" data-bs-toggle="tab"
                                    data-bs-target="#rubric-tab-pane" type="button" role="tab"
                                    aria-controls="rubric-tab-pane" aria-selected="true">Rubric</button>
                            </li>
                            <li class="nav-item" role="presentation">
                                <button class="nav-link" id="comment-tab" data-bs-toggle="tab"
                                    data-bs-target="#comment-tab-pane" type="button" role="tab"
                                    aria-controls="comment-tab-pane" aria-selected="false">Comment</button>
                            </li>
                            <li class="nav-item" role="presentation">
                                <button class="nav-link" id="correction-tab" data-bs-toggle="tab"
                                    data-bs-target="#correction-tab-pane" type="button" role="tab"
                                    aria-controls="correction-tab-pane" aria-selected="false">Paper with Correction</button>
                            </li>
                        </ul>

                        <div class="tab-content pt-3" id="myTabContent">
                            <div class="tab-pane fade show active" id="rubric-tab-pane" role="tabpanel"
                                aria-labelledby="rubric-tab" tabindex="0">

                                @error('rubrics')
                                    <div class="card text-bg-danger">
                                        <div class="card-body">
                                            <p class="card-text">{{ $message }}</p>
                                        </div>
                                    </div>
                                    <div class="pt-3"></div>
                                @enderror

                                @error('rubrics.*')
                                    <div class="card text-bg-danger">
                                        <div class="card-body">
                                            <p class="card-text">{{ $message }}</p>
                                        </div>
                                    </div>
                                    <div class="pt-3"></div>
                                @enderror

                                <div class="table-responsive">
                                    <table class="table table-bordered">
                                        <thead class="table-primary">
                                            <tr>
                                                <th>Description</th>
                                                <th style="width: 10%">Mark</th>
                                                <th style="width: 5%">Pass?</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach ($submission->form->rubrics as $rubric)
                                                <tr>
                                                    <td>{{ $rubric->description }}</td>
                                                    <td class="text-center">{{ $rubric->mark }}</td>
                                                    <td>
                                                        <div class="form-check">
                                                            <input class="form-check-input" type="checkbox"
                                                                id="rubrics{{ $rubric->id }}"
                                                                name="rubrics[{{ $rubric->id }}]"
                                                                @checked(old('rubrics.' . $rubric->id, false))>
                                                        </div>
                                                    </td>
                                                </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                </div>

                            </div>
                            <div class="tab-pane fade" id="comment-tab-pane" role="tabpanel"
                                aria-labelledby="comment-tab" tabindex="0">

                                @error('comment')
                                    <div class="card text-bg-danger">
                                        <div class="card-body">
                                            <p class="card-text">{{ $message }}</p>
                                        </div>
                                    </div>
                                    <div class="pt-3"></div>
                                @enderror

                                <textarea id="summernote" name="comment">{!! old('comment') !!}</textarea>
                            </div>

                            <div class="tab-pane fade" id="correction-tab-pane" role="tabpanel"
                                aria-labelledby="correction-tab" tabindex="0">

                                @error('correction')
                                    <div class="card text-bg-danger">
                                        <div class="card-body">
                                            <p class="card-text">{{ $message }}</p>
                                        </div>
                                    </div>
                                    <div class="pt-3"></div>
                                @enderror

                                <div class="mb-3">
                                    <label for="correction" class="form-label">Upload Paper with Correction <small
                                            class="text-muted">(PDF only, Max:
                                            4MB)</small></label>
                                    <input type="file"
                                        class="form-control {{ $errors->has('correction') ? 'is-invalid' : '' }}"
                                        name="correction" id="correction" placeholder="Upload Paper with Correction">
                                </div>

                            </div>
                        </div>

                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                    <button type="submit" class="btn btn-primary" form="giveReview" name="submit"
                        value="save">Save</button>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('scripts')
    <script src="{{ asset('lib/summernote/summernote-lite.js') }}"></script>

    <script>
        $('#summernote').summernote({
            placeholder: 'Give your comment',
            tabsize: 2,
            height: 120
        });
    </script>
@endsection
