@extends('participant.layouts.app')

@section('styles')
@endsection

@section('content')
    <h3 class="text-dark mb-1">Registration - Registration Detail (ARAHE {{ $registration->form->session->year }})</h3>

    <div class="card">
        <div class="card-body">
            <div class="row pt-3 pb-3">
                <div class="col">
                    <button type="button" class="btn btn-primary float-end" data-bs-toggle="modal"
                        data-bs-target="#createRegistrationModal">
                        Make New Registration
                    </button>
                </div>
            </div>
            <div class="table-responsive">
                <table class="table table-bordered" id="table_id">
                    <tbody>
                        <tr>
                            <th class='w-25'>Registration ID</th>
                            <td>{{ $registration->status_code != 'NR' ? $registration->code : '' }}</td>
                        </tr>
                        <tr>
                            <th class='w-25'>Register As</th>
                            <td>{{ strtoupper($registration->register_as) }}</td>
                        </tr>
                        <tr>
                            <th class='w-25'>Fee Category</th>
                            <td>{{ $registration->category->name ?? '' }}</td>
                        </tr>
                        <tr>
                            <th class='w-25'>Proof</th>
                            @if ($registration->proof)
                                <td>
                                    <form action="{{ route('participant.competition.registration.download') }}"
                                        method="post" target="_blank">
                                        @csrf
                                        <input type="hidden" name="filename" value="{{ $registration->proof }}">
                                        <button type="submit" class="btn btn-link" name="registration_id"
                                            value="{{ $registration->id }}">{{ $registration->proof }}</button>
                                    </form>
                                </td>
                            @else
                                <td></td>
                            @endif
                        </tr>
                        <tr>
                            <th class='w-25'>Status</th>
                            @if ($registration->status_code == 'DR')
                                @php
                                    if ($registration->register_as == 'presenter') {
                                        $addtionalDesc = '. Please proceed to Submmision page to submit your paper.';
                                    } else {
                                        $addtionalDesc = '';
                                    }
                                @endphp
                                <td>{{ $registration->getStatusDescription() . strtoupper($registration->register_as) . $addtionalDesc }}
                                </td>
                            @else
                                <td>{{ $registration->getStatusDescription() }}</td>
                            @endif
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <div class="modal fade" id="createRegistrationModal" tabindex="-1" aria-labelledby="createRegistrationModalLabel"
        aria-hidden="true">
        <div class="modal-dialog modal-xl">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title fs-5" id="createRegistrationModalLabel">Registration</h4>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form action="{{ route('participant.competition.registration.create') }}" method="post"
                        id="createRegistration" enctype="multipart/form-data">
                        @csrf

                        <input type="hidden" name="form_id" value="{{ $registration->form->id }}">

                        <div class="mb-3">
                            <label for="code" class="form-label">Registration ID</label>
                            <input type="text" readonly class="form-control-plaintext" id="code" name="code"
                                value="{{ $registration->code }}">
                        </div>

                        <div class="mb-3">
                            <label for="register_as" class="form-label">Register As</label>
                            <select class="form-select {{ $errors->has('register_as') ? 'is-invalid' : '' }}"
                                id="register_as" name="register_as">
                                <option selected disabled value="">Choose Role</option>
                                <option @selected(old('register_as') == 'presenter') value="presenter">Presenter</option>
                                <option @selected(old('register_as') == 'participant') value="participant">Participant</option>
                            </select>
                            @error('register_as')
                                <div class="invalid-feedback">
                                    {{ $message }}
                                </div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label for="category_id" class="form-label">Fee Category</label>
                            <select class="form-select {{ $errors->has('category_id') ? 'is-invalid' : '' }}"
                                id="category_id" name="category_id">
                                <option selected disabled value="">Choose Category</option>
                                @foreach ($registration->form->categories as $category)
                                    <option @selected(old('category_id') == $category->id) value="{{ $category->id }}">{{ $category->name }}
                                    </option>
                                @endforeach
                            </select>
                            @error('category_id')
                                <div class="invalid-feedback">
                                    {{ $message }}
                                </div>
                            @enderror
                        </div>

                        <div class="mb-3" id="proofDiv">
                            <label for="proof" class="form-label">Proof</label>
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
                    <button type="submit" class="btn btn-primary" form="createRegistration">Save</button>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('scripts')
    <script>
        const feeCategoryInput = document.getElementById('category_id');
        const proofDiv = document.getElementById('proofDiv');
        checkNeedProofInput();

        feeCategoryInput.addEventListener('change', function() {
            checkNeedProofInput();
        });

        function checkNeedProofInput() {
            if (feeCategoryInput.value != '') {
                axios.get('/participant/competition/registration/category/' + feeCategoryInput.value)
                    .then(function(response) {
                        const category = response.data;

                        if (!category.needProof) {
                            proofDiv.style.display = 'none';
                        } else {
                            proofDiv.style.display = 'block';
                        }
                    })
                    .catch(function(error) {
                        apiError();
                    });
            }
        }

        @if ($errors->any())
            const createRegistrationModal = new bootstrap.Modal('#createRegistrationModal');
            createRegistrationModal.show();
        @endif
    </script>
@endsection
