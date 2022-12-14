
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
                    <h4 class="text-dark mb-4">Request Password Reset</h4>
                </div>

                <div class="card text-bg-primary mb-3">
                    <div class="card-body">
                        <p class="card-text">Forgot your password? No problem. Just let us know your email address and we will email you a password reset link that will allow you to choose a new one.</p>
                    </div>
                </div>

                @if (session()->has('status'))
                    <div class="card text-bg-success mb-3">
                        <div class="card-body">
                            <p class="card-text">{{session('status')}}</p>
                        </div>
                    </div>
                @endif

                <form class="user" method="POST" action="{{ route('participant.password.email') }}">
                    @csrf
                    <div class="mb-3">
                        <input class="form-control form-control-user {{ $errors->has('email') ? 'is-invalid' : '' }}"
                            type="email" id="email" placeholder="Enter Email Address" name="email" value="{{old('email')}}" required>
                        @error('email')
                            <div class="invalid-feedback">
                                {{ $message }}
                            </div>
                        @enderror
                    </div>

                    <button class="btn btn-primary d-block btn-user w-100" type="submit">Email Password Reset Link</button>
                </form>
            </div>
        </div>
    </div>
@endsection
