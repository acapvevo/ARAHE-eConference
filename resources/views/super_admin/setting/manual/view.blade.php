@extends('super_admin.layouts.app')

@section('content')
    <h3 class="text-dark mb-1">{{ $manual->name }}</h3>

    <div class="card">
        <div class="card-body text-center">
            <div class="row">
                <div class="col">
                    <button type="button" class="btn btn-primary float-end" data-bs-toggle="modal"
                        data-bs-target="#updateUserManualModal">
                        Update
                    </button>
                </div>
            </div>
            @if (isset($manual->file))
                <iframe src="{{ route('super_admin.system.manual.stream', ['id' => $manual->id]) }}" class="w-100 pt-3 pb-3"
                    height="750">
                </iframe>
            @else
                <h3>No User Manual uploaded for {{ $manual->name }}</h3>
            @endif
        </div>
    </div>


    <div class="modal fade" id="updateUserManualModal" tabindex="-1" aria-labelledby="updateUserManualModalLabel"
        aria-hidden="true">
        <div class="modal-dialog modal-xl">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="updateUserManualModalLabel">Update {{ $manual->name }}</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form action="{{ route('super_admin.system.manual.update', ['id' => $manual->id]) }}" method="post"
                        accept-charset="utf-8" enctype="multipart/form-data" id="updateManual">
                        @csrf
                        @method('PATCH')

                        <div class="mb-3">
                            <label for="file" class="form-label">User Manual File</label>
                            <input class="form-control {{ $errors->has('file') ? 'is-invalid' : '' }}" type="file"
                                id="file" name="file">
                            @error('file')
                                <div class="invalid-feedback">
                                    {{ $message }}
                                </div>
                            @enderror
                        </div>

                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                    <button type="submit" class="btn btn-primary" form="updateManual">Save</button>
                </div>
            </div>
        </div>
    </div>
@endsection
