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
                @if ($registration->status_code === 'NR' || $registration->status_code === 'WR' || $registration->status_code === 'UR')
                    <button type="button" class="btn btn-primary" data-bs-toggle="modal"
                        data-bs-target="#chooseLocalityModal">
                        Change Locality
                    </button>
                    <button type="button" class="btn btn-primary" data-bs-toggle="modal" id="registrationButton"
                        data-bs-target="#registrationModal" @disabled($registration->status_code === 'NR')>
                        {{ $registration->status_code === 'NR' ? 'Make New Registration' : 'Update Registration' }}
                    </button>
                @elseif ($registration->status_code === 'DR' || $registration->status_code === 'PR')
                    <button type="button" class="btn btn-primary" data-bs-toggle="modal"
                        data-bs-target="#choosePackageModal">
                        {{ $registration->status_code === 'DR' ? 'Choose Package' : 'Update Package' }}
                    </button>
                @endif
                @if ($registration->status_code === 'PR')
                    <button type="button" class="btn btn-success" data-bs-toggle="modal"
                        data-bs-target="#checkoutSessionModal">
                        Proceed to Payment
                    </button>
                @endif
            </div>
            <div class="pt-3">
                <hr>
                <h4 class="text-center">Registration Details</h4>
                <hr>
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
                            <td>{{ $registration->getType() ? $registration->getType()->name : '' }}</td>
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
                                    {{ $registration->linkParticipant->name }}
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

            @if (
                $registration->status_code === 'DR' ||
                    $registration->status_code === 'PR' ||
                    $registration->status_code === 'PW' ||
                    $registration->status_code === 'AR')
                <div class="pt-3">
                    <hr>
                    <h4 class="text-center">Package Details</h4>
                    <hr>
                </div>

                <div class="pt-3 pb-3 table-responsive">
                    <table class="table table-bordered align-middle text-center">
                        <thead class="table-primary">
                            <tr>
                                <th style="width: 50%">Package</th>
                                <th>Code</th>
                                <th style="width: 30%">Option</th>
                                <th style="width: 10%">Fee/Rate</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <th colspan="4">Main Package</th>
                            </tr>
                            <tr>
                                @php
                                    $currentChosenPackageFee = $registration->summary ? $registration->summary->getPackageFee() : null;
                                @endphp
                                <td>{!! $currentChosenPackageFee->parent->description ?? '' !!}</td>
                                <td>{{ $currentChosenPackageFee->parent->code ?? '' }}</td>
                                <td class="table-light"></td>
                                <td>{{ $registration->summary ? $registration->summary->getLocality()->currency : '' }}{{ $currentChosenPackageFee->amount ?? '' }}
                                </td>
                            </tr>
                            @php
                                $currentChosenExtraFees = $registration->summary ? $registration->summary->getExtraFees() : null;
                            @endphp
                            @if ($currentChosenExtraFees)
                                <tr>
                                    <th colspan="4">Extra Packages</th>
                                </tr>
                                @foreach ($currentChosenExtraFees as $extraFee)
                                    <tr>
                                        <td>{{ $extraFee->parent->description ?? '' }}</td>
                                        <td>{{ $extraFee->parent->code ?? '' }}</td>
                                        <td>{{ $extraFee->parent->options[$extraFee->optionIndex] ?? '' }}</td>
                                        <td>
                                            @if ($currentChosenPackageFee->parent->fullPackage)
                                                INCLUDED
                                            @else
                                                {{ $registration->summary ? $registration->summary->getLocality()->currency : '' }}{{ $extraFee->amount ?? '' }}
                                            @endif
                                        </td>
                                    </tr>
                                @endforeach
                            @endif
                            @if ($registration->summary && $registration->summary->hotel_id && $registration->summary->occupancy_id)
                                @php
                                    $hotelRate = $registration->summary->getHotelRate();

                                    $hotel = $hotelRate->hotel;
                                    $occupancy = $hotelRate->occupancy;
                                @endphp
                                <tr>
                                    <th colspan="4">Hotel Accomadation Package</th>
                                </tr>
                                <tr>
                                    <td>{{ $hotel->description }} ({{ $hotel->getDaysAndNights() }}: Check In:
                                        {{ $hotel->checkIn->format('d M') }} Check Out:
                                        {{ $hotel->checkOut->format('d M') }})</td>
                                    <td>{{ $hotel->code }}</td>
                                    <td class="table-light"></td>
                                    <td>{{ $registration->summary ? $registration->summary->getLocality()->currency : '' }}{{ $hotelRate->amount ?? '' }}
                                    </td>
                                </tr>
                            @endif
                            <tr>
                                <td colspan="3"><strong
                                        class="float-end">TOTAL{{ $registration->status_code === 'DR' || $registration->status_code === 'PR' ? ' NEED TO PAY' : ' PAID' }}</strong>
                                </td>
                                <td>{{ $registration->summary ? $registration->summary->getLocality()->currency : '' }}{{ $registration->summary ? $registration->summary->total : '' }}
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            @endif

        </div>
    </div>

    @if ($registration->status_code === 'NR' || $registration->status_code === 'WR')
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
                            action="{{ $registration->status_code === 'NR' ? route('participant.competition.registration.create') : route('participant.competition.registration.update', ['id' => $registration->id ?? 0]) }}"
                            method="post" id="registrationForm" enctype="multipart/form-data">
                            @csrf

                            @if ($registration->status_code === 'NR')
                                <input type="hidden" name="form_id" value="{{ $registration->form->id }}">
                            @else
                                @method('PATCH')
                            @endif

                            <div class="mb-3">
                                <label for="code" class="form-label">Registration ID</label>
                                <input type="text" readonly class="form-control-plaintext" id="code" name="code"
                                    value="{{ $registration->code }}" @disabled($registration->status_code !== 'NR')>
                            </div>

                            <div class="mb-3">
                                @php
                                    $typeList = DB::table('participant_type')->get();
                                @endphp
                                <label for="type" class="form-label">Register As</label>
                                <select class="form-select {{ $errors->has('type') ? 'is-invalid' : '' }}" id="type"
                                    name="type">
                                    <option hidden>Choose Role</option>
                                    @foreach ($typeList as $type)
                                        <option @selected(old('type', $registration->type) == $type->code) value="{{ $type->code }}">
                                            {{ $type->name }}</option>
                                    @endforeach
                                </select>
                                @error('type')
                                    <div class="invalid-feedback">
                                        {{ $message }}
                                    </div>
                                @enderror
                            </div>

                            <div class="mb-3">
                                <label for="category" class="form-label">Category</label>
                                <select class="form-select {{ $errors->has('category') ? 'is-invalid' : '' }}"
                                    id="category" name="category">
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
                                <input class="form-control {{ $errors->has('proof') ? 'is-invalid' : '' }}"
                                    type="file" id="proof" name="proof">
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
                                <select class="form-select {{ $errors->has('dietary') ? 'is-invalid' : '' }}"
                                    id="dietary" name="dietary">
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
                        <button type="reset" class="btn btn-secondary" form="registrationForm">Reset</button>
                        <button type="submit" class="btn btn-primary" form="registrationForm">Save</button>
                    </div>
                </div>
            </div>
        </div>
    @endif

    @if ($registration->status_code === 'NR' || $registration->status_code === 'WR')
        <div class="modal fade" id="chooseLocalityModal" tabindex="-1" aria-labelledby="chooseLocalityModalLabel"
            aria-hidden="true" {!! $registration->status_code === 'NR' ? 'data-bs-backdrop="static" data-bs-keyboard="false"' : '' !!}>
            <div class="modal-dialog modal-xl modal-dialog-centered">
                <div class="modal-content">
                    <div class="modal-header">
                        <h4 class="modal-title text-center" id="chooseLocalityModalLabel">Please Choose your Locality for
                            Registration</h4>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
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
                        <button type="reset" class="btn btn-secondary" form="chooseLocality">Reset</button>
                        <button type="submit" class="btn btn-primary" form="chooseLocality">Submit</button>
                    </div>
                </div>
            </div>
        </div>
    @endif

    @if ($registration->status_code === 'DR' || $registration->status_code === 'PR')
        <div class="modal fade" id="choosePackageModal" tabindex="-1" aria-labelledby="choosePackageModalLabel"
            aria-hidden="true">
            <div class="modal-dialog modal-xl modal-dialog-scrollable">
                <div class="modal-content">
                    <div class="modal-header">
                        <h1 class="modal-title fs-5" id="choosePackageModalLabel">Package Selection</h1>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <form
                            action="{{ $registration->status_code === 'DR' ? route('participant.competition.package.create') : route('participant.competition.package.update') }}"
                            method="post" id="choosePackageForm">
                            @csrf

                            @if ($registration->status_code === 'PR')
                                @method('PATCH')
                                <input type="hidden" name="summary_id" value="{{ $registration->summary->id }}">
                            @else
                                <input type="hidden" name="registration_id" value="{{ $registration->id }}">
                            @endif

                            @php
                                $locality_code = $registration->category->getLocality()->code;
                                $currentDuration = $registration->form->getDurationBasedCurrentDate($locality_code);

                                dd($currentDuration);

                                $currentChosenPackageFee = $registration->summary ? $registration->summary->getPackageFee() : null;
                            @endphp
                            <div class="mb-3 table-responsive">
                                <label for="package" class="form-label">Package List
                                    ({{ $registration->category->name }})</label>
                                <table class="table table-bordered align-middle" id="package">
                                    <thead class="table-primary align-middle text-center">
                                        <tr>
                                            <th style="width: 50%">Package</th>
                                            <th>Code</th>
                                            <th style="width: 20%">{{ $currentDuration->name }}
                                                <br>
                                                ({{ $currentDuration->getShortDateFormat('start') }} -
                                                {{ $currentDuration->getShortDateFormat('end') }})
                                            </th>
                                            <th>Choose Package</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($registration->category->packages as $index => $package)
                                            @php
                                                $currentFee = $package->fees->first(function ($fee) use ($currentDuration) {
                                                    return $currentDuration->id;
                                                });
                                            @endphp
                                            <tr {!! $package->fullPackage ? 'class="table-success"' : '' !!}>
                                                <td>{!! $package->description !!}</td>
                                                <td class="text-center">
                                                    {{ $package->code }}{!! $package->fullPackage ? '<br>(Full Package)' : '' !!}
                                                </td>
                                                <td class="text-center">
                                                    {{ DB::table('locality')->where('code', $locality_code)->first()->currency }}{{ $currentFee->amount }}
                                                </td>
                                                <td>
                                                    <div class="form-check d-flex justify-content-center">
                                                        <input class="form-check-input packageFee" type="radio"
                                                            value="{{ $currentFee->id }}" name="package[fee]"
                                                            id="package.fee.{{ $index }}"
                                                            @checked(old('package.fee', $currentChosenPackageFee->id ?? 0) == $currentFee->id)>
                                                    </div>
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                                @error('package.fee')
                                    <div class="invalid-feedback d-block">
                                        {{ $message }}
                                    </div>
                                @enderror
                            </div>

                            @php
                                $notFullPackageCodes = $registration->category->packages->where('fullPackage', false)->pluck('code');
                                $chosenExtraFees = $registration->summary ? $registration->summary->getExtraFees() : collect();
                            @endphp

                            <div class="mb-3 table-responsive">
                                <label for="extra" class="form-label">Extra List <small>( This option available for
                                        @foreach ($notFullPackageCodes as $index => $code)
                                            {{ $code }}
                                            @if ($notFullPackageCodes->keys()->last() !== $index)
                                                ,
                                            @endif
                                        @endforeach)
                                    </small> <small class="text-danger" id="warningExtra">Please choose Package
                                        FIRST</small></label>
                                <table class="table table-bordered align-middle text-center" id="extra">
                                    <thead class="table-primary align-middle">
                                        <tr>
                                            <th style="width: 30%">Package</th>
                                            <th>Code</th>
                                            <th style="width: 20%">{{ $currentDuration->name }}
                                                <br>
                                                ({{ $currentDuration->getShortDateFormat('start') }} -
                                                {{ $currentDuration->getShortDateFormat('end') }})
                                            </th>
                                            <th>Select Extra</th>
                                            <th style="width: 40%">Choose Option</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($registration->form->extras as $indexExtra => $extra)
                                            @php
                                                $currentFee = $extra->fees->firstWhere('duration_id', $currentDuration->id);

                                                $isChosenFee = $registration->summary
                                                    ? $chosenExtraFees->contains(function ($fee) use ($currentFee) {
                                                        return $fee->id == $currentFee->id;
                                                    })
                                                    : false;
                                            @endphp
                                            <tr>
                                                <td>{{ $extra->description }}</td>
                                                <td>
                                                    {{ $extra->code }}
                                                </td>
                                                <td>
                                                    {{ DB::table('locality')->where('code', $locality_code)->first()->currency }}{{ $currentFee->amount }}
                                                </td>
                                                <td>
                                                    <div class="form-check d-flex justify-content-center">
                                                        <input
                                                            class="form-check-input extraFee extraFee{{ $indexExtra }}"
                                                            type="checkbox" value="{{ $currentFee->id }}"
                                                            name="extra[{{ $indexExtra }}][fee]"
                                                            id="extra.{{ $indexExtra }}.fee"
                                                            @checked(
                                                                (old('extra.' . $indexExtra . '.fee') == $currentFee->id || $isChosenFee) &&
                                                                    !($currentChosenPackageFee->fullPackage ?? false))>
                                                    </div>
                                                    @error('extra.' . $indexExtra . '.fee')
                                                        <div class="invalid-feedback d-block">
                                                            {{ $message }}
                                                        </div>
                                                    @enderror
                                                </td>
                                                <td>
                                                    @forelse ($extra->options as $indexOption => $option)
                                                        <div class="form-check">
                                                            <input
                                                                class="form-check-input extraOption extraOption{{ $indexExtra }}"
                                                                type="radio" value="{{ $indexOption + 1 }}"
                                                                name="extra[{{ $indexExtra }}][option]"
                                                                id="extra.{{ $indexExtra }}.option.{{ $indexOption }}"
                                                                @checked(old(
                                                                        'extra.' . $indexExtra . '.option',
                                                                        isset($registration->summary->extras[$indexExtra]['option'])
                                                                            ? $registration->summary->extras[$indexExtra]['option'] + 1
                                                                            : 0) ==
                                                                        $indexOption + 1)>
                                                            <label class="form-check-label"
                                                                for="extra.{{ $indexExtra }}.option.{{ $indexOption }}">
                                                                {{ $option }}
                                                            </label>
                                                        </div>
                                                    @empty
                                                        <div class="d-flex justify-content-center noExtraOption">
                                                            No Option Needed to Choose
                                                        </div>
                                                    @endforelse
                                                    @error('extra.' . $indexExtra . '.option')
                                                        <div class="invalid-feedback d-block">
                                                            {{ $message }}
                                                        </div>
                                                    @enderror
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>

                            @php
                                $occupancies = $registration->form->getOccupanciesByLocality($locality_code);
                                $hotels = $registration->form->getHotelsByLocality($locality_code);

                                $currentChosenHotelRate = $registration->summary->hotel_id ?? 0 ? $registration->summary->getHotelRate() : null;
                            @endphp
                            <div class="mb-3 table-responsive">
                                <label for="hotel" class="form-label">Hotel Accommodation Packages <small>( This option
                                        available for
                                        @foreach ($notFullPackageCodes as $index => $code)
                                            {{ $code }}
                                            @if ($notFullPackageCodes->keys()->last() !== $index)
                                                ,
                                            @endif
                                        @endforeach)
                                    </small> <small class="text-danger" id="warningHotel">Please choose Package
                                        FIRST</small></label>
                                <table class="table table-bordered align-middle text-center" id="hotel">
                                    <thead class="table-primary align-middle">
                                        <tr>
                                            <th style="width: 50%">Package</th>
                                            <th>Code</th>
                                            @php
                                                $widthEachColumn = 40 / $occupancies->count();
                                            @endphp
                                            @foreach ($occupancies as $occupancy)
                                                <th style="width: {{ $widthEachColumn }}%">
                                                    Rate (BEFORE
                                                    {{ strtoupper($occupancy->bookBefore->format('F d, Y')) }})
                                                    {{ $occupancy->type }} ({{ $occupancy->number }}
                                                    {{ $occupancy->number > 1 ? 'Persons' : 'Person' }})
                                                </th>
                                            @endforeach
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($hotels as $index => $hotel)
                                            <tr>
                                                <td>{{ $hotel->description }} ({{ $hotel->getDaysAndNights() }}: Check In:
                                                    {{ $hotel->checkIn->format('d M') }} Check Out:
                                                    {{ $hotel->checkOut->format('d M') }})</td>
                                                <td>{{ $hotel->code }}</td>
                                                @foreach ($occupancies as $occupancy)
                                                    @php
                                                        $currentRate = DB::table('rates')
                                                            ->where('hotel_id', $hotel->id)
                                                            ->where('occupancy_id', $occupancy->id)
                                                            ->first();
                                                    @endphp
                                                    <td>
                                                        <div class="form-check">
                                                            <input class="form-check-input hotelRate" type="radio"
                                                                id="hotel.rate" name="hotel[rate]"
                                                                value="{{ $currentRate->id }}"
                                                                @checked(old('hotel.rate', $currentChosenHotelRate->id ?? 0) == $currentRate->id)>
                                                            <label class="form-check-label" for="hotel.rate">
                                                                {{ DB::table('locality')->where('code', $locality_code)->first()->currency }}{{ $currentRate->amount }}{{ $occupancy->number > 1 ? '/pax' : '' }}
                                                            </label>
                                                        </div>
                                                    </td>
                                                @endforeach
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                                @error('hotel.rate')
                                    <div class="invalid-feedback">
                                        {{ $message }}
                                    </div>
                                @enderror
                            </div>
                        </form>
                    </div>
                    <div class="modal-footer">
                        <button type="reset" class="btn btn-secondary" form="choosePackageForm">Reset</button>
                        <button type="submit" class="btn btn-primary" form="choosePackageForm">Save</button>
                    </div>
                </div>
            </div>
        </div>
    @endif

    @if ($registration->status_code === 'PR')
        <div class="modal fade" id="checkoutSessionModal" tabindex="-1" aria-labelledby="checkoutSessionModalLabel"
            aria-hidden="true">
            <div class="modal-dialog modal-xl modal-dialog-scrollable">
                <div class="modal-content">
                    <div class="modal-header">
                        <h1 class="modal-title fs-5" id="checkoutSessionModalLabel">Payment Confirmation</h1>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <form action="{{ route('participant.payment.pay.main') }}" method="post"
                            id="checkoutSessionForm">
                            @csrf

                            <input type="hidden" name="summary_id" value="{{ $registration->summary->id }}">

                            <div class="pt-3 pb-3 table-responsive">
                                <table class="table table-bordered align-middle text-center">
                                    <thead class="table-primary">
                                        <tr>
                                            <th style="width: 50%">Package</th>
                                            <th>Code</th>
                                            <th style="width: 30%">Option</th>
                                            <th style="width: 10%">Fee/Rate</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <tr>
                                            <th colspan="4">Main Package</th>
                                        </tr>
                                        <tr>
                                            @php
                                                $currentChosenPackageFee = $registration->summary->getPackageFee();
                                            @endphp
                                            <td>{!! $currentChosenPackageFee->parent->description ?? '' !!}</td>
                                            <td>{{ $currentChosenPackageFee->parent->code ?? '' }}</td>
                                            <td class="table-light"></td>
                                            <td>{{ $registration->summary->getLocality()->currency }}{{ $currentChosenPackageFee->amount ?? '' }}
                                            </td>
                                            <input type="hidden" name="price_id[]"
                                                value="{{ $currentChosenPackageFee->price_id }}">
                                        </tr>
                                        @php
                                            $currentChosenExtraFees = $registration->summary->getExtraFees();
                                        @endphp
                                        @if ($currentChosenExtraFees)
                                            <tr>
                                                <th colspan="4">Extra Packages</th>
                                            </tr>
                                            @foreach ($currentChosenExtraFees as $extraFee)
                                                <tr>
                                                    <td>{{ $extraFee->parent->description ?? '' }}</td>
                                                    <td>{{ $extraFee->parent->code ?? '' }}</td>
                                                    <td>{{ $extraFee->parent->options[$extraFee->optionIndex] ?? '' }}
                                                    </td>
                                                    <td>
                                                        @if ($currentChosenPackageFee->parent->fullPackage)
                                                            INCLUDED
                                                        @else
                                                            {{ $registration->summary ? $registration->summary->getLocality()->currency : '' }}{{ $extraFee->amount ?? '' }}
                                                        @endif
                                                    </td>
                                                    @if (!$currentChosenPackageFee->parent->fullPackage)
                                                        <input type="hidden" name="price_id[]"
                                                            value="{{ $extraFee->price_id }}">
                                                    @endif
                                                </tr>
                                            @endforeach
                                        @endif
                                        @if ($registration->summary && $registration->summary->hotel_id && $registration->summary->occupancy_id)
                                            @php
                                                $hotelRate = $registration->summary->getHotelRate();

                                                $hotel = $hotelRate->hotel;
                                                $occupancy = $hotelRate->occupancy;
                                            @endphp
                                            <tr>
                                                <th colspan="4">Hotel Accomadation Package</th>
                                            </tr>
                                            <tr>
                                                <td>{{ $hotel->description }} ({{ $hotel->getDaysAndNights() }}:
                                                    Check In:
                                                    {{ $hotel->checkIn->format('d M') }} Check Out:
                                                    {{ $hotel->checkOut->format('d M') }})</td>
                                                <td>{{ $hotel->code }}</td>
                                                <td class="table-light"></td>
                                                <td>{{ $registration->summary->getLocality()->currency }}{{ $hotelRate->amount ?? '' }}
                                                </td>
                                            </tr>
                                        @endif
                                        <tr>
                                            <td colspan="3"><strong class="float-end">TOTAL NEED TO PAY</strong>
                                            </td>
                                            <td>{{ $registration->summary->getLocality()->currency }}{{ $registration->summary->total }}
                                            </td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>

                        </form>
                    </div>
                    <div class="modal-footer">
                        <button type="button" id="checkoutButton" class="btn btn-primary">Checkout</button>
                    </div>
                </div>
            </div>
        </div>
    @endif
