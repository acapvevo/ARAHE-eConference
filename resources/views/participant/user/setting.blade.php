@extends('participant.layouts.app')

@section('content')
    <h3 class="text-dark mb-1">Setting</h3>

    <div class="card">
        <h4 class="card-header text-center">Update Profile Picture</h4>
        <div class="card-body">
            <form action="{{ route('participant.user.picture.update') }}" method="post" enctype="multipart/form-data">
                @csrf
                @method('PATCH')

                <div class="mb-3">
                    <img width="200" height="200" class="img-fluid rounded-circle mx-auto d-block"
                        src="{{ Auth::user()->getImageURL() }}" />
                </div>

                <div class="mb-3">
                    <label for="ProfilePicture" class="form-label">Profile Picture</label>
                    <input class="form-control {{ $errors->has('ProfilePicture') ? 'is-invalid' : '' }}" type="file"
                        id="ProfilePicture" name="ProfilePicture">
                    @error('ProfilePicture')
                        <div class="invalid-feedback">
                            {{ $message }}
                        </div>
                    @enderror
                </div>

                <div class="mb-3">
                    <button class="btn btn-primary float-end" type="submit">Save</button>
                </div>
            </form>
        </div>
    </div>

    <div class="pt-3 pb-3"></div>

    <div class="card">
        <h4 class="card-header text-center">Update Password</h4>
        <div class="card-body">
            <form action="{{ route('participant.user.password.update') }}" method="post">
                @csrf
                @method('PATCH')
                <div class="form-floating mb-3">
                    <input class="form-control {{ $errors->has('password') ? 'is-invalid' : '' }}" id="password"
                        type="password" placeholder="Enter New Password" name="password">
                    <label class="form-label" for="password">New Password </label>
                    @error('password')
                        <div class="invalid-feedback">
                            {{ $message }}
                        </div>
                    @enderror
                </div>
                <div class="form-floating mb-3">
                    <input class="form-control {{ $errors->has('password_confirmation') ? 'is-invalid' : '' }}"
                        id="password_confirmation" type="password" placeholder="Enter New Password For Confirmation"
                        name="password_confirmation">
                    <label class="form-label" for="password_confirmation">New Password Confirmation</label>
                    @error('password_confirmation')
                        <div class="invalid-feedback">
                            {{ $message }}
                        </div>
                    @enderror
                </div>
                <div class="mb-3">
                    <button class="btn btn-primary float-end" type="submit">Save</button>
                </div>
            </form>
        </div>
    </div>

    <div class="pt-3 pb-3"></div>
@endsection
