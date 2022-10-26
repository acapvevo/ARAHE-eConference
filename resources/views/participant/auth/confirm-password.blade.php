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
                    <h4 class="text-dark mb-4">Password Confimation</h4>
                </div>

                <div class="card text-bg-primary mb-3">
                    <div class="card-body">
                        <p class="card-text">This is a secure area of the application. Please confirm your password before continuing.</p>
                    </div>
                </div>

                @if (session()->has('status'))
                    <div class="card text-bg-success mb-3">
                        <div class="card-body">
                            <p class="card-text">{{session('status')}}</p>
                        </div>
                    </div>
                @endif

                <form class="user" method="POST" action="{{ route('participant.password.confirm') }}">
                    @csrf
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

                    <button class="btn btn-primary d-block btn-user w-100" type="submit">Confirm</button>
                </form>
            </div>
        </div>
    </div>
@endsection
