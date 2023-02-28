@extends('participant.layouts.app')

@section('styles')
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" />
    <link rel="stylesheet"
        href="https://cdn.jsdelivr.net/npm/select2-bootstrap-5-theme@1.3.0/dist/select2-bootstrap-5-theme.min.css" />
@endsection

@section('content')
    <h3 class="text-dark mb-1">Registration - Registration Detail (ARAHE {{ $registration->form->session->year }})</h3>

    <div class="card">
        <div class="card-body">
            <div class="pt-3 pb-3 d-grid gap-2 d-md-flex justify-content-md-end">
                @if ($registration->status_code === 'NR' || $registration->status_code === 'WR')
                    <button type="button" class="btn btn-primary" data-bs-toggle="modal"
                        data-bs-target="#chooseLocalityModal">
                        Change Locality
                    </button>
                    <button type="button" class="btn btn-primary" data-bs-toggle="modal" id="registrationButton"
                        data-bs-target="#registrationModal" @disabled($registration->status_code === 'NR')>
                        {{ $registration->status_code === 'NR' ? 'Make New Registration' : 'Update Registration' }}
                    </button>
                @else
                @endif
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
                            <th class='w-25'>Locality</th>
                            <td>{{ $registration->category ? $registration->category->getLocality()->name : '' }}</td>
                        </tr>
                        <tr>
                            <th class='w-25'>Category</th>
                            <td>{{ $registration->category->name ?? '' }}</td>
                        </tr>
                        @if ($registration->proof)
                            <tr>
                                <th class='w-25'>Proof</th>
                                <td>
                                    <form action="{{ route('participant.competition.registration.download') }}"
                                        method="post" target="_blank">
                                        @csrf
                                        <input type="hidden" name="filename" value="{{ $registration->proof }}">
                                        <button type="submit" class="btn btn-link" name="registration_id"
                                            value="{{ $registration->id }}">{{ $registration->proof }}</button>
                                    </form>
                                </td>
                            </tr>
                        @endif
                        @if ($registration->link)
                            <tr>
                                <th class='w-25'>Participant Patner</th>
                                <td>
                                    {{ $registration->getLink()->name }}
                                </td>
                            </tr>
                        @endif
                        <tr>
                            <th class='w-25'>Dietary Preference</th>
                            <td>{{ $registration->getDietary()->name ?? '' }}</td>
                        </tr>
                        <tr>
                            <th class='w-25'>Status</th>
                            <td>{{ $registration->getStatusDescription() }}</td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <div class="modal fade" id="registrationModal" tabindex="-1" aria-labelledby="registrationModalLabel"
        aria-hidden="true">
        <div class="modal-dialog modal-xl">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title fs-5" id="registrationModalLabel">Registration</h4>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form
                        action="{{ $registration->status_code === 'NR' ? route('participant.competition.registration.create') : route('participant.competition.registration.update', ["id" => $registration->id ?? 0]) }}"
                        method="post" id="registrationForm" enctype="multipart/form-data">
                        @csrf

                        @if ($registration->status_code === 'NR')
                            <input type="hidden" name="form_id" value="{{ $registration->form->id }}">
                        @else
                            @method('PATCH')
                        @endif

                        <input type="hidden" name="locality" id="locality">

                        <div class="mb-3">
                            <label for="code" class="form-label">Registration ID</label>
                            <input type="text" readonly class="form-control-plaintext" id="code" name="code"
                                value="{{ $registration->code }}" @disabled($registration->status_code !== 'NR')>
                        </div>

                        <div class="mb-3">
                            <label for="register_as" class="form-label">Register As</label>
                            <select class="form-select {{ $errors->has('register_as') ? 'is-invalid' : '' }}"
                                id="register_as" name="register_as">
                                <option selected disabled value="">Choose Role</option>
                                <option @selected(old('register_as', $registration->register_as) == 'presenter') value="presenter">Presenter</option>
                                <option @selected(old('register_as', $registration->register_as) == 'participant') value="participant">Participant</option>
                            </select>
                            @error('register_as')
                                <div class="invalid-feedback">
                                    {{ $message }}
                                </div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label for="category" class="form-label">Category</label>
                            <select class="form-select {{ $errors->has('category') ? 'is-invalid' : '' }}" id="category"
                                name="category">
                            </select>
                            @error('category')
                                <div class="invalid-feedback">
                                    {{ $message }}
                                </div>
                            @enderror
                        </div>

                        <div class="mb-3" id="proofDiv" style="display: none">
                            <label for="proof" class="form-label">Proof <small>(Accepted format: PDF, JPEG, JPG,
                                    PNG)</small></label>
                            <input class="form-control {{ $errors->has('proof') ? 'is-invalid' : '' }}" type="file"
                                id="proof" name="proof">
                            @error('proof')
                                <div class="invalid-feedback">
                                    {{ $message }}
                                </div>
                            @enderror
                        </div>

                        <div class="mb-3" id="linkDiv" style="display: none">
                            <label for="link" class="form-label">Registration Patner</label>
                            <select class="form-select {{ $errors->has('link') ? 'is-invalid' : '' }}" id="link"
                                name="link">
                            </select>
                            @error('link')
                                <div class="invalid-feedback">
                                    {{ $message }}
                                </div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            @php
                                $dietaries = DB::table('dietary_preference')->get();
                            @endphp
                            <label for="dietary" class="form-label">Dietary Preference</label>
                            <select class="form-select {{ $errors->has('dietary') ? 'is-invalid' : '' }}" id="dietary"
                                name="dietary">
                                <option hidden>Choose Dietary Preference</option>
                                @foreach ($dietaries as $dietary)
                                    <option @selected(old('dietary', $registration->getDietary()->code ?? '') === $dietary->code) value="{{ $dietary->code }}">
                                        {{ $dietary->name }}</option>
                                @endforeach
                            </select>
                            @error('dietary')
                                <div class="invalid-feedback">
                                    {{ $message }}
                                </div>
                            @enderror
                        </div>

                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                    <button type="submit" class="btn btn-primary" form="registrationForm">Save</button>
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade" id="chooseLocalityModal" tabindex="-1" aria-labelledby="chooseLocalityModalLabel"
        aria-hidden="true" {!! $registration->status_code === 'NR' ? 'data-bs-backdrop="static" data-bs-keyboard="false"' : '' !!}>
        <div class="modal-dialog modal-xl modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title text-center" id="chooseLocalityModalLabel">Please Choose your Locality for
                        Registration</h4>
                </div>
                <div class="modal-body">
                    <form id="chooseLocality">

                        <div class="m-3 text-center">
                            @php
                                $localities = DB::table('locality')->get();
                            @endphp
                            @foreach ($localities as $index => $locality)
                                <div class="form-check form-check-inline">
                                    <input class="form-check-input" type="radio" name="locality_code"
                                        id="locality_code{{ $index }}" value="{{ $locality->code }}"
                                        @checked(old('locality', $registration->category ? $registration->category->getLocality()->code : '') === $locality->code)>
                                    <label class="form-check-label"
                                        for="locality_code{{ $index }}">{{ strtoupper($locality->name) }}</label>
                                </div>
                            @endforeach
                        </div>

                    </form>
                </div>
                <div class="modal-footer">
                    @if ($registration->status_code !== 'NR')
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                    @endif
                    <button type="submit" class="btn btn-primary" form="chooseLocality">Submit</button>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('scripts')
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
    <script>
        const feeCategoryInput = document.getElementById('category');
        const proofDiv = document.getElementById('proofDiv');
        const linkDiv = document.getElementById('linkDiv');

        const chooseLocalityModal = new bootstrap.Modal('#chooseLocalityModal');

        feeCategoryInput.addEventListener('change', function() {
            checkAdditionalInput();
        });

        function initSelect2() {
            $('#link').select2({
                dropdownParent: $('#registrationModal'),
                theme: 'bootstrap-5',
                placeholder: 'Choose Participant that register with you',
                ajax: {
                    transport: function(params, success, failure) {
                        return axios.post(params.url, params.data)
                            .then(success)
                            .catch(failure)
                    },
                    url: "/participant/competition/registration/participants",
                    dataType: 'json',
                    type: 'post',
                    delay: 250,
                    cache: true,
                    processResults: function(response) {
                        console.log(response.data.results)
                        return {
                            results: response.data.results,
                            pagination: response.data.pagination
                        };
                    },
                },
                minimumInputLength: 3,
            });

            @if ($errors->any() || old('link', $registration->getLink()->id ?? 0) !== 0)
                axios.post('/participant/competition/registration/participant', {
                    id: {{ old('link', $registration->getLink()->id ?? 0) }}
                }).then(function(response) {
                    const linkSelect = $('#link');
                    const data = response.data;

                    // create the option and append to Select2
                    var option = new Option(data.name, data.id, true, true);
                    linkSelect.append(option).trigger('change');

                    // manually trigger the `select2:select` event
                    linkSelect.trigger({
                        type: 'select2:select',
                        params: {
                            data: data
                        }
                    });
                })
            @endif
        }

        function checkAdditionalInput() {
            if (feeCategoryInput.value != '') {
                axios.get('/participant/competition/registration/category/' + feeCategoryInput.value)
                    .then(function(response) {
                        const category = response.data;

                        if (!category.needProof) {
                            proofDiv.style.display = 'none';
                        } else {
                            proofDiv.style.display = 'block';
                        }

                        if (!category.needLink) {
                            linkDiv.style.display = 'none';
                        } else {
                            linkDiv.style.display = 'block';
                            initSelect2();
                        }
                    })
                    .catch(function(error) {
                        apiError();
                    });
            }
        }

        $('#chooseLocality').on('submit', function(e) {
            e.preventDefault();

            checkCategory();

            document.getElementById('registrationButton').disabled = false;

            return false;
        });

        function checkCategory() {
            const locality_code = document.querySelector('input[name="locality_code"]:checked').value;
            const form_id = "{{ $registration->form->id }}";

            document.getElementById('locality').value = locality_code;

            const categorySelect = document.getElementById('category');

            axios.post('/participant/competition/registration/category', {
                    form_id: form_id,
                    locality_code: locality_code
                })
                .then(function(response) {
                    const categories = response.data;

                    categorySelect.length = 0;

                    const placeholder = new Option('Choose Category', '', true);
                    placeholder.hidden = true;

                    @if ($registration->category)
                        const selectedOptionValue = {{ old('category', $registration->category->id) }};
                    @else
                        const selectedOptionValue = 0;
                    @endif

                    categorySelect.add(placeholder);
                    categories.forEach(function(category) {
                        categorySelect.add(new Option(category.name, category.id, selectedOptionValue ===
                            category.id, selectedOptionValue === category.id));
                    });

                    @if ($registration->category || $errors->any())
                        checkAdditionalInput();
                    @endif

                    chooseLocalityModal.hide();
                })
                .catch(function(error) {
                    apiError();
                })
        }

        @if ($registration->status_code === 'NR' && !$errors->any())
            chooseLocalityModal.show();
        @else
            $(document).ready(function() {
                checkCategory();
            });
        @endif

        @if ($errors->any())
            const registrationModal = new bootstrap.Modal('#registrationModal');
            registrationModal.show();
        @endif
    </script>
@endsection
