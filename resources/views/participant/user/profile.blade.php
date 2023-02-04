@extends('participant.layouts.app')

@section('styles')
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/intl-tel-input/17.0.8/css/intlTelInput.css" />
@endsection

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
                        <th colspan="2" class="text-center"><strong>Account</strong></th>
                    </tr>
                    <tr>
                        <th class="w-25">Name: </th>
                        <td>{{ $user->name }}</td>
                    </tr>
                    <tr>
                        <th class="w-25">Email: </th>
                        <td>{{ $user->email }}</td>
                    </tr>

                    <tr>
                        <th colspan="2" class="text-center"><strong>Institution</strong></th>
                    </tr>
                    <tr>
                        <th class="w-25">University: </th>
                        <td>{{ $user->institution->name }}</td>
                    </tr>
                    <tr>
                        <th class="w-25">Faculty: </th>
                        <td>{{ $user->institution->faculty }}</td>
                    </tr>
                    <tr>
                        <th class="w-25">Department: </th>
                        <td>{{ $user->institution->department }}</td>
                    </tr>


                    <tr>
                        <th colspan="2" class="text-center"><strong>Address</strong></th>
                    </tr>
                    <tr>
                        <th class="w-25">Line 1: </th>
                        <td>{{ $user->address->lineOne }}</td>
                    </tr>
                    <tr>
                        <th class="w-25">Line 2: </th>
                        <td>{{ $user->address->lineTwo }}</td>
                    </tr>
                    <tr>
                        <th class="w-25">Line 3: </th>
                        <td>{{ $user->address->lineThree }}</td>
                    </tr>
                    <tr>
                        <th class="w-25">City: </th>
                        <td>{{ $user->address->city }}</td>
                    </tr>
                    <tr>
                        <th class="w-25">Postcode: </th>
                        <td>{{ $user->address->postcode }}</td>
                    </tr>
                    <tr>
                        <th class="w-25">State: </th>
                        <td>{{ $user->address->state }}</td>
                    </tr>
                    <tr>
                        <th class="w-25">Country: </th>
                        <td>{{ $user->address->country }}</td>
                    </tr>


                    <tr>
                        <th colspan="2" class="text-center"><strong>Contact</strong></th>
                    </tr>
                    <tr>
                        <th class="w-25">Phone Number: </th>
                        <td>{{ $user->contact->phoneNumber }}</td>
                    </tr>
                    <tr>
                        <th class="w-25">Fax Number: </th>
                        <td>{{ $user->contact->faxNumber }}</td>
                    </tr>
                </table>
            </div>
        </div>
    </div>

    <div class="modal fade" id="UpdateProfileModal" tabindex="-1" aria-labelledby="UpdateProfileModalLabel"
        aria-hidden="true">
        <div class="modal-dialog modal-xl">
            <form action="{{ route('participant.user.profile.update') }}" method="post" onsubmit="process(event)"
                id="myForm">
                @csrf
                @method('PATCH')
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="UpdateProfileModalLabel">Update Profile</h5>
                        <button class="btn-close" type="button" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <nav>
                            <div class="nav nav-tabs nav-justified" id="nav-tab" role="tablist">
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
                                <button class="nav-link" id="nav-contact-tab" data-bs-toggle="tab"
                                    data-bs-target="#nav-contact" type="button" role="tab" aria-controls="nav-contact"
                                    aria-selected="false">Contact
                                    @error('contact.*')
                                        <span class="badge text-bg-danger">!</span>
                                    @enderror
                                </button>
                                <button class="nav-link" id="nav-address-tab" data-bs-toggle="tab"
                                    data-bs-target="#nav-address" type="button" role="tab" aria-controls="nav-address"
                                    aria-selected="false">Address
                                    @error('address.*')
                                        <span class="badge text-bg-danger">!</span>
                                    @enderror
                                </button>
                            </div>
                        </nav>
                        <div class="tab-content p-3" id="nav-tabContent">
                            <div class="tab-pane fade show active" id="nav-account" role="tabpanel"
                                aria-labelledby="nav-account-tab" tabindex="0">

                                <div class="mb-3">
                                    <label for="account.name" class="form-label">Name</label>
                                    <input type="text"
                                        class="form-control {{ $errors->has('account.name') ? 'is-invalid' : '' }}"
                                        placeholder="Enter Name" name="account[name]" id="account.name"
                                        value="{{ old('account.name', $user->name) }}">
                                    @error('account.name')
                                        <div class="invalid-feedback">
                                            {{ $message }}
                                        </div>
                                    @enderror
                                </div>

                                <div class="mb-3">
                                    <label for="account.email" class="form-label">Email Address</label>
                                    <input type="email"
                                        class="form-control {{ $errors->has('account.email') ? 'is-invalid' : '' }}"
                                        placeholder="Enter Email Address" name="account[email]" id="account.email"
                                        value="{{ old('account.email', $user->email) }}">
                                    @error('account.email')
                                        <div class="invalid-feedback">
                                            {{ $message }}
                                        </div>
                                    @enderror
                                </div>

                            </div>
                            <div class="tab-pane fade" id="nav-institution" role="tabpanel"
                                aria-labelledby="nav-institution-tab" tabindex="0">

                                <div class="mb-3">
                                    <label for="institution.university" class="form-label">University</label>
                                    <input type="text"
                                        class="form-control {{ $errors->has('institution.university') ? 'is-invalid' : '' }}"
                                        placeholder="Enter University" name="institution[university]"
                                        id="institution.university"
                                        value="{{ old('institution.university', $user->institution->name) }}"
                                        list="universityList">
                                    <datalist id="universityList">
                                    </datalist>
                                    @error('institution.university')
                                        <div class="invalid-feedback">
                                            {{ $message }}
                                        </div>
                                    @enderror
                                </div>

                                <div class="mb-3">
                                    <label for="institution.faculty" class="form-label">Faculty</label>
                                    <input type="text"
                                        class="form-control {{ $errors->has('institution.faculty') ? 'is-invalid' : '' }}"
                                        placeholder="Enter Faculty" name="institution[faculty]" id="institution.faculty"
                                        value="{{ old('institution.faculty', $user->institution->faculty) }}">
                                    @error('institution.faculty')
                                        <div class="invalid-feedback">
                                            {{ $message }}
                                        </div>
                                    @enderror
                                </div>

                                <div class="mb-3">
                                    <label for="institution.department" class="form-label">Department</label>
                                    <input type="text"
                                        class="form-control {{ $errors->has('institution.department') ? 'is-invalid' : '' }}"
                                        placeholder="Enter Department" name="institution[department]"
                                        id="institution.department"
                                        value="{{ old('institution.department', $user->institution->department) }}">
                                    @error('institution.department')
                                        <div class="invalid-feedback">
                                            {{ $message }}
                                        </div>
                                    @enderror
                                </div>

                            </div>
                            <div class="tab-pane fade" id="nav-contact" role="tabpanel"
                                aria-labelledby="nav-contact-tab" tabindex="0">

                                <div class="mb-3">
                                    <label for="contact.phoneNumber" class="form-label">Phone Number</label>
                                    <input type="tel"
                                        class="form-control {{ $errors->has('contact.phoneNumber') ? 'is-invalid' : '' }}"
                                        placeholder="Enter Phone Number" name="contact[phoneNumber]"
                                        id="contact.phoneNumber"
                                        value="{{ old('contact.phoneNumber', $user->contact->phoneNumber) }}">
                                    @error('contact.phoneNumber')
                                        <div class="invalid-feedback" style="display: block;">
                                            {{ $message }}
                                        </div>
                                    @enderror
                                    <div class="invalid-feedback" id="alert-error-phoneNumber" style="display: none;">
                                    </div>
                                </div>

                                <div class="mb-3">
                                    <label for="contact.faxNumber" class="form-label">Fax Number</label>
                                    <input type="tel"
                                        class="form-control {{ $errors->has('contact.faxNumber') ? 'is-invalid' : '' }}"
                                        placeholder="Enter Fax Number" name="contact[faxNumber]" id="contact.faxNumber"
                                        value="{{ old('contact.faxNumber', $user->contact->faxNumber) }}">
                                    @error('contact.faxNumber')
                                        <div class="invalid-feedback" style="display: block;">
                                            {{ $message }}
                                        </div>
                                    @enderror
                                    <div class="invalid-feedback" id="alert-error-faxNumber" style="display: none;">
                                    </div>
                                </div>

                            </div>
                            <div class="tab-pane fade" id="nav-address" role="tabpanel"
                                aria-labelledby="nav-address-tab" tabindex="0">

                                <div class="mb-3">
                                    <label for="address.lineOne" class="form-label">Address Line 1</label>
                                    <input type="text"
                                        class="form-control {{ $errors->has('address.lineOne') ? 'is-invalid' : '' }}"
                                        placeholder="Enter Address Line 1" name="address[lineOne]" id="address.lineOne"
                                        value="{{ old('address.lineOne', $user->address->lineOne) }}">
                                    @error('address.lineOne')
                                        <div class="invalid-feedback">
                                            {{ $message }}
                                        </div>
                                    @enderror
                                </div>

                                <div class="mb-3">
                                    <label for="address.lineTwo" class="form-label">Address Line 2</label>
                                    <input type="text"
                                        class="form-control {{ $errors->has('address.lineTwo') ? 'is-invalid' : '' }}"
                                        placeholder="Enter Address Line 2" name="address[lineTwo]" id="address.lineTwo"
                                        value="{{ old('address.lineTwo', $user->address->lineTwo) }}">
                                    @error('address.lineTwo')
                                        <div class="invalid-feedback">
                                            {{ $message }}
                                        </div>
                                    @enderror
                                </div>

                                <div class="mb-3">
                                    <label for="address.lineThree" class="form-label">Address Line 3</label>
                                    <input type="text"
                                        class="form-control {{ $errors->has('address.lineThree') ? 'is-invalid' : '' }}"
                                        placeholder="Enter Address Line 3" name="address[lineThree]"
                                        id="address.lineThree"
                                        value="{{ old('address.lineThree', $user->address->lineThree) }}">
                                    @error('address.lineThree')
                                        <div class="invalid-feedback">
                                            {{ $message }}
                                        </div>
                                    @enderror
                                </div>

                                <div class="mb-3">
                                    <label for="address.city" class="form-label">City</label>
                                    <input type="text"
                                        class="form-control {{ $errors->has('address.city') ? 'is-invalid' : '' }}"
                                        placeholder="Enter City" name="address[city]" id="address.city"
                                        value="{{ old('address.city', $user->address->city) }}">
                                    @error('address.city')
                                        <div class="invalid-feedback">
                                            {{ $message }}
                                        </div>
                                    @enderror
                                </div>

                                <div class="mb-3">
                                    <label for="address.postcode" class="form-label">Postcode</label>
                                    <input type="text"
                                        class="form-control {{ $errors->has('address.postcode') ? 'is-invalid' : '' }}"
                                        placeholder="Enter Postcode" name="address[postcode]" id="address.postcode"
                                        value="{{ old('address.postcode', $user->address->postcode) }}">
                                    @error('address.postcode')
                                        <div class="invalid-feedback">
                                            {{ $message }}
                                        </div>
                                    @enderror
                                </div>

                                <div class="mb-3">
                                    <label for="address.state" class="form-label">State</label>
                                    <input type="text"
                                        class="form-control {{ $errors->has('address.state') ? 'is-invalid' : '' }}"
                                        placeholder="Enter State" name="address[state]" id="address.state"
                                        value="{{ old('address.state', $user->address->state) }}" list="stateList">
                                    <datalist id="stateList">
                                    </datalist>
                                    @error('address.state')
                                        <div class="invalid-feedback">
                                            {{ $message }}
                                        </div>
                                    @enderror
                                </div>

                                <div class="mb-3">
                                    <label for="address.country" class="form-label">Country</label>
                                    <input type="text"
                                        class="form-control {{ $errors->has('address.country') ? 'is-invalid' : '' }}"
                                        placeholder="Enter Country" name="address[country]" id="address.country"
                                        value="{{ old('address.country', $user->address->country) }}" list="countryList">
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
                    <div class="modal-footer">
                        <button class="btn btn-secondary" type="button" data-bs-dismiss="modal">Close</button>
                        <button class="btn btn-primary" type="submit">Save</button>
                    </div>
                </div>
            </form>
        </div>
    </div>
@endsection

@section('scripts')
    <script src="https://cdnjs.cloudflare.com/ajax/libs/intl-tel-input/17.0.8/js/intlTelInput.min.js"></script>
    <script src="{{ asset('js/participantForm.js') }}"></script>

    @if ($errors->isNotEmpty())
        <script>
            const UpdateProfileModal = new bootstrap.Modal('#UpdateProfileModal');
            UpdateProfileModal.show();
        </script>
    @endif
@endsection
