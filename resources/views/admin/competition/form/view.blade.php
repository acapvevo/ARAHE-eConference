@extends('admin.layouts.app')

@section('styles')
    <link rel="stylesheet" href="https://cdn.datatables.net/1.12.1/css/dataTables.bootstrap5.min.css">
@endsection

@section('content')
    <h3 class="text-dark mb-1">Form Detail and Rubrics</h3>

    <div class="card">
        <h4 class="card-header text-center">Form Detail</h4>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-bordered">
                    <tbody>
                        <tr>
                            <th colspan='2' class="text-center">Session</th>
                        </tr>
                        <tr>
                            <th class='w-25'>Year</th>
                            <td>{{ $form->session->year }}</td>
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
                                <th style="width:10%">Mark</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($form->rubrics as $rubric)
                                <tr>
                                    <td>
                                        <div class="checkbox"><input class="checkboxTick" type="checkbox" name="rubric_id[]"
                                                value="{{ $rubric->id }}"></div>
                                    </td>
                                    <td><a href="{{ route('admin.competition.rubric.view', ['id' => $rubric->id]) }}">{{ $rubric->description }}</a></td>
                                    <td class="text-center">{{ $rubric->mark }}</td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </form>
        </div>
    </div>

    <div class="modal fade" id="addRubricModal" tabindex="-1" aria-labelledby="addRubricModalLabel" aria-hidden="true">
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
                            <div class="col-md-8">
                                <div class="mb-3">
                                    <label for="description" class="form-label">Description</label>
                                    <textarea class="form-control {{ $errors->has('description') ? 'is-invalid' : '' }}" rows="1" name="description"
                                        id="description" placeholder="Enter Rubric Description" required>{{ old('description') }}</textarea>
                                    @error('description')
                                        <div class="invalid-feedback">
                                            {{ $message }}
                                        </div>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="mb-3">
                                    <label for="mark" class="form-label">Mark</label>
                                    <input type="number"
                                        class="form-control {{ $errors->has('mark') ? 'is-invalid' : '' }}" name="mark"
                                        id="mark" placeholder="Enter Rubric Mark" value="{{ old('mark') }}">
                                    @error('mark')
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
    </script>
@endsection
