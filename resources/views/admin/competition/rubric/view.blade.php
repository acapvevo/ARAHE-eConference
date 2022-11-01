@extends('admin.layouts.app')

@section('styles')
@endsection

@section('content')
    <h3 class="text-dark mb-1">Form Management - Rubric Detail</h3>

    <div class="card">
        <h4 class="card-header text-center">Rubric Detail</h4>
        <div class="card-body">
            <div class="row pt-3 pb-3">
                <div class="col">
                    <button type="button" class="btn btn-primary float-end" data-bs-toggle="modal" data-bs-target="#updateRubricModal">
                        Update Rubric
                    </button>
                </div>
            </div>
            <div class="table-responsive">
                <table class="table table-bordered">
                    <tbody>
                        <tr>
                            <th class='w-25'>Description</th>
                            <td>{{ $rubric->description }}</td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <div class="modal fade" id="updateRubricModal" tabindex="-1" aria-labelledby="updateRubricModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-xl">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title fs-5" id="updateRubricModalLabel">Update Rubric</h4>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form action="{{ route('admin.competition.rubric.update', ['id' => $rubric->id]) }}" method="post" id="updateRubric">
                        @csrf
                        @method('PATCH')

                        <div class="row">
                            <div class="col-12">
                                <div class="mb-3">
                                    <label for="description" class="form-label">Description</label>
                                    <textarea class="form-control {{ $errors->has('description') ? 'is-invalid' : '' }}" rows="3" name="description"
                                        id="description" placeholder="Enter Rubric Description" required>{{ old('description', $rubric->description) }}</textarea>
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
                    <button type="submit" class="btn btn-primary" form="updateRubric">Save</button>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('scripts')
@endsection