@endsection

@section('scripts')
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>

    @if ($registration->status_code === 'NR' || $registration->status_code === 'WR')
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

                @if ($errors->any() || old('link', $registration->linkParticipant->id ?? 0) !== 0)
                    axios.post('/participant/competition/registration/participant', {
                        id: {{ old('link', $registration->linkParticipant->id ?? 0) }}
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
    @endif

    @if ($registration->status_code === 'DR' || $registration->status_code === 'PR')
        <script>
            $(document).ready(function() {
                checkPackage($("input:radio.packageFee:checked").val());
                checkOption();
            });

            const extraCount = {!! $registration->form->extras->count() !!};

            $("input:radio.packageFee").change(function() {
                checkPackage(this.value)
            });

            function checkPackage(value) {
                if (value) {
                    axios.post('/participant/competition/package/fee', {
                            fee: value
                        })
                        .then(function(response) {
                            $('#warningExtra').css('display', 'none');
                            $('#warningHotel').css('display', 'none');

                            const category = response.data.category;

                            if (category.fullPackage) {
                                $('#warningExtra').html(
                                    "This Packages has been INCLUDED. Please choose an option that available from each package."
                                );
                                $('#warningExtra').css('display', 'inline');

                                $('#warningHotel').html("The Hotel Accommodation has been INCLUDED");
                                $('#warningHotel').css('display', 'inline');

                                if ($('input:checkbox.extraFee').length) {
                                    $("input:checkbox.extraFee").prop('checked', false);

                                    $("input:checkbox.extraFee").attr("disabled", true);
                                }

                                checkOption(category.fullPackage);

                                $('input:radio.extraOption').length ? $("input:radio.extraOption").prop('disabled', false) :
                                    null;

                                if ($('input:radio.hotelRate').length) {
                                    $("input:radio.hotelRate").prop('checked', false);

                                    $("input:radio.hotelRate").attr("disabled", true);
                                }
                                $('.noExtraOption').css('opacity', '0.5');
                            } else {

                                for (let i = 0; i < extraCount; i++) {
                                    if ($('input:checkbox.extraFee' + i).length && !$("input:checkbox.extraFee" + i).is(
                                            ":checked")) {
                                        $('input:checkbox.extraFee' + i).prop('checked', false);

                                        $('input:checkbox.extraFee' + i).attr("disabled", false);
                                    }
                                }

                                checkOption(category.fullPackage);

                                $('input:radio.hotelRate').length ? $("input:radio.hotelRate").attr("disabled",
                                    false) : null;

                                $('.noExtraOption').css('opacity', '1');
                            }
                        })
                        .catch(function(error) {
                            apiError();
                        });
                } else {
                    $('input:checkbox.extraFee').length ? $("input:checkbox.extraFee").attr("disabled",
                        true) : null;
                    checkOption();
                    $('input:radio.hotelRate').length ? $("input:radio.hotelRate").attr("disabled",
                        true) : null;
                    $('.noExtraOption').css('opacity', '0.5');
                }
            }

            function uncheckExtraPackageOptionsRadioButton() {
                $('input:radio.extraOption').length ? $("input:radio.extraOption").prop('checked', false) : null;
            }

            function checkOption(fullPackage = false) {
                for (let i = 0; i < extraCount; i++) {
                    checkExtraOptionRadioButtonPerExtra(i, fullPackage)

                    $("input:checkbox.extraFee" + i).change(function() {
                        checkExtraOptionRadioButtonPerExtra(i, fullPackage)
                    });
                }
            }

            function checkExtraOptionRadioButtonPerExtra(i, fullPackage) {
                if (!$("input:checkbox.extraFee" + i).is(":checked") && $('input:radio.extraOption' + i).length) {
                    fullPackage ? null : $("input:radio.extraOption" + i).prop('checked', false)
                    $("input:radio.extraOption" + i).prop('disabled', true)
                } else {
                    $("input:radio.extraOption" + i).prop('disabled', false)
                }
            }

            @if ($errors->any())
                const choosePackageModal = new bootstrap.Modal('#choosePackageModal');
                choosePackageModal.show();
            @endif
        </script>
    @endif

    @if ($registration->status_code === 'PR')
        <script>
            const checkoutButtonEl = document.getElementById('checkoutButton');
            const checkoutSessionForm = document.getElementById('checkoutSessionForm');
            const checkoutSessionModal = new bootstrap.Modal('#checkoutSessionModal');

            checkoutButtonEl.addEventListener('click', function(e) {
                e.preventDefault();

                Swal.fire({
                    icon: 'warning',
                    title: 'Do you confirm to proceed?',
                    text: 'The package that have been chosen will be locked and cannot be change after the payment',
                    showDenyButton: true,
                    confirmButtonText: 'Proceed',
                    denyButtonText: 'Cancel',
                }).then((result) => {
                    if (result.isConfirmed) {
                        checkoutSessionForm.submit();
                    } else if (result.isDenied) {
                        checkoutSessionModal.hide();
                    }
                })
            });
        </script>
    @endif
@endsection
