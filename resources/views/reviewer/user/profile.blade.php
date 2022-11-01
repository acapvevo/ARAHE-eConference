@extends('reviewer.layouts.app')

@section('content')
    <h3 class="text-dark mb-1">Profile</h3>

    <div class="card">
        <div class="card-body">
            <div class="row pt-3 pb-3">
                <div class="col">
                    <button class="btn btn-primary float-end" type="button" data-bs-toggle="modal"
                        data-bs-target="#UpdateProfileModal">Update</button>
                </div>
            </div>
            <div class="table-responsive">
                <table class="table table-bordered">
                    <tr>
                        <th class="w-25">Nama: </th>
                        <td>{{ $user->participant->name }}</td>
                    </tr>
                    <tr>
                        <th class="w-25">Emel: </th>
                        <td>{{ $user->email }}</td>
                    </tr>
                </table>
            </div>
        </div>
    </div>

    <div class="modal fade" id="UpdateProfileModal" tabindex="-1" aria-labelledby="UpdateProfileModalLabel"
        aria-hidden="true">
        <div class="modal-dialog modal-xl">
            <form action="{{ route('reviewer.user.profile.update') }}" method="post">
                @csrf
                @method('PATCH')
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="UpdateProfileModalLabel">Update Profile</h5>
                        <button class="btn-close" type="button" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <div class="form-floating mb-3">
                            <input class="form-control {{ $errors->has('name') ? 'is-invalid' : '' }}" id="name"
                                type="text" placeholder="Enter Name" name="name"
                                value="{{ old('name', $user->participant->name) }}">
                            <label class="form-label" for="name">Name</label>
                            @error('name')
                                <div class="invalid-feedback">
                                    {{ $message }}
                                </div>
                            @enderror
                        </div>
                        <div class="form-floating mb-3">
                            <input class="form-control {{ $errors->has('email') ? 'is-invalid' : '' }}" id="email"
                                type="email" placeholder="Enter Email Address" name="email"
                                value="{{ old('email', $user->email) }}">
                            <label class="form-label" for="email">Email Address</label>
                            @error('email')
                                <div class="invalid-feedback">
                                    {{ $message }}
                                </div>
                            @enderror
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button class="btn btn-secondary" type="button" data-bs-dismiss="modal">Close</button>
                        <button class="btn btn-primary" type="submit">Save</button>
                    </div>
                </div>
            </form>
        </div>
    </div>
@endsection
