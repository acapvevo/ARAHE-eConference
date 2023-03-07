@extends('reviewer.layouts.app')

@section('styles')
    <link href="{{ asset('lib/summernote/summernote-lite.css') }}" rel="stylesheet">
@endsection

@section('content')
    <h3 class="text-dark mb-1">Give Review - Submission Detail</h3>

    <div class="card">
        <div class="card-body">
            <div class="row pt-3 pb-3">
                <div class="col d-grid gap-2 d-md-flex justify-content-md-end">

                    <button type="submit" class="btn btn-danger" form="giveReview" name="submit" value="reject"
                        @disabled($submission->status_code !== 'IR')>
                        Reject Submission
                    </button>

                    <button type="submit" class="btn btn-warning" form="giveReview" name="submit" value="return"
                        @disabled($submission->status_code !== 'IR')>
                        Re-assign
                    </button>

                    <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#giveReviewModal"
                        @disabled($submission->status_code !== 'IR')>
                        Give Review
                    </button>
                </div>
            </div>
            <div class="table-responsive">
                <table class="table table-bordered">
                    <tbody>
                        <tr>
                            <th class='w-25'>Registration ID</th>
                            <td colspan="2">{{ $submission->registration->code }}</td>
                        </tr>
                        <tr>
                            <th class='w-25'>Title</th>
                            <td colspan="2">{{ $submission->title }}</td>
                        </tr>
                        @forelse ($submission->authors ?? [] as $index => $author)
                            <tr>
                                @if ($index == 0)
                                    <th class='w-25' rowspan="{{ $submission->authors->count() }}">Authors</th>
                                @endif
                                <td>{{ $author['name'] }}</td>
                                <td>{{ $author['email'] }}</td>
                            </tr>
                        @empty
                            <tr>
                                <th class='w-25'>Authors</th>
                                <td colspan="2"></td>
                            </tr>
                        @endforelse
                        @forelse ($submission->coAuthors ?? [] as $index => $coAuthor)
                            <tr>
                                @if ($index == 0)
                                    <th class='w-25' rowspan="{{ $submission->coAuthors->count() }}">Co-Authors</th>
                                @endif
                                <td>{{ $coAuthor['name'] }}</td>
                                <td>{{ $coAuthor['email'] }}</td>
                            </tr>
                        @empty
                            <tr>
                                <th class='w-25'>Co-Authors</th>
                                <td colspan="2"></td>
                            </tr>
                        @endforelse
                        <tr>
                            <th class='w-25'>Presenter</th>
                            <td colspan="2">{{ $submission->presenter }}</td>
                        </tr>
                        <tr>
                            <th class='w-25'>Abstract</th>
                            <td colspan="2">{!! $submission->abstract !!}</td>
                        </tr>
                        <tr>
                            <th class='w-25'>Abstract File</th>
                            @if (isset($submission->abstractFile))
                                <td colspan="2">
                                    <form action="{{ route('reviewer.submission.review.download') }}" method="post"
                                        target="_blank">
                                        @csrf
                                        <input type="hidden" name="type" value="abstractFile">
                                        <input type="hidden" name="filename" value="{{ $submission->abstractFile }}">
                                        <button type="submit" class="btn btn-link" name="submission_id"
                                            value="{{ $submission->id }}">{{ $submission->abstractFile }}</button>
                                    </form>
                                </td>
                            @else
                                <td colspan="2"></td>
                            @endif
                        </tr>
                        <tr>
                            <th class='w-25'>Keywords</th>
                            <td colspan="2">{{ $submission->keywords }}</td>
                        </tr>
                        <tr>
                            <th class='w-25'>Paper File</th>
                            @if (isset($submission->paperFile))
                                <td colspan="2">
                                    <form action="{{ route('reviewer.submission.review.download') }}" method="post"
                                        target="_blank">
                                        @csrf
                                        <input type="hidden" name="type" value="paperFile">
                                        <input type="hidden" name="filename" value="{{ $submission->paperFile }}">
                                        <button type="submit" class="btn btn-link" name="submission_id"
                                            value="{{ $submission->id }}">{{ $submission->paperFile }}</button>
                                    </form>
                                </td>
                            @else
                                <td colspan="2"></td>
                            @endif
                        </tr>
                        <tr>
                            <th class='w-25'>Status</th>
                            <td colspan="2">{{ $submission->getStatusDescription() }}</td>
                        </tr>
                        <tr>
                            <th class="text-center table-primary" colspan='3'>Review</th>
                        </tr>
                        <tr>
                            <th class='w-25'>Reviewer</th>
                            <td colspan="2">{{ $submission->reviewer->participant->name ?? '' }}</td>
                        </tr>
                        <tr>
                            <th class='w-25'>Total Mark</th>
                            <td colspan="2">
                                {{ $submission->calculatePercentage() === 0 ? '' : number_format($submission->calculatePercentage(), 2) . '%' }}
                            </td>
                        </tr>
                        <tr>
                            <th class='w-25'>Comment</th>
                            <td colspan="2">{!! $submission->comment ?? '' !!}</td>
                        </tr>
                        <tr>
                            <th class='w-25'>Paper with Correction</th>
                            @if (isset($submission->correctionFile))
                                <td colspan="2">
                                    <form action="{{ route('reviewer.submission.review.download') }}" method="post"
                                        target="_blank">
                                        @csrf
                                        <input type="hidden" name="type" value="correctionFile">
                                        <input type="hidden" name="filename" value="{{ $submission->correctionFile }}">
                                        <button type="submit" class="btn btn-link" name="submission_id"
                                            value="{{ $submission->id }}">{{ $submission->correctionFile }}</button>
                                    </form>
                                </td>
                            @else
                                <td colspan="2"></td>
                            @endif
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <div class="modal fade" id="giveReviewModal" tabindex="-1" aria-labelledby="giveReviewModalLabel"
        aria-hidden="true">
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
                                    aria-controls="correction-tab-pane" aria-selected="false">Paper with
                                    Correction</button>
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

                                @php
                                    $scales = DB::table('scale')->get();
                                    $totalMark = 0;
                                @endphp

                                <div class="table-responsive">
                                    <table class="table table-bordered">
                                        <thead class="table-primary">
                                            <tr>
                                                <th style="width: 60%" class="align-middle text-center" rowspan="2">
                                                    Description</th>
                                                <th class="align-middle text-center" colspan="5">Scale</th>
                                            </tr>
                                            <tr>
                                                @foreach ($scales as $scale)
                                                    <th class="text-center">{{ $scale->mark }}</th>
                                                @endforeach
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach ($submission->registration->form->rubrics as $index => $rubric)
                                                <tr>
                                                    <td>{{ $rubric->description }}</td>
                                                    @foreach ($scales as $scale)
                                                        <td>
                                                            <div class="form-check text-center">
                                                                <input
                                                                    class="form-check-input float-none rubrics rubric{{ $index }}"
                                                                    type="radio" value="{{ $scale->code }}"
                                                                    mark={!! $scale->mark !!}
                                                                    name="rubrics[{{ $rubric->id }}]"
                                                                    id="rubrics{{ $rubric->id }}_{{ $scale->id }}"
                                                                    @checked(old('rubrics.' . $rubric->id) == $scale->code)>
                                                            </div>
                                                        </td>
                                                    @endforeach
                                                </tr>
                                                @php
                                                    $totalMark += $scale->mark;
                                                @endphp
                                            @endforeach
                                            <tr>
                                                <th>
                                                    <div class="d-flex justify-content-end">
                                                        TOTAL SCORE
                                                    </div>
                                                <td colspan="{{ $scales->count() }}" id="total">0/{{$totalMark}}</td>
                                                </th>
                                            </tr>
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
                                            class="text-muted">(PDF, DOC, and DOCX only) (Max Size:
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
                    <button type="reset" class="btn btn-secondary" form="giveReview">Reset</button>
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
        $("document").ready(function() {
            $('#summernote').summernote({
                placeholder: 'Give your comment',
                tabsize: 2,
                height: 120
            });

            updateTotalScore();
        });

        const getAllScaleInput = document.getElementsByClassName('rubrics');

        Array.from(getAllScaleInput).forEach(function(el) {
            el.addEventListener("change", function(event) {
                updateTotalScore();
            });
        });

        function updateTotalScore() {
            const rubricCount = {{ $submission->registration->form->rubrics->count() }};
            const totalEl = document.getElementById('total');

            let total = 0;
            for (let index = 0; index < rubricCount; index++) {
                let chosenScale = $(".rubric" + index + ":checked ").attr('mark') ?? 0;

                total += parseInt(chosenScale);
            }

            const percentage = total/parseInt({{$totalMark}}) * 100;
            if(percentage >= 80){
                totalEl.innerHTML = total + '/{{$totalMark}} (' + percentage.toFixed(2) + '%) <span class="badge bg-success">Excellent</span>';
            } else {
                totalEl.innerHTML = total + '/{{$totalMark}} (' + percentage.toFixed(2) + '%) <span class="badge bg-danger">Need Correction</span>';
            }
        }
    </script>

    @if ($errors->any())
        <script>
            const giveReviewModal = new bootstrap.Modal('#giveReviewModal')
            giveReviewModal.show();
        </script>
    @endif
@endsection
