@extends('admin.layouts.app')

@section('styles')
    <link rel="stylesheet" href="https://cdn.datatables.net/1.12.1/css/dataTables.bootstrap5.min.css">
@endsection

@section('content')
    <h3 class="text-dark mb-1">Form Management - Form List</h3>

    <div class="card">
        <div class="card-body">
            <div class="row pt-3 pb-3">
                <div class="col">
                    <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#addFormModal">
                        Add Form
                    </button>
                </div>
            </div>
            <div class="table-responsive">
                <table class="table table-bordered" id="table_id">
                    <thead class="table-primary">
                        <tr>
                            <th style="width: 10%">Year</th>
                            <th>Submission</th>
                            <th>Registration</th>
                            <th>Congress</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($forms as $form)
                            <tr>
                                <td class="text-center">{{ $form->session->year }}</td>
                                <td>{{ $form->session->returnDateString('submission', 'start') }} -
                                    {{ $form->session->returnDateString('submission', 'end') }}</td>
                                <td>{{ $form->session->returnDateString('registration', 'start') }} -
                                    {{ $form->session->returnDateString('registration', 'end') }}</td>
                                <td>{{ $form->session->returnDateString('congress', 'start') }} -
                                    {{ $form->session->returnDateString('congress', 'end') }}</td>
                                <td class="text-center">
                                    <div class="btn-group-vertical" role="group" aria-label="Vertical button group">
                                        <a class="btn btn-primary" href="{{route('admin.competition.form.view', ['id' => $form->id])}}" role="button">Form Details</a>
                                        <a class="btn btn-primary" href="{{route('admin.competition.package.view', ['form_id' => $form->id])}}" role="button">Package and Hotel Details</a>
                                    </div>
                                </td>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <div class="modal fade" id="addFormModal" tabindex="-1" aria-labelledby="addFormModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-xl">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title fs-5" id="addFormModalLabel">Add Form</h4>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form action="{{ route('admin.competition.form.create') }}" method="post" id="addForm">
                        @csrf

                        <hr>
                        <h5 class="text-center">Session</h5>
                        <hr>

                        <div class="mb-3">
                            <label for="sessionYear" class="form-label">Year</label>
                            <select class="form-select {{ $errors->has('session.year') ? 'is-invalid' : '' }}"
                                id="sessionYear" name="session[year]">
                                <option selected disabled>Select Year</option>
                                @foreach ($yearsAvailable as $year)
                                    <option value={{ $year }} {{ old('session.year') == $year ? 'selected' : '' }}>
                                        {{ $year }}</option>
                                @endforeach
                            </select>
                            @error('session.year')
                                <div class="invalid-feedback">
                                    {{ $message }}
                                </div>
                            @enderror
                        </div>

                        <label for="sessionRegistration" class="form-label"><strong>Registration</strong></label>
                        <div class="row mb-3" id="sessionRegistration">
                            <div class="col">
                                <label for="session.registration.start" class="form-label">Start</label>
                                <input type="date"
                                    class="form-control {{ $errors->has('session.registration.start') ? 'is-invalid' : '' }}"
                                    id="session.registration.start" name="session[registration][start]"
                                    value="{{ old('session.registration.start') }}">
                                @error('session.registration.start')
                                    <div class="invalid-feedback">
                                        {{ $message }}
                                    </div>
                                @enderror
                            </div>
                            <div class="col">
                                <label for="session.registration.end" class="form-label">End</label>
                                <input type="date"
                                    class="form-control {{ $errors->has('session.registration.end') ? 'is-invalid' : '' }}"
                                    id="session.registration.end" name="session[registration][end]"
                                    value="{{ old('session.registration.end') }}">
                                @error('session.registration.end')
                                    <div class="invalid-feedback">
                                        {{ $message }}
                                    </div>
                                @enderror
                            </div>
                        </div>

                        <label for="sessionSubmission" class="form-label"><strong>Submission</strong></label>
                        <div class="row mb-3" id="sessionSubmission">
                            <div class="col">
                                <label for="session.submission.start" class="form-label">Start</label>
                                <input type="date"
                                    class="form-control {{ $errors->has('session.submission.start') ? 'is-invalid' : '' }}"
                                    id="session.submission.start" name="session[submission][start]"
                                    value="{{ old('session.submission.start') }}">
                                @error('session.submission.start')
                                    <div class="invalid-feedback">
                                        {{ $message }}
                                    </div>
                                @enderror
                            </div>
                            <div class="col">
                                <label for="session.submission.end" class="form-label">End</label>
                                <input type="date"
                                    class="form-control {{ $errors->has('session.submission.end') ? 'is-invalid' : '' }}"
                                    id="session.submission.end" name="session[submission][end]"
                                    value="{{ old('session.submission.end') }}">
                                @error('session.submission.end')
                                    <div class="invalid-feedback">
                                        {{ $message }}
                                    </div>
                                @enderror
                            </div>
                        </div>

                        <label for="sessionCongress" class="form-label"><strong>Congress</strong></label>
                        <div class="row mb-3" id="sessionCongress">
                            <div class="col">
                                <label for="session.congress.start" class="form-label">Start</label>
                                <input type="date"
                                    class="form-control {{ $errors->has('session.congress.start') ? 'is-invalid' : '' }}"
                                    id="session.congress.start" name="session[congress][start]"
                                    value="{{ old('session.congress.start') }}">
                                @error('session.congress.start')
                                    <div class="invalid-feedback">
                                        {{ $message }}
                                    </div>
                                @enderror
                            </div>
                            <div class="col">
                                <label for="session.congress.end" class="form-label">End</label>
                                <input type="date"
                                    class="form-control {{ $errors->has('session.congress.end') ? 'is-invalid' : '' }}"
                                    id="session.congress.end" name="session[congress][end]"
                                    value="{{ old('session.congress.end') }}">
                                @error('session.congress.end')
                                    <div class="invalid-feedback">
                                        {{ $message }}
                                    </div>
                                @enderror
                            </div>
                        </div>

                        <div class="mb-3">
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" value="1" id="copyOldForm"
                                    name="copyOldForm">
                                <label class="form-check-label" for="copyOldForm">
                                    Do you want to copy rubric from past year?
                                </label>
                            </div>
                        </div>

                        <div class="mb-3" id="selectOldForm">
                            <label for="oldForm" class="form-label">Copy from</label>
                            <select class="form-select {{ $errors->has('oldForm') ? 'is-invalid' : '' }}" id="oldForm"
                                name="oldForm">
                                <option selected disabled>Select Year</option>
                                @foreach ($forms as $form)
                                    <option value="{{ $form->id }}"
                                        {{ old('oldForm') == $form->id ? 'selected' : '' }}>{{ $form->session->year }}
                                    </option>
                                @endforeach
                            </select>
                            @error('oldForm')
                                <div class="invalid-feedback">
                                    {{ $message }}
                                </div>
                            @enderror
                        </div>

                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                    <button type="submit" class="btn btn-primary" form="addForm">Save</button>
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
            $('#table_id').DataTable();
            checkInputCopyOldForm()
        });

        const copyOldFormEl = document.getElementById('copyOldForm');
        const selectOldFormEl = document.getElementById('selectOldForm');

        copyOldFormEl.addEventListener('change', function() {
            checkInputCopyOldForm()
        })

        const checkInputCopyOldForm = () => {
            const valueCheckBox = copyOldFormEl.checked;

            if (valueCheckBox) {
                selectOldFormEl.style.display = "block";
            } else {
                selectOldFormEl.style.display = "none";
            }
        }

        const sessionRegistrationStart = document.getElementById('session.registration.start');
        const sessionSubmissionStart = document.getElementById('session.submission.start');

        sessionRegistrationStart.addEventListener('change', function() {
            sessionSubmissionStart.value = sessionRegistrationStart.value;
        });

        sessionSubmissionStart.addEventListener('change', function() {
            sessionRegistrationStart.value = sessionSubmissionStart.value;
        });
    </script>
@endsection
