@extends('layouts.guest')

@section('content')
    <div class="p-5">
        <div class="text-center">
            <h4 class="text-dark mb-4">Reset Password</h4>
        </div>

        <div class="card text-bg-primary mb-3">
            <div class="card-body">
                <p class="card-text">Forgot your password? No problem. Just let us know your email address and we will email
                    you a password reset link that will allow you to choose a new one.</p>
            </div>
        </div>

        @if (session()->has('status'))
            <div class="card text-bg-success mb-3">
                <div class="card-body">
                    <p class="card-text">{{ session('status') }}</p>
                </div>
            </div>
        @endif

        <form class="user" method="POST" action="{{ route('super_admin.password.update') }}">
            @csrf

            <!-- Password Reset Token -->
            <input type="hidden" name="token" value="{{ $request->route('token') }}">
            @error('token')
                <div class="invalid-feedback">
                    {{ $message }}
                </div>
            @enderror

            <div class="mb-3">
                <input class="form-control form-control-user {{ $errors->has('email') ? 'is-invalid' : '' }}" type="email"
                    id="email" placeholder="Enter Email Address" name="email" required
                    value="{{ old('email', $request->email) }}">
                @error('email')
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
                <input
                    class="form-control form-control-user {{ $errors->has('password_confirmation') ? 'is-invalid' : '' }}"
                    type="password" id="password_confirmation" placeholder="Enter Password Again"
                    name="password_confirmation" required autocomplete="password_confirmation">
                @error('password_confirmation')
                    <div class="invalid-feedback">
                        {{ $message }}
                    </div>
                @enderror
            </div>

            <button class="btn btn-primary d-block btn-user w-100" type="submit">Email Password Reset Link</button>
        </form>
    </div>
@endsection
