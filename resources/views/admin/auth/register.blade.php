@extends('layouts.guest')

@section('content')
    <div class="row">
        <div class="col-lg-6 d-none d-lg-flex">
            <div class="flex-grow-1 bg-login-image" style="background-image: url(&quot;assets/img/dogs/image3.jpeg&quot;);">
            </div>
        </div>
        <div class="col-lg-6">
            <div class="p-5">
                <div class="text-center">
                    <h4 class="text-dark mb-4">Register</h4>
                </div>

                <form class="user" method="POST" action="{{ route('admin.register') }}">
                    @csrf

                    <div class="mb-3">
                        <input class="form-control form-control-user {{ $errors->has('name') ? 'is-invalid' : '' }}"
                            type="text" placeholder="Enter Name" name="name" id="name"
                            value="{{ old('name') }}" required autofocus>
                        @error('name')
                            <div class="invalid-feedback">
                                {{ $message }}
                            </div>
                        @enderror
                    </div>

                    <div class="mb-3">
                        <input class="form-control form-control-user {{ $errors->has('email') ? 'is-invalid' : '' }}"
                            type="email" placeholder="Enter Email Address" name="email" id="email"
                            value="{{ old('email') }}" required>
                        @error('email')
                            <div class="invalid-feedback">
                                {{ $message }}
                            </div>
                        @enderror
                    </div>

                    <div class="mb-3">
                        <input class="form-control form-control-user {{ $errors->has('username') ? 'is-invalid' : '' }}"
                            type="text" placeholder="Enter Username" name="username" id="username"
                            value="{{ old('username') }}" required>
                        @error('username')
                            <div class="invalid-feedback">
                                {{ $message }}
                            </div>
                        @enderror
                    </div>

                    <div class="mb-3">
                        <input class="form-control form-control-user {{ $errors->has('password') ? 'is-invalid' : '' }}"
                            type="password" id="password" placeholder="Enter Password" name="password" required
                            autocomplete="current-password">
                        @error('password')
                            <div class="invalid-feedback">
                                {{ $message }}
                            </div>
                        @enderror
                    </div>

                    <div class="mb-3">
                        <input class="form-control form-control-user {{ $errors->has('password_confirmation') ? 'is-invalid' : '' }}"
                            type="password" id="password_confirmation" placeholder="Enter Password Again For Confirmation" name="password_confirmation" required>
                        @error('password_confirmation')
                            <div class="invalid-feedback">
                                {{ $message }}
                            </div>
                        @enderror
                    </div>

                    <button class="btn btn-primary d-block btn-user w-100" type="submit">Register</button>
                </form>
                <div class="text-center">
                    <a class="small" href="{{ route('admin.login') }}">Already Have An Account? Login Here</a>
                </div>
            </div>
        </div>
    </div>
@endsection
