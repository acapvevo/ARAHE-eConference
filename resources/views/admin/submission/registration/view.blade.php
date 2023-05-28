@extends('admin.layouts.app')

@section('styles')
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/select2-bootstrap-5-theme@1.3.0/dist/select2-bootstrap-5-theme.min.css" />
@endsection

@section('content')
    <h3 class="text-dark mb-1">Registration - Participant Detail</h3>

    <div class="card">
        <div class="card-body">
            @if ($registration->status_code === 'WR')
                <div class="pt-3 pb-3 d-grid gap-2 d-md-flex justify-content-md-end">
                    <form action="{{ route('admin.submission.registration.update', ['id' => $registration->id]) }}"
                        method="post">
                        @csrf
                        @method('PATCH')

                        <button class="btn btn-success me-md-2" type="submit" value="DR" name="decision">Accept</button>
                        @if ($registration->proof)
                            <button class="btn btn-warning me-md-2" type="submit" value="UR" name="decision">Reupload
                                Proof</button>
                        @endif
                        <button class="btn btn-danger" type="submit" value="RR" name="decision">Reject</button>
                    </form>
                </div>
            @elseif ($registration->status_code === 'PR')
                <div class="pt-3 pb-3 d-grid gap-2 d-md-flex justify-content-md-end">
                    <button type="button" class="btn btn-primary" data-bs-toggle="modal"
                        data-bs-target="#updateProofModal">
                        Update Payment
                    </button>
                </div>
            @endif
            <div class="table-responsive">
                <table class="table table-bordered">
                    <tbody>
                        <tr>
                            <th class="text-center table-primary" colspan='3'>Registration Details</th>
                        </tr>
                        <tr>
                            <th class='w-25'>Registration ID</th>
                            <td colspan="2">{{ $registration->code }}</td>
                        </tr>
                        <tr>
                            <th class='w-25'>Role</th>
                            <td colspan="2">{{ $registration->getType()->name }}</td>
                        </tr>
                        <tr>
                            <th class='w-25'>Locality</th>
                            <td colspan="2">{{ $registration->category->getLocality()->name }}</td>
                        </tr>
                        <tr>
                            <th class='w-25'>Category</th>
                            <td colspan="2">{{ $registration->category->name }}</td>
                        </tr>
                        @if ($registration->proof)
                            <tr>
                                <th class='w-25'>Proof for Chosen Category</th>
                                <td colspan="2">
                                    <form action="{{ route('admin.submission.registration.download') }}" method="post"
                                        target="_blank">
                                        @csrf
                                        <button type="submit" class="btn btn-link" name="registration_id"
                                            value="{{ $registration->id }}">{{ $registration->proof }}</button>
                                    </form>
                                </td>
                            </tr>
                        @endif
                        @if ($registration->link)
                            <tr>
                                <th class='w-25'>Registration Patner</th>
                                <td colspan="2">{{ $registration->linkParticipant->name }}</td>
                            </tr>
                        @endif
                        <tr>
                            <th class='w-25'>Dietary Preference</th>
                            <td colspan="2">{{ $registration->getDietary()->name }}</td>
                        </tr>
                        <tr>
                            <th class='w-25'>Status</th>
                            <td colspan="2">{{ $registration->getStatusDescription() }}</td>
                        </tr>
                        <tr>
                            <th class="text-center table-primary" colspan='3'>Participant</th>
                        </tr>
                        <tr>
                            <th class='w-25'>Name</th>
                            <td colspan="2">{{ $registration->participant->name }}</td>
                        </tr>
                        <tr>
                            <th class='w-25'>Email</th>
                            <td colspan="2">{{ $registration->participant->email }}</td>
                        </tr>
                        <tr>
                            <th class='w-25'>Institution</th>
                            <td colspan="2">{{ $registration->participant->institution->name }}</td>
                        </tr>
                        <tr>
                            <th class='w-25'>Faculty</th>
                            <td colspan="2">{{ $registration->participant->institution->faculty }}</td>
                        </tr>
                        <tr>
                            <th class='w-25'>Department</th>
                            <td colspan="2">{{ $registration->participant->institution->department }}</td>
                        </tr>
                        <tr>
                            <th class='w-25'>Address</th>
                            <td colspan="2">{{ $registration->participant->address->lineOne }},<br>
                                {{ $registration->participant->address->lineTwo }},<br>
                                {!! $registration->participant->address->lineThree
                                    ? $registration->participant->address->lineThree . ',<br>'
                                    : '' !!}
                                {{ $registration->participant->address->postcode }}
                                {{ $registration->participant->address->city }},<br>
                                {{ $registration->participant->address->state }},<br>
                                {{ $registration->participant->address->country }}
                            </td>
                        </tr>
                        <tr>
                            <th class='w-25'>Phone Number</th>
                            <td colspan="2">{{ $registration->participant->contact->phoneNumber }}</td>
                        </tr>
                        <tr>
                            <th class='w-25'>Fax Number</th>
                            <td colspan="2">{{ $registration->participant->contact->faxNumber }}</td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <div class="modal fade" id="updateProofModal" tabindex="-1" aria-labelledby="updateProofModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-xl">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="updateProofModalLabel">Modal title</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form action="{{ route('admin.submission.registration.update', ['id' => $registration->id]) }}"
                        method="post" id="updateProof" enctype="multipart/form-data">
                        @csrf
                        @method('PATCH')

                        <div class="mb-3">
                            <label for="payment_attempt" class="form-label">Payment Done <small>(Payment done by participant
                                    based on the proof given)</small></label>
                            <div class="row" id="payment_attempt">
                                <div class="col-md-4">
                                    <label for="date" class="form-label">Date</label>
                                    <input type="date"
                                        class="form-control {{ $errors->has('date') ? 'is-invalid' : '' }}" name="date"
                                        id="date" value="{{ old('date') }}">
                                    @error('date')
                                        <div class="invalid-feedback">
                                            {{ $message }}
                                        </div>
                                    @enderror
                                </div>
                                <div class="col-md-4">
                                    <label for="time" class="form-label">Time</label>
                                    <input type="time"
                                        class="form-control {{ $errors->has('time') ? 'is-invalid' : '' }}"
                                        name="time" id="time" value="{{ old('time') }}">
                                    @error('time')
                                        <div class="invalid-feedback">
                                            {{ $message }}
                                        </div>
                                    @enderror
                                </div>
                                <div class="col-md-4">
                                    <label for="timezone" class="form-label">Timezone</label>
                                    <select class="form-select {{ $errors->has('timezone') ? 'is-invalid' : '' }}"
                                        name="timezone" id="timezone">
                                    </select>
                                    @error('timezone')
                                        <div class="invalid-feedback">
                                            {{ $message }}
                                        </div>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <div class="mb-3">
                            <label for="proof" class="form-label">Payment Proof <small>(File: PDF, JPG, JPEG, PNG only
                                    Max Size: 2MB)</small></label>
                            <input class="form-control {{ $errors->has('proof') ? 'is-invalid' : '' }}" type="file"
                                id="proof" name="proof">
                            @error('proof')
                                <div class="invalid-feedback">
                                    {{ $message }}
                                </div>
                            @enderror
                        </div>
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                    <button type="submit" class="btn btn-primary" form="updateProof" name="decision"
                        value="UP">Update</button>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('scripts')
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
    <script>
        const updateProofModalEl = document.getElementById('updateProofModal')
        const timezoneInput = document.getElementById('timezone');
        const timezoneList = moment.tz.names();

        updateProofModalEl.addEventListener('shown.bs.modal', function(event) {
            timezoneList.forEach(timezone => {
                const offset = moment().tz(timezone).format('Z');

                if (timezone !== 'Asia/Kuala_Lumpur') {
                    var option = new Option(timezone + ' (GMT ' + offset + ')', timezone);
                } else {
                    var option = new Option(timezone + ' (GMT ' + offset + ')', timezone, false, true);
                }

                timezoneInput.appendChild(option);
            });

            $('#timezone').select2({
                dropdownParent: $('#updateProofModal'),
                theme: 'bootstrap-5',
                placeholder: 'Select Timezone',
            });
        })

        @if ($errors->has('proof') || $errors->has('date') || $errors->has('time') || $errors->has('timezone'))
            const updateProofModal = new bootstrap.Modal('#updateProofModal');
            updateProofModal.show();
        @endif
    </script>
@endsection
