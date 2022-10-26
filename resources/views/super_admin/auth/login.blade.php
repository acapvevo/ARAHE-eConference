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
                    <h4 class="text-dark mb-4">Login</h4>
                </div>

                @if (session()->has('status'))
                    <div class="card text-bg-success mb-3">
                        <div class="card-body">
                            <p class="card-text">{{session('status')}}</p>
                        </div>
                    </div>
                @endif

                <form class="user" method="POST" action="{{ route('super_admin.login') }}">
                    @csrf

                    <div class="mb-3">
                        <input class="form-control form-control-user {{ $errors->has('username') ? 'is-invalid' : '' }}"
                            type="text" placeholder="Enter Username" name="username" id="username"
                            value="{{ old('username') }}" required autofocus>
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
                        <div class="custom-control custom-checkbox small">
                            <div class="form-check"><input class="form-check-input custom-control-input" type="checkbox"
                                    id="remember_me" name="remember"><label class="form-check-label custom-control-label"
                                    for="remember_me">Remember Me</label></div>
                        </div>
                    </div>

                    <button class="btn btn-primary d-block btn-user w-100" type="submit">Login</button>

                    <hr><a class="btn btn-primary d-block btn-google btn-user w-100 mb-2" role="button"><i
                            class="fab fa-google"></i>&nbsp; Login with Google</a><a
                        class="btn btn-primary d-block btn-facebook btn-user w-100" role="button"><i
                            class="fab fa-facebook-f"></i>&nbsp; Login with Facebook</a>
                    <hr>
                </form>
                <div class="text-center"><a class="small" href="{{ route('super_admin.password.request') }}">Forgot
                        Password?</a></div>
                {{-- <div class="text-center"><a class="small" href="{{ route('admin.register') }}">Create an Account!</a></div> --}}
            </div>
        </div>
    </div>
@endsection
