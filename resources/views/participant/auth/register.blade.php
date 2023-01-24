@extends('layouts.guest')

@section('styles')
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/intl-tel-input/17.0.8/css/intlTelInput.css" />
@endsection

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

                <form class="user" method="POST" action="{{ route('participant.register') }}" onsubmit="process(event)"
                    id="myForm">
                    @csrf

                    <nav>
                        <div class="nav nav-pills" id="nav-tab" role="tablist">
                            <button class="nav-link active" id="nav-account-tab" data-bs-toggle="tab"
                                data-bs-target="#nav-account" type="button" role="tab" aria-controls="nav-account"
                                aria-selected="true">Account Detail</button>
                            <button class="nav-link" id="nav-institute-tab" data-bs-toggle="tab"
                                data-bs-target="#nav-institute" type="button" role="tab" aria-controls="nav-institute"
                                aria-selected="false">Institute</button>
                            <button class="nav-link" id="nav-contact-tab" data-bs-toggle="tab" data-bs-target="#nav-contact"
                                type="button" role="tab" aria-controls="nav-contact"
                                aria-selected="false">Contact</button>
                            <button class="nav-link" id="nav-address-tab" data-bs-toggle="tab" data-bs-target="#nav-address"
                                type="button" role="tab" aria-controls="nav-address"
                                aria-selected="false">Address</button>
                        </div>
                    </nav>
                    <div class="tab-content pt-3" id="nav-tabContent">
                        <div class="tab-pane fade show active" id="nav-account" role="tabpanel"
                            aria-labelledby="nav-account-tab" tabindex="0">

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
                                <input
                                    class="form-control form-control-user {{ $errors->has('email') ? 'is-invalid' : '' }}"
                                    type="email" placeholder="Enter Email Address" name="email" id="email"
                                    value="{{ old('email') }}" required>
                                @error('email')
                                    <div class="invalid-feedback">
                                        {{ $message }}
                                    </div>
                                @enderror
                            </div>

                            <div class="mb-3">
                                <input
                                    class="form-control form-control-user {{ $errors->has('password') ? 'is-invalid' : '' }}"
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
                                    type="password" id="password_confirmation"
                                    placeholder="Enter Password Again For Confirmation" name="password_confirmation"
                                    required>
                                @error('password_confirmation')
                                    <div class="invalid-feedback">
                                        {{ $message }}
                                    </div>
                                @enderror
                            </div>

                        </div>
                        <div class="tab-pane fade" id="nav-institute" role="tabpanel" aria-labelledby="nav-institute-tab"
                            tabindex="0">

                            <div class="mb-3">
                                <input
                                    class="form-control form-control-user {{ $errors->has('university') ? 'is-invalid' : '' }}"
                                    type="text" placeholder="Enter University" list="universityList" name="university"
                                    id="university" value="{{ old('university') }}" required>
                                <datalist id="universityList">
                                </datalist>
                                @error('university')
                                    <div class="invalid-feedback">
                                        {{ $message }}
                                    </div>
                                @enderror
                            </div>

                            <div class="mb-3">
                                <input
                                    class="form-control form-control-user {{ $errors->has('faculty') ? 'is-invalid' : '' }}"
                                    type="text" placeholder="Enter Faculty" name="faculty" id="faculty"
                                    value="{{ old('faculty') }}" required>
                                @error('faculty')
                                    <div class="invalid-feedback">
                                        {{ $message }}
                                    </div>
                                @enderror
                            </div>

                            <div class="mb-3">
                                <input
                                    class="form-control form-control-user {{ $errors->has('department') ? 'is-invalid' : '' }}"
                                    type="text" placeholder="Enter Department" name="department" id="department"
                                    value="{{ old('department') }}">
                                @error('department')
                                    <div class="invalid-feedback">
                                        {{ $message }}
                                    </div>
                                @enderror
                            </div>

                        </div>
                        <div class="tab-pane fade" id="nav-contact" role="tabpanel" aria-labelledby="nav-contact-tab"
                            tabindex="0">

                            <div class="mb-3" width="100%">
                                <input
                                    class="form-control form-control-user {{ $errors->has('telephoneNumber') ? 'is-invalid' : '' }}"
                                    type="tel" id="telephoneNumber" placeholder="Enter Phone Number"
                                    name="telephoneNumber" required>
                                <div class="invalid-feedback" id="alert-error-telephoneNumber" style="display: none;">
                                </div>
                                @error('telephoneNumber')
                                    <div class="invalid-feedback">
                                        {{ $message }}
                                    </div>
                                @enderror
                            </div>

                            <div class="mb-3" width="100%">
                                <input
                                    class="form-control form-control-user {{ $errors->has('faxNumber') ? 'is-invalid' : '' }}"
                                    type="tel" id="faxNumber" placeholder="Enter Fax Number" name="faxNumber"
                                    required>
                                <div class="invalid-feedback" id="alert-error-faxNumber" style="display: none;">
                                </div>
                                @error('faxNumber')
                                    <div class="invalid-feedback">
                                        {{ $message }}
                                    </div>
                                @enderror
                            </div>

                        </div>
                        <div class="tab-pane fade" id="nav-address" role="tabpanel" aria-labelledby="nav-address-tab"
                            tabindex="0">

                            <div class="mb-3">
                                <input
                                    class="form-control form-control-user {{ $errors->has('lineOne') ? 'is-invalid' : '' }}"
                                    type="text" placeholder="Enter Address Line 1" name="lineOne" id="lineOne"
                                    value="{{ old('lineOne') }}" required>
                                @error('lineOne')
                                    <div class="invalid-feedback">
                                        {{ $message }}
                                    </div>
                                @enderror
                            </div>

                            <div class="mb-3">
                                <input
                                    class="form-control form-control-user {{ $errors->has('lineTwo') ? 'is-invalid' : '' }}"
                                    type="text" placeholder="Enter Address Line 2" name="lineTwo" id="lineTwo"
                                    value="{{ old('lineTwo') }}" required>
                                @error('lineTwo')
                                    <div class="invalid-feedback">
                                        {{ $message }}
                                    </div>
                                @enderror
                            </div>

                            <div class="mb-3">
                                <input
                                    class="form-control form-control-user {{ $errors->has('lineThree') ? 'is-invalid' : '' }}"
                                    type="text" placeholder="Enter Address Line 3" name="lineThree" id="lineThree"
                                    value="{{ old('lineThree') }}">
                                @error('lineThree')
                                    <div class="invalid-feedback">
                                        {{ $message }}
                                    </div>
                                @enderror
                            </div>

                            <div class="row mb-3">
                                <div class="col">
                                    <input
                                        class="form-control form-control-user {{ $errors->has('city') ? 'is-invalid' : '' }}"
                                        type="text" placeholder="Enter City" name="city" id="city"
                                        value="{{ old('city') }}">
                                    @error('city')
                                        <div class="invalid-feedback">
                                            {{ $message }}
                                        </div>
                                    @enderror
                                </div>
                                <div class="col">
                                    <input
                                        class="form-control form-control-user {{ $errors->has('postcode') ? 'is-invalid' : '' }}"
                                        type="text" placeholder="Enter Postcode" name="postcode" id="postcode"
                                        value="{{ old('postcode') }}">
                                    @error('postcode')
                                        <div class="invalid-feedback">
                                            {{ $message }}
                                        </div>
                                    @enderror
                                </div>
                            </div>

                            <div class="row mb-3">
                                <div class="col">
                                    <input
                                        class="form-control form-control-user {{ $errors->has('state') ? 'is-invalid' : '' }}"
                                        type="text" placeholder="Enter State" name="state" id="state"
                                        value="{{ old('state') }}" list="stateList">
                                    <datalist id="stateList">
                                    </datalist>
                                    @error('city')
                                        <div class="invalid-feedback">
                                            {{ $message }}
                                        </div>
                                    @enderror
                                </div>
                                <div class="col">
                                    <input
                                        class="form-control form-control-user {{ $errors->has('country') ? 'is-invalid' : '' }}"
                                        type="text" placeholder="Enter Country" name="country" id="country"
                                        value="{{ old('country') }}" list="countryList">
                                    <datalist id="countryList">
                                    </datalist>
                                    @error('country')
                                        <div class="invalid-feedback">
                                            {{ $message }}
                                        </div>
                                    @enderror
                                </div>
                            </div>

                        </div>
                    </div>

                    <button class="btn btn-primary d-block btn-user w-100" type="submit">Register</button>
                </form>
                <div class="text-center pt-3">
                    <a class="small" href="{{ route('participant.login') }}">Already Have An Account? Login Here</a>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('scripts')
    <script src="https://cdnjs.cloudflare.com/ajax/libs/intl-tel-input/17.0.8/js/intlTelInput.min.js"></script>
    <script src="{{ asset('js/participantForm.js') }}"></script>
@endsection
