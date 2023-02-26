@extends('admin.layouts.app')

@section('styles')
@endsection

@section('content')
    <h3 class="text-dark mb-1">Form Management - Extra Detail</h3>

    <div class="card">
        <h4 class="card-header text-center">Extra Detail</h4>
        <div class="card-body">
            <div class="row pt-3 pb-3">
                <div class="col">
                    <button type="button" class="btn btn-primary float-end" data-bs-toggle="modal" data-bs-target="#updateExtraModal">
                        Update Extra
                    </button>
                </div>
            </div>
            <div class="table-responsive">
                <table class="table table-bordered">
                    <tbody>
                        <tr>
                            <th class='w-25'>Description</th>
                            <td>{{ $extra->description }}</td>
                        </tr>
                        <tr>
                            <th class='w-25'>Code</th>
                            <td>{{ $extra->code }}</td>
                        </tr>
                        <tr>
                            <th class='w-25'>Date</th>
                            <td>{{ $extra->date->format('j F Y') }}</td>
                        </tr>
                        <tr>
                            <th class='w-25'>Options</th>
                            <td>
                                @if ($extra->options->count())
                                    <ul class="list-group">
                                        @foreach ($extra->options as $option)
                                            <li class="list-group-item">{{ $option }}</li>
                                        @endforeach
                                    </ul>
                                @else
                                    No Option Added
                                @endif
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <div class="modal fade" id="updateExtraModal" tabindex="-1" aria-labelledby="updateExtraModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-xl">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title fs-5" id="updateExtraModalLabel">Update Extra</h4>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form action="{{ route('admin.competition.extra.update', ['id' => $extra->id]) }}" method="post" id="updateExtra">
                        @csrf
                        @method('PATCH')

                        <div class="mb-3">
                            <label for="description" class="form-label">Description</label>
                            <input type="text" class="form-control {{ $errors->has('description') }}"
                                id="description" name="description" placeholder="Enter Extra Description"
                                value="{{ old('description', $extra->description) }}">
                            @error('description')
                                <div class="invalid-feedback">
                                    {{ $message }}
                                </div>
                            @enderror
                        </div>

                        <div class="mb-3 row">
                            <div class="col-md-6">
                                <label for="code" class="form-label">Code</label>
                                <input type="text" class="form-control {{ $errors->has('code') }}"
                                    id="code" name="code" placeholder="Enter Extra Code"
                                    value="{{ old('code', $extra->code) }}">
                                @error('code')
                                    <div class="invalid-feedback">
                                        {{ $message }}
                                    </div>
                                @enderror
                            </div>
                            <div class="col-md-6">
                                <label for="date" class="form-label">Date</label>
                                <input type="date" class="form-control {{ $errors->has('date') }}"
                                    id="date" name="date" placeholder="Enter Extra date"
                                    value="{{ old('date', $extra->date->format('Y-m-d')) }}">
                                @error('date')
                                    <div class="invalid-feedback">
                                        {{ $message }}
                                    </div>
                                @enderror
                            </div>
                        </div>

                        <div class="mb-3">
                            <label for="extra" class="form-label">Options</label>
                            <div class="table-responsive" id="options">
                                <table class="table table-bordered" id="tableOptions">
                                    <thead class="table-primary">
                                        <tr>
                                            <th>Option</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach (old('options', $extra->options) as $index => $option)
                                            <tr>
                                                <td>
                                                    <input type="text"
                                                        class="form-control {{ $errors->has('option.' . $index) }}"
                                                        name="option[{{ $index }}]"
                                                        id="option.{{ $index }}"
                                                        placeholder="Extra Option {{ $index + 1 }}"
                                                        value="{{ $option }}">
                                                    @error('option.' . $index)
                                                        <div class="invalid-feeback">
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
                                        <button type="button" id="addOption" class="btn btn-success"><i
                                                class="fa-solid fa-plus"></i></button>
                                        <button type="button" id="removeOption" class="btn btn-danger"><i
                                                class="fa-solid fa-minus"></i></button>
                                    </div>
                                </div>
                            </div>
                        </div>

                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                    <button type="submit" class="btn btn-primary" form="updateExtra">Save</button>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('scripts')
<script>
        //Extra Option
        const addOptionButton = document.getElementById('addOption');
        const currentIndexOption =
            {{ count(old('options', $extra->options)) }};

        let iO = currentIndexOption;
        addOptionButton.addEventListener('click', function() {

            var stringHtmlScaleElements = `<tr>
                                            <td>
                                                <input type="text"
                                                    class="form-control"
                                                    name="option[` + iO + `]"
                                                    id="option.` + iO + `"
                                                    placeholder="Extra Option ` + (iO + 1) + `">
                                            </td>
                                        </tr>`;

            $("#tableOptions").append(stringHtmlScaleElements);

            iO++;
        });

        $("#removeOption").on("click", function() {
            if (iO != 0) {
                iO--
                $('#tableOptions tr:last').remove();
            }
        });

        @if ($errors->any())
            const updateFormModal = new bootstrap.Modal('#updateExtraModal');
            updateFormModal.show();
        @endif
</script>
@endsection
