@extends('admin.layouts.app')

@section('styles')
    <link rel="stylesheet" href="https://cdn.datatables.net/1.12.1/css/dataTables.bootstrap5.min.css">
    <style>
        .form-switch.form-switch-lg .form-check-input {
            height: 2rem;
            width: calc(3rem + 0.75rem);
            border-radius: 4rem;
        }
    </style>
@endsection

@section('content')
    <h3 class="text-dark mb-1">Form Management - Form Detail and Rubrics</h3>

    <div class="card">
        <h4 class="card-header text-center">Form Detail</h4>
        <div class="card-body">
            <div class="row pt-3 pb-3">
                <div class="col-12">
                    <button type="button" class="btn btn-primary float-end" data-bs-toggle="modal"
                        data-bs-target="#updateFormModal">
                        Update Form
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
                        @forelse ($form->categories as $index => $category)
                            <tr>
                                @if ($index == 0)
                                    <th rowspan="{{ $form->categories->count() }}">Categories</th>
                                @endif
                                <td class="text-center" colspan="2">{{ $category->name }}</td>
                                <td class="text-center" colspan="2">
                                    {{ $category->needProof ? 'Need Proof' : 'No Need Proof' }}</td>
                            </tr>
                        @empty
                            <tr>
                                <th>Categories</th>
                                <td colspan="4">No Category Added Yet</td>
                            </tr>
                        @endforelse
                        @forelse ($form->durations as $index => $duration)
                            <tr>
                                @if ($index == 0)
                                    <th rowspan="{{ $form->durations->count() }}">Durations</th>
                                @endif
                                <td class="text-center" colspan="1">{{ $duration->name }}</td>
                                <td class="text-center" colspan="3">{{ $duration->start->translatedFormat('j F Y') }} -
                                    {{ $duration->end->translatedFormat('j F Y') }}</td>
                            </tr>
                        @empty
                            <tr>
                                <th>Duration</th>
                                <td colspan="4">No Duration Added Yet</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <div class="pt-3 pb-3"></div>

    <div class="card">
        <h4 class="card-header text-center">Fee Structure</h4>
        <div class="card-body">
            <div class="row pt-3 pb-3">
                <div class="col-12">
                    <button type="button" class="btn btn-primary float-end" data-bs-toggle="modal"
                        data-bs-target="#modifyFeeModal">
                        Modify Fee Stucture
                    </button>
                </div>
            </div>

            <div class="table-responsive">
                <table class="table table-bordered text-center">
                    <thead>
                        <tr>
                            <th></th>
                            @foreach ($form->durations as $duration)
                                <th>
                                    {{ $duration->name }}
                                    <br>
                                    {{ $duration->start->translatedFormat('j/m/Y') }} -
                                    {{ $duration->end->translatedFormat('j/m/Y') }}
                                </th>
                            @endforeach
                        </tr>
                    </thead>
                    <tbody>
                        @php
                            $feeIndex = 0;
                        @endphp
                        @foreach ($form->categories as $category)
                            <tr>
                                <td>{{ $category->name }}</td>
                                @foreach ($form->durations as $duration)
                                    <td>
                                        {{ DB::table('fees')->where('category_id', $category->id)->where('duration_id', $duration->id)->first()->amount ?? 0 }}
                                        USD
                                    </td>
                                    @php
                                        $feeIndex++;
                                    @endphp
                                @endforeach
                            </tr>
                        @endforeach
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
                                        <div class="checkbox"><input class="checkboxTick" type="checkbox" name="rubric_id[]"
                                                value="{{ $rubric->id }}"></div>
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

    <div class="modal fade" id="updateFormModal" tabindex="-1" aria-labelledby="updateFormModalLabel" aria-hidden="true">
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

                        <label for="categories" class="form-label"><strong>Categories</strong></label>
                        <div class="table-responsive">
                            <table class="table table-bordered align-middle" id="tableCategory">
                                <thead class="table-primary">
                                    <tr>
                                        <th class="w-75">Category Name</th>
                                        <th>Need Proof?</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach (old('categories', $form->categories) as $index => $category)
                                        <tr>
                                            <td>
                                                <input type="text"
                                                    class="form-control {{ $errors->has('categories.' . $index . '.name') ? 'is-invalid' : '' }}"
                                                    name="categories[{{ $index }}][name]"
                                                    id="categories.{{ $index }}.name"
                                                    placeholder="Enter Category {{ $index + 1 }} Name"
                                                    value="{{ $category['name'] }}">
                                                @error('categories.' . $index . '.name')
                                                    <div class="invalid-feedback">
                                                        {{ $message }}
                                                    </div>
                                                @enderror
                                            </td>
                                            <td>
                                                <div
                                                    class="form-check form-switch form-switch-lg d-flex justify-content-center">
                                                    <input class="form-check-input" type="checkbox" role="switch"
                                                        name="categories[{{ $index }}][needProof]"
                                                        id="categories.{{ $index }}.needProof" value=1
                                                        @checked($category['needProof'] ?? false)>
                                                </div>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                            <div class="d-flex justify-content-end">
                                <div class="btn-group pt-3 pb-3" role="group" aria-label="Basic example">
                                    <button type="button" id="addCategory" class="btn btn-success"><i
                                            class="fa-solid fa-plus"></i></button>
                                    <button type="button" id="removeCategory" class="btn btn-danger"><i
                                            class="fa-solid fa-minus"></i></button>
                                </div>
                            </div>
                        </div>

                        <div class="p-3"></div>

                        <label for="durations" class="form-label"><strong>Registration Durations</strong></label>
                        <div class="table-responsive">
                            <table class="table table-bordered align-middle" id="tableDuration">
                                <thead class="table-primary">
                                    <tr>
                                        <th class="w-50">Duration Name</th>
                                        <th>Start</th>
                                        <th>End</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach (old('durations', $form->durations) as $index => $duration)
                                        <tr>
                                            <td>
                                                <input type="text"
                                                    class="form-control {{ $errors->has('durations.' . $index . '.name') ? 'is-invalid' : '' }}"
                                                    name="durations[{{ $index }}][name]"
                                                    id="durations.{{ $index }}.name"
                                                    placeholder="Enter Duration {{ $index + 1 }} Name"
                                                    value="{{ $duration['name'] }}">
                                                @error('durations.' . $index . '.name')
                                                    <div class="invalid-feedback">
                                                        {{ $message }}
                                                    </div>
                                                @enderror
                                            </td>
                                            <td>
                                                <input type="date"
                                                    class="form-control {{ $errors->has('durations.' . $index . '.start') ? 'is-invalid' : '' }}"
                                                    name="durations[{{ $index }}][start]"
                                                    id="durations.{{ $index }}.start"
                                                    value="{{ $duration['start'] }}">
                                                @error('durations.' . $index . '.start')
                                                    <div class="invalid-feedback">
                                                        {{ $message }}
                                                    </div>
                                                @enderror
                                            </td>
                                            <td>
                                                <input type="date"
                                                    class="form-control {{ $errors->has('durations.' . $index . '.end') ? 'is-invalid' : '' }}"
                                                    name="durations[{{ $index }}][end]"
                                                    id="durations.{{ $index }}.end"
                                                    value="{{ $duration['end'] }}">
                                                @error('durations.' . $index . '.end')
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
                                <div class="btn-group pt-3 pb-3" role="group" aria-label="Basic example">
                                    <button type="button" id="addDuration" class="btn btn-success"><i
                                            class="fa-solid fa-plus"></i></button>
                                    <button type="button" id="removeDuration" class="btn btn-danger"><i
                                            class="fa-solid fa-minus"></i></button>
                                </div>
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

    <div class="modal fade" id="modifyFeeModal" tabindex="-1" aria-labelledby="modifyFeeModalLabel" aria-hidden=true>
        <div class="modal-dialog modal-xl">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title fs-5" id="modifyFeeModalLabel">Modify Fee Structure</h4>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form action="{{ route('admin.competition.form.modify', ['id' => $form->id]) }}" method="post"
                        id="modifyFee">
                        @csrf
                        @method('PATCH')

                        <div class="table-responsive">
                            <table class="table table-bordered text-center">
                                <thead>
                                    <tr>
                                        <th></th>
                                        @foreach ($form->durations as $duration)
                                            <th>
                                                {{ $duration->name }}
                                                <br>
                                                {{ $duration->start->translatedFormat('j/m/Y') }} -
                                                {{ $duration->end->translatedFormat('j/m/Y') }}
                                            </th>
                                        @endforeach
                                    </tr>
                                </thead>
                                <tbody>
                                    @php
                                        $feeIndex = 0;
                                    @endphp
                                    @foreach ($form->categories as $category)
                                        <tr>
                                            <td>{{ $category->name }}</td>
                                            @foreach ($form->durations as $duration)
                                                <td>
                                                    <input type="hidden" name="fee[{{ $feeIndex }}][duration_id]"
                                                        value="{{ $duration->id }}">
                                                    <input type="hidden" name="fee[{{ $feeIndex }}][category_id]"
                                                        value="{{ $category->id }}">
                                                    <div class="input-group mb-3">
                                                        <span class="input-group-text">$</span>
                                                        <input type="number"
                                                            class="form-control {{ $errors->has('fee.' . $feeIndex . '.amount') }}"
                                                            name="fee[{{ $feeIndex }}][amount]"
                                                            value="{{ old('fee.' . $feeIndex . '.amount',DB::table('fees')->where('category_id', $category->id)->where('duration_id', $duration->id)->first()->amount ?? 0) }}">
                                                        <span class="input-group-text">USD</span>
                                                    </div>
                                                    @error('fee.' . $feeIndex . '.amount')
                                                        <div class="invalid-feedback">
                                                            {{ $message }}
                                                        </div>
                                                    @enderror
                                                </td>
                                                @php
                                                    $feeIndex++;
                                                @endphp
                                            @endforeach
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>

                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                    <button type="submit" class="btn btn-primary" form="modifyFee">Save</button>
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

        //Category
        const addCategoryButton = document.getElementById('addCategory');
        const currentIndexCategory =
            {{ count(old('categories', $form->categories)) }};

        let iC = currentIndexCategory;
        addCategoryButton.addEventListener('click', function() {

            var stringHtmlScaleElements = `<tr>
                                            <td>
                                                <input type="text"
                                                    class="form-control"
                                                    name="categories[` + iC + `][name]"
                                                    id="categories.` + iC + `.name"
                                                    placeholder="Enter Category ` + (iC + 1) + ` Name">
                                            </td>
                                            <td>
                                                <div class="form-check form-switch form-switch-lg d-flex justify-content-center">
                                                <input class="form-check-input" type="checkbox" role="switch"
                                                    name="categories[` + iC + `][needProof]"
                                                    id="categories.` + iC + `.needProof"
                                                    value=1>
                                                </div>
                                            </td>
                                        </tr>`;

            $("#tableCategory").append(stringHtmlScaleElements);

            iC++;
        });

        $("#removeCategory").on("click", function() {
            if (iC != 0) {
                iC--
                $('#tableCategory tr:last').remove();
            }
        });

        //Duration
        const addDurationButton = document.getElementById('addDuration');
        const currentIndexDuration =
            {{ count(old('durations', $form->durations)) }};

        let iD = currentIndexDuration;
        addDurationButton.addEventListener('click', function() {

            var stringHtmlScaleElements = `<tr>
                                            <td>
                                                <input type="text"
                                                    class="form-control"
                                                    name="durations[` + iD + `][name]"
                                                    id="durations.` + iD + `.name"
                                                    placeholder="Enter Duration ` + (iD + 1) + ` Name">
                                            </td>
                                            <td>
                                                <input type="date"
                                                    class="form-control"
                                                    name="durations[` + iD + `][start]"
                                                    id="durations.` + iD + `.start">
                                            </td>
                                            <td>
                                                <input type="date"
                                                    class="form-control"
                                                    name="durations[` + iD + `][end]"
                                                    id="durations.` + iD + `.end">
                                            </td>
                                        </tr>`;

            $("#tableDuration").append(stringHtmlScaleElements);

            iD++;
        });

        $("#removeDuration").on("click", function() {
            if (iD != 0) {
                iD--
                $('#tableDuration tr:last').remove();
            }
        });

        @if ($errors->has('categories.*') || $errors->has('durations.*'))
            const updateFormModal = new bootstrap.Modal('#updateFormModal');
            updateFormModal.show();
        @endif

        @if ($errors->has('fee.*'))
            const updateFormModal = new bootstrap.Modal('#modifyFeeModal');
            updateFormModal.show();
        @endif

        @if ($errors->has('description'))
            const updateFormModal = new bootstrap.Modal('#addRubricModal');
            updateFormModal.show();
        @endif
    </script>
@endsection
