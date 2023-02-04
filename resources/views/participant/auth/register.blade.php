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
                        <div class="nav nav-pills nav-justified" id="nav-tab" role="tablist">
                            <button class="nav-link active" id="nav-account-tab" data-bs-toggle="tab"
                                data-bs-target="#nav-account" type="button" role="tab" aria-controls="nav-account"
                                aria-selected="true">Account
                                @error('account.*')
                                    <span class="badge text-bg-danger">!</span>
                                @enderror
                            </button>
                            <button class="nav-link" id="nav-institution-tab" data-bs-toggle="tab"
                                data-bs-target="#nav-institution" type="button" role="tab"
                                aria-controls="nav-institution" aria-selected="false">Institution
                                @error('institution.*')
                                    <span class="badge text-bg-danger">!</span>
                                @enderror
                            </button>
                            <button class="nav-link" id="nav-contact-tab" data-bs-toggle="tab" data-bs-target="#nav-contact"
                                type="button" role="tab" aria-controls="nav-contact" aria-selected="false">Contact
                                @error('contact.*')
                                    <span class="badge text-bg-danger">!</span>
                                @enderror
                            </button>
                            <button class="nav-link" id="nav-address-tab" data-bs-toggle="tab" data-bs-target="#nav-address"
                                type="button" role="tab" aria-controls="nav-address" aria-selected="false">Address
                                @error('address.*')
                                    <span class="badge text-bg-danger">!</span>
                                @enderror
                            </button>
                        </div>
                    </nav>
                    <div class="tab-content pt-3" id="nav-tabContent">
                        <div class="tab-pane fade show active" id="nav-account" role="tabpanel"
                            aria-labelledby="nav-account-tab" tabindex="0">

                            <div class="mb-3">
                                <input
                                    class="form-control form-control-user {{ $errors->has('account.name') ? 'is-invalid' : '' }}"
                                    type="text" placeholder="Enter Name" name="account[name]" id="account.name"
                                    value="{{ old('account.name') }}" autofocus>
                                @error('account.name')
                                    <div class="invalid-feedback">
                                        {{ $message }}
                                    </div>
                                @enderror
                            </div>

                            <div class="mb-3">
                                <input
                                    class="form-control form-control-user {{ $errors->has('account.email') ? 'is-invalid' : '' }}"
                                    type="email" placeholder="Enter Email Address" name="account[email]"
                                    id="account.email" value="{{ old('account.email') }}">
                                @error('account.email')
                                    <div class="invalid-feedback">
                                        {{ $message }}
                                    </div>
                                @enderror
                            </div>

                            <div class="mb-3">
                                <input
                                    class="form-control form-control-user {{ $errors->has('account.password') ? 'is-invalid' : '' }}"
                                    type="password" placeholder="Enter Password" name="account[password]"
                                    id="account.password" autocomplete="current-password">
                                @error('account.password')
                                    <div class="invalid-feedback">
                                        {{ $message }}
                                    </div>
                                @enderror
                            </div>

                            <div class="mb-3">
                                <input
                                    class="form-control form-control-user {{ $errors->has('account.password_confirmation') ? 'is-invalid' : '' }}"
                                    type="password" placeholder="Enter Password Again For Confirmation"
                                    name="account[password_confirmation]" id="account.password_confirmation">
                                @error('account.password_confirmation')
                                    <div class="invalid-feedback">
                                        {{ $message }}
                                    </div>
                                @enderror
                            </div>

                        </div>
                        <div class="tab-pane fade" id="nav-institution" role="tabpanel"
                            aria-labelledby="nav-institution-tab" tabindex="0">

                            <div class="mb-3">
                                <input
                                    class="form-control form-control-user {{ $errors->has('institution.university') ? 'is-invalid' : '' }}"
                                    type="text" placeholder="Enter University" list="universityList"
                                    name="institution[university]" id="institution.university"
                                    value="{{ old('institution.university') }}">
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
                                    class="form-control form-control-user {{ $errors->has('institution.faculty') ? 'is-invalid' : '' }}"
                                    type="text" placeholder="Enter Faculty" name="institution[faculty]"
                                    id="institution.faculty" value="{{ old('institution.faculty') }}">
                                @error('institution.faculty')
                                    <div class="invalid-feedback">
                                        {{ $message }}
                                    </div>
                                @enderror
                            </div>

                            <div class="mb-3">
                                <input
                                    class="form-control form-control-user {{ $errors->has('institution.department') ? 'is-invalid' : '' }}"
                                    type="text" placeholder="Enter Department" name="institution[department]"
                                    id="institution.department" value="{{ old('institution.department') }}">
                                @error('institution.department')
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
                                    class="form-control form-control-user {{ $errors->has('contact.phoneNumber') ? 'is-invalid' : '' }}"
                                    type="tel" id="contact.phoneNumber" placeholder="Enter Phone Number"
                                    name="contact[phoneNumber]" value="{{ old('contact.phoneNumber') }}">
                                <div class="invalid-feedback" id="alert-error-phoneNumber" style="display: none;">
                                </div>
                                @error('contact.phoneNumber')
                                    <div class="invalid-feedback">
                                        {{ $message }}
                                    </div>
                                @enderror
                            </div>

                            <div class="mb-3" width="100%">
                                <input
                                    class="form-control form-control-user {{ $errors->has('contact.faxNumber') ? 'is-invalid' : '' }}"
                                    type="tel" id="contact.faxNumber" placeholder="Enter Fax Number"
                                    name="contact[faxNumber]" value="{{ old('contact.faxNumber') }}">
                                <div class="invalid-feedback" id="alert-error-faxNumber" style="display: none;">
                                </div>
                                @error('contact.faxNumber')
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
                                    class="form-control form-control-user {{ $errors->has('address.lineOne') ? 'is-invalid' : '' }}"
                                    type="text" placeholder="Enter Address Line 1" name="address[lineOne]"
                                    id="address.lineOne" value="{{ old('address.lineOne') }}">
                                @error('address.lineOne')
                                    <div class="invalid-feedback">
                                        {{ $message }}
                                    </div>
                                @enderror
                            </div>

                            <div class="mb-3">
                                <input
                                    class="form-control form-control-user {{ $errors->has('address.lineTwo') ? 'is-invalid' : '' }}"
                                    type="text" placeholder="Enter Address Line 2" name="address[lineTwo]"
                                    id="address.lineTwo" value="{{ old('address.lineTwo') }}">
                                @error('address.lineTwo')
                                    <div class="invalid-feedback">
                                        {{ $message }}
                                    </div>
                                @enderror
                            </div>

                            <div class="mb-3">
                                <input
                                    class="form-control form-control-user {{ $errors->has('address.lineThree') ? 'is-invalid' : '' }}"
                                    type="text" placeholder="Enter Address Line 3" name="address[lineThree]"
                                    id="address.lineThree" value="{{ old('address.lineThree') }}">
                                @error('address.lineThree')
                                    <div class="invalid-feedback">
                                        {{ $message }}
                                    </div>
                                @enderror
                            </div>

                            <div class="row mb-3">
                                <div class="col">
                                    <input
                                        class="form-control form-control-user {{ $errors->has('address.city') ? 'is-invalid' : '' }}"
                                        type="text" placeholder="Enter City" name="address[city]" id="address.city"
                                        value="{{ old('address.city') }}">
                                    @error('address.city')
                                        <div class="invalid-feedback">
                                            {{ $message }}
                                        </div>
                                    @enderror
                                </div>
                                <div class="col">
                                    <input
                                        class="form-control form-control-user {{ $errors->has('address.postcode') ? 'is-invalid' : '' }}"
                                        type="text" placeholder="Enter Postcode" name="address[postcode]"
                                        id="address.postcode" value="{{ old('address.postcode') }}">
                                    @error('address.postcode')
                                        <div class="invalid-feedback">
                                            {{ $message }}
                                        </div>
                                    @enderror
                                </div>
                            </div>

                            <div class="row mb-3">
                                <div class="col">
                                    <input
                                        class="form-control form-control-user {{ $errors->has('address.state') ? 'is-invalid' : '' }}"
                                        type="text" placeholder="Enter State" name="address[state]"
                                        id="address.state" value="{{ old('address.state') }}" list="stateList">
                                    <datalist id="stateList">
                                    </datalist>
                                    @error('address.state')
                                        <div class="invalid-feedback">
                                            {{ $message }}
                                        </div>
                                    @enderror
                                </div>
                                <div class="col">
                                    <input
                                        class="form-control form-control-user {{ $errors->has('address.country') ? 'is-invalid' : '' }}"
                                        type="text" placeholder="Enter Country" name="address[country]"
                                        id="address.country" value="{{ old('address.country') }}" list="countryList">
                                    <datalist id="countryList">
                                    </datalist>
                                    @error('address.country')
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
