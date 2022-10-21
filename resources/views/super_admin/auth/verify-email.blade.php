@extends('template.layouts.guest')

@section('content')
    <div class="row">
        <div class="col-lg-6 d-none d-lg-flex">
            <div class="flex-grow-1 bg-login-image" style="background-image: url(&quot;assets/img/dogs/image3.jpeg&quot;);">
            </div>
        </div>
        <div class="col-lg-6">
            <div class="p-5">
                <div class="text-center">
                    <h4 class="text-dark mb-4">Email Verification</h4>
                </div>

                <div class="card text-bg-primary mb-3">
                    <div class="card-body">
                        <p class="card-text">Thanks for signing up! Before getting started, could you verify your email address by clicking on the link we just emailed to you? If you didn\'t receive the email, we will gladly send you another.</p>
                    </div>
                </div>

                @if (session('status') == 'verification-link-sent')
                    <div class="card text-bg-success mb-3">
                        <div class="card-body">
                            <p class="card-text">A new verification link has been sent to the email address you provided during registration.</p>
                        </div>
                    </div>
                @endif

                <form class="user" method="POST" action="{{ route('super_admin.verification.send') }}">
                    @csrf

                    <button class="btn btn-primary d-block btn-user w-100" type="submit">Resend Verification Email</button>
                </form>

                <form class="user" method="POST" action="{{ route('super_admin.logout') }}">
                    @csrf

                    <button class="btn btn-danger d-block btn-user w-100" type="submit">Log Out</button>
                </form>
            </div>
        </div>
    </div>
@endsection
