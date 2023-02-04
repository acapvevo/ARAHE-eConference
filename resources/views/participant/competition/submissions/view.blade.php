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
                        <form action="{{ route('participant.payment.pay.main') }}" method="post">
                            @csrf

                            <button type="submit" name="submission_id" value="{{ $submission->id }}"
                                class="btn btn-success float-end">
                                Proceed to Payment
                            </button>
                        </form>
                    @elseif ($submission->status_code === 'N')
                        <button type="button" class="btn btn-primary float-end" data-bs-toggle="modal"
                            data-bs-target="#createSubmissionModal">
                            Create Submission
                        </button>
                    @else
                        <button type="button" class="btn btn-primary float-end" data-bs-toggle="modal"
                            data-bs-target="#updateSubmissionModal"
                            {{ $submission->checkEnableSubmit() ? '' : 'disabled' }}>
                            Update Submission
                        </button>
                    @endif
                </div>
            </div>
            <div class="table-responsive">
                <table class="table table-bordered" id="table_id">
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
                                <td  colspan="2"></td>
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
                            <td colspan="2">{{ $submission->abstract }}</td>
                        </tr>
                        <tr>
                            <th class='w-25'>Abstract File</th>
                            @if (isset($submission->abstractFile))
                                <td colspan="2">
                                    <form action="{{ route('participant.competition.submission.download') }}"
                                        method="post" target="_blank">
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
                                    <form action="{{ route('participant.competition.submission.download') }}"
                                        method="post" target="_blank">
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
                            <th class="text-center" colspan='3'>Review</th>
                        </tr>
                        <tr>
                            <th class='w-25'>Reviewer</th>
                            <td colspan="2">{{ $submission->reviewer->participant->name ?? '' }}</td>
                        </tr>
                        <tr>
                            <th class='w-25'>Total Mark</th>
                            <td colspan="2">{{ $submission->calculatePercentage() === 0 ? '' : number_format($submission->calculatePercentage(), 2) . '%' }}
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
                                    <form action="{{ route('participant.competition.submission.download') }}"
                                        method="post" target="_blank">
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

    <div class="modal fade" id="createSubmissionModal" tabindex="-1" aria-labelledby="createSubmissionModalLabel"
        aria-hidden="true">
        <div class="modal-dialog modal-xl">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title fs-5" id="createSubmissionModalLabel">Create Submission</h4>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form action="{{ route('participant.competition.submission.create') }}" method="post"
                        id="createSubmission" enctype="multipart/form-data">
                        @csrf

                        <input type="hidden" name="registration_id" value="{{ $submission->registration->id }}">

                        <div class="mb-3">
                            <label for="code" class="form-label">Registration ID</label>
                            <input type="text" readonly class="form-control-plaintext" name="code"
                                id="code"
                                value="{{ old('code', $submission->registration->code) }}">
                        </div>

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
                            <label for="authors" class="form-label">Authors</label>
                            <div class="table-responsive" id="authors">
                                <table class="table table-bordered" id="tableAuthors">
                                    <thead class="table-primary">
                                        <tr>
                                            <th>Author Name</th>
                                            <th>Author Email</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach (old('authors', $submission->authors ?? []) as $index => $author)
                                            <tr>
                                                <td>
                                                    <input type="text" name="authors[{{ $index }}][name]"
                                                        id="authors.{{ $index }}.name"
                                                        class="form-control {{ $errors->has('authors.' . $index . '.name') ? 'is-invalid' : '' }}"
                                                        placeholder="Enter Author {{ $index + 1 }} Name"
                                                        value="{{ $author['name'] }}">
                                                    @error('authors.' . $index . '.name')
                                                        <div class="invalid-feedback">
                                                            {{ $message }}
                                                        </div>
                                                    @enderror
                                                </td>
                                                <td>
                                                    <input type="email" name="authors[{{ $index }}][email]"
                                                        id="authors.{{ $index }}.email"
                                                        class="form-control {{ $errors->has('authors.' . $index . '.email') ? 'is-invalid' : '' }}"
                                                        placeholder="Enter Author {{ $index + 1 }} Email"
                                                        value="{{ $author['email'] }}">
                                                    @error('authors.' . $index . '.email')
                                                        <div class="invalid-feedback">
                                                            {{ $message }}
                                                        </div>
                                                    @enderror
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                                <div class="d-flex justify-content-end">
                                    <div class="btn-group pt-3 pb-3" role="group">
                                        <button type="button" id="addAuthor" class="btn btn-success"><i
                                                class="fa-solid fa-plus"></i></button>
                                        <button type="button" id="removeAuthor" class="btn btn-danger"><i
                                                class="fa-solid fa-minus"></i></button>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="mb-3">
                            <label for="coAuthors" class="form-label">Co-Authors</label>
                            <div class="table-responsive" id="coAuthors">
                                <table class="table table-bordered" id="tableCoAuthors">
                                    <thead class="table-primary">
                                        <tr>
                                            <th>Co-Author Name</th>
                                            <th>Co-Author Email</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach (old('coAuthors', $submission->coAuthors ?? []) as $index => $coAuthor)
                                            <tr>
                                                <td>
                                                    <input type="text" name="coAuthors[{{ $index }}][name]"
                                                        id="coAuthors.{{ $index }}.name"
                                                        class="form-control {{ $errors->has('coAuthors.' . $index . '.name') ? 'is-invalid' : '' }}"
                                                        placeholder="Enter Co-Author {{ $index + 1 }} Name"
                                                        value="{{ $coAuthor['name'] }}">
                                                    @error('coAuthors.' . $index . '.name')
                                                        <div class="invalid-feedback">
                                                            {{ $message }}
                                                        </div>
                                                    @enderror
                                                </td>
                                                <td>
                                                    <input type="email" name="coAuthors[{{ $index }}][email]"
                                                        id="coAuthors.{{ $index }}.email"
                                                        class="form-control {{ $errors->has('coAuthors.' . $index . '.email') ? 'is-invalid' : '' }}"
                                                        placeholder="Enter Co-Author {{ $index + 1 }} Email"
                                                        value="{{ $coAuthor['email'] }}">
                                                    @error('coAuthors.' . $index . '.email')
                                                        <div class="invalid-feedback">
                                                            {{ $message }}
                                                        </div>
                                                    @enderror
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                                <div class="d-flex justify-content-end">
                                    <div class="btn-group pt-3 pb-3" role="group">
                                        <button type="button" id="addCoAuthor" class="btn btn-success"><i
                                                class="fa-solid fa-plus"></i></button>
                                        <button type="button" id="removeCoAuthor" class="btn btn-danger"><i
                                                class="fa-solid fa-minus"></i></button>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="mb-3">
                            <label for="presenter" class="form-label">Presenter</label>
                            <input type="text"
                                class="form-control {{ $errors->has('presenter') ? 'is-invalid' : '' }}" name="presenter"
                                id="presenter" placeholder="Enter Presenter"
                                value="{{ old('presenter', $submission->presenter) }}">
                            @error('presenter')
                                <div class="invalid-feedback">
                                    {{ $message }}
                                </div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label for="abstract" class="form-label">Abstract</label>
                            <textarea class="form-control {{ $errors->has('abstract') ? 'is-invalid' : '' }}" rows="5" name="abstract"
                                id="abstract" placeholder="Enter Abstract" required>{{ old('abstract', $submission->abstract) }}</textarea>
                            @error('abstract')
                                <div class="invalid-feedback">
                                    {{ $message }}
                                </div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label for="abstractFile" class="form-label">Abstract File <small class="text-muted">(PDF or
                                    DOCX only, Max:
                                    4MB)</small></label>
                            <input type="file"
                                class="form-control {{ $errors->has('abstractFile') ? 'is-invalid' : '' }}"
                                name="abstractFile" id="abstractFile">
                            @error('abstractFile')
                                <div class="invalid-feedback">
                                    {{ $message }}
                                </div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label for="keywords" class="form-label">Keywords</label>
                            <input type="text"
                                class="form-control {{ $errors->has('keywords') ? 'is-invalid' : '' }}" name="keywords"
                                id="keywords" placeholder="Enter Keywords (Ex: Keyword 1, Keyword 2, Keyword 3, ...)"
                                value="{{ old('keywords', $submission->keywords) }}">
                            @error('keywords')
                                <div class="invalid-feedback">
                                    {{ $message }}
                                </div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label for="paperFile" class="form-label">Paper File <small class="text-muted">(PDF or DOCX
                                    only, Max:
                                    4MB)</small></label>
                            <input type="file"
                                class="form-control {{ $errors->has('paperFile') ? 'is-invalid' : '' }}" name="paperFile"
                                id="paperFile">
                            @error('paperFile')
                                <div class="invalid-feedback">
                                    {{ $message }}
                                </div>
                            @enderror
                        </div>

                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                    <button type="submit" class="btn btn-primary" form="createSubmission">Save</button>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('scripts')
    <script>
        //Authors
        const addAuthorButton = document.getElementById('addAuthor');
        const currentIndexAuthor =
            {{ count(old('authors', $submission->authors ?? [])) }};

        let iA = currentIndexAuthor;
        addAuthorButton.addEventListener('click', function() {

            var stringHtmlScaleElements = `<tr>
                                                <td>
                                                    <input type="text" name="authors[` + iA + `][name]"
                                                        id="authors.` + iA + `.name"
                                                        class="form-control"
                                                        placeholder="Enter Author ` + (iA + 1) + ` Name">
                                                </td>
                                                <td>
                                                    <input type="email" name="authors[` + iA + `][email]"
                                                        id="authors.` + iA + `.email"
                                                        class="form-control"
                                                        placeholder="Enter Author ` + (iA + 1) + ` Email">
                                                </td>
                                            </tr>`;

            $("#tableAuthors tbody").append(stringHtmlScaleElements);

            iA++;
        });

        $("#removeAuthor").on("click", function() {
            if (iA != 0) {
                iA--
                $('#tableAuthors tr:last').remove();
            }
        });


        //Co-Authors
        const addCoAuthorButton = document.getElementById('addCoAuthor');
        const currentIndexCoAuthor =
            {{ count(old('coAuthors', $submission->coAuthors ?? [])) }};

        let iCA = currentIndexCoAuthor;
        addCoAuthorButton.addEventListener('click', function() {

            var stringHtmlScaleElements = `<tr>
                                                <td>
                                                    <input type="text" name="coAuthors[` + iCA + `][name]"
                                                        id="coAuthors.` + iCA + `.name"
                                                        class="form-control"
                                                        placeholder="Enter Co-Author ` + (iCA + 1) + ` Name">
                                                </td>
                                                <td>
                                                    <input type="email" name="coAuthors[` + iCA + `][email]"
                                                        id="coAuthors.` + iCA + `.email"
                                                        class="form-control"
                                                        placeholder="Enter Co-Author ` + (iCA + 1) + ` Email">
                                                </td>
                                            </tr>`;

            iCA++;

            $("#tableCoAuthors tbody").append(stringHtmlScaleElements);
        });

        $("#removeCoAuthor").on("click", function() {
            if (iCA != 0) {
                iCA--
                $('#tableCoAuthors tr:last').remove();
            }
        });

        @if ($errors->has('registration_id') || $errors->has('title') || $errors->has('authors.*') || $errors->has('coAuthors.*') || $errors->has('presenter') || $errors->has('abstract') || $errors->has('abstractFile') || $errors->has('paperFile') || $errors->has('keywords'))
            const createSubmissionModal = new bootstrap.Modal('#createSubmissionModal');
            createSubmissionModal.show();
        @endif
    </script>
@endsection
