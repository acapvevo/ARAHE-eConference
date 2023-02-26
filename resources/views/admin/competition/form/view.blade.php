@extends('admin.layouts.app')

@section('styles')
    <link rel="stylesheet" href="https://cdn.datatables.net/1.12.1/css/dataTables.bootstrap5.min.css">
@endsection

@php
    $durationsLocal = $form->durations->where('locality', 'L');
    $durationsInternational = $form->durations->where('locality', 'I');

    $durationsInternationalWihtoutOnsite = $durationsInternational->reject(function ($duration) {
        return $duration->name === 'On-site';
    });
    $durationsLocalWihtoutOnsite = $durationsLocal->reject(function ($duration) {
        return $duration->name === 'On-site';
    });

    $OnSiteDurationInternational = $durationsInternational->first(function ($duration) {
        return $duration->name === 'On-site';
    });
    $OnSiteDurationLocal = $durationsLocal->first(function ($duration) {
        return $duration->name === 'On-site';
    });

    $categoriesLocal = $form->categories->where('locality', 'L');
    $categoriesInternational = $form->categories->where('locality', 'I');
@endphp

@section('content')
    <h3 class="text-dark mb-1">Form Management - Form Detail and Rubrics</h3>

    <div class="card">
        <h4 class="card-header text-center">Form Detail</h4>
        <div class="card-body">
            <div class="row pt-3 pb-3">
                <div class="col-12">
                    <button type="button" class="btn btn-primary float-end" data-bs-toggle="modal"
                        data-bs-target="#updateFormModal">
                        Update Form Details
                    </button>
                </div>
            </div>
            <div class="table-responsive">
                <table class="table table-bordered">
                    <tbody>
                        <tr>
                            <th colspan='5' class="text-center">Session</th>
                        </tr>
                        <tr>
                            <th class='w-25'>Year</th>
                            <td class="text-center" colspan="4">{{ $form->session->year }}</td>
                        </tr>
                        <tr>
                            <th class='w-25'>Submission Period</th>
                            <th>Start</th>
                            <td class="text-center">{{ $form->session->returnDateString('submission', 'start') }}</td>
                            <th>End</th>
                            <td class="text-center">{{ $form->session->returnDateString('submission', 'end') }}</td>
                        </tr>
                        <tr>
                            <th class='w-25'>Registration Period</th>
                            <th>Start</th>
                            <td class="text-center">{{ $form->session->returnDateString('registration', 'start') }}</td>
                            <th>End</th>
                            <td class="text-center">{{ $form->session->returnDateString('registration', 'end') }}</td>
                        </tr>
                        <tr>
                            <th class='w-25'>Congress</th>
                            <th>Start</th>
                            <td class="text-center">{{ $form->session->returnDateString('congress', 'start') }}</td>
                            <th>End</th>
                            <td class="text-center">{{ $form->session->returnDateString('congress', 'end') }}</td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <div class="pt-3 pb-3"></div>

    <div class="card">
        <h4 class="card-header text-center">Form Rubrics</h4>
        <div class="card-body">
            <div class="row pt-3 pb-3">
                <div class="col-6">
                    <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#addRubricModal">
                        Add Rubric
                    </button>
                </div>
                <div class="col-6">
                    <button type="submit" class="btn btn-danger float-end" form="deleteRubric">
                        Delete Rubric(s)
                    </button>
                </div>
            </div>

            <form action="{{ route('admin.competition.rubric.delete') }}" method="post" id="deleteRubric">
                @csrf
                @method('DELETE')

                <input type="hidden" name="form_id" value="{{ $form->id }}">

                <div class="table-responsive">
                    <table class="table table-bordered" id="table_id">
                        <thead class="table-primary">
                            <tr>
                                <th style="width: 5%">
                                    <div class="checkbox"><input type="checkbox" id="checkAll"></div>
                                </th>
                                <th>Rubric</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($form->rubrics as $rubric)
                                <tr>
                                    <td>
                                        <div class="checkbox"><input class="checkboxTick" type="checkbox"
                                                name="rubric_id[]" value="{{ $rubric->id }}"></div>
                                    </td>
                                    <td><a
                                            href="{{ route('admin.competition.rubric.view', ['id' => $rubric->id]) }}">{{ $rubric->description }}</a>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </form>
        </div>
    </div>

    <div class="pt-3 pb-3"></div>

    <div class="modal fade" id="updateFormModal" tabindex="-1" aria-labelledby="updateFormModalLabel"
        aria-hidden="true">
        <div class="modal-dialog modal-xl">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title fs-5" id="updateFormModalLabel">Update Form</h4>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form action="{{ route('admin.competition.form.update', ['id' => $form->id]) }}" method="post"
                        id="updateForm">
                        @csrf
                        @method('PATCH')

                        <hr>
                        <h5 class="text-center">Session</h5>
                        <hr>

                        <label for="sessionRegistration" class="form-label"><strong>Registration</strong></label>
                        <div class="row mb-3" id="sessionRegistration">
                            <div class="col">
                                <label for="session.registration.start" class="form-label">Start</label>
                                <input type="date"
                                    class="form-control {{ $errors->has('session.registration.start') ? 'is-invalid' : '' }}"
                                    id="session.registration.start" name="session[registration][start]"
                                    value="{{ old('session.registration.start', $form->session->returnDateInputValue('registration', 'start')) }}">
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
                                    value="{{ old('session.registration.end', $form->session->returnDateInputValue('registration', 'end')) }}">
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
                                    value="{{ old('session.submission.start', $form->session->returnDateInputValue('submission', 'start')) }}">
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
                                    value="{{ old('session.submission.end', $form->session->returnDateInputValue('submission', 'end')) }}">
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
                                    value="{{ old('session.congress.start', $form->session->returnDateInputValue('congress', 'start')) }}">
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
                                    value="{{ old('session.congress.end', $form->session->returnDateInputValue('congress', 'end')) }}">
                                @error('session.congress.end')
                                    <div class="invalid-feedback">
                                        {{ $message }}
                                    </div>
                                @enderror
                            </div>
                        </div>

                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                    <button type="submit" class="btn btn-primary" form="updateForm">Save</button>
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade" id="addRubricModal" tabindex="-1" aria-labelledby="addRubricModalLabel"
        aria-hidden="true">
        <div class="modal-dialog modal-xl">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title fs-5" id="addRubricModalLabel">Add Rubric</h4>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form action="{{ route('admin.competition.rubric.create') }}" method="post" id="addRubric">
                        @csrf

                        <input type="hidden" name="form_id" value="{{ $form->id }}">

                        <div class="row">
                            <div class="col-12">
                                <div class="mb-3">
                                    <label for="description" class="form-label">Description</label>
                                    <textarea class="form-control {{ $errors->has('description') ? 'is-invalid' : '' }}" rows="3"
                                        name="description" id="description" placeholder="Enter Rubric Description" required>{{ old('description') }}</textarea>
                                    @error('description')
                                        <div class="invalid-feedback">
                                            {{ $message }}
                                        </div>
                                    @enderror
                                </div>
                            </div>
                        </div>

                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                    <button type="submit" class="btn btn-primary" form="addRubric">Save</button>
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

            $('#checkAll').click(function() {
                var checked = this.checked;
                $('input[class="checkboxTick"]').each(function() {
                    this.checked = checked;
                });
            });
        });

        @if ($errors->has('description'))
            const updateFormModal = new bootstrap.Modal('#addRubricModal');
            updateFormModal.show();
        @endif
    </script>
@endsection
