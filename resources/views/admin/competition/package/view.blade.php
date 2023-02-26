@extends('admin.layouts.app')

@section('styles')
    <style>
        .form-switch.form-switch-lg .form-check-input {
            height: 2rem;
            width: calc(3rem + 0.75rem);
            border-radius: 4rem;
        }
    </style>
@endsection

@php
    $durationsLocal = $form->durations->where('locality', 'L');
    $durationsInternational = $form->durations->where('locality', 'I');

    $durationsInternationalWihtoutOnsite = $durationsInternational->reject(function ($duration) {
        return $duration->name === 'On-site';
    });
    $durationsLocalWihtoutOnsite = $durationsLocal->reject(function ($duration) {
        return $duration->name === 'On-site';
    });

    $OnSiteDurationInternational = $durationsInternational->first(function ($duration) {
        return $duration->name === 'On-site';
    });
    $OnSiteDurationLocal = $durationsLocal->first(function ($duration) {
        return $duration->name === 'On-site';
    });

    $categoriesLocal = $form->categories->where('locality', 'L');
    $packagesLocal = DB::table('packages')
        ->whereIn('category_id', $categoriesLocal->pluck('id'))
        ->get();

    $categoriesInternational = $form->categories->where('locality', 'I');
    $packagesInternational = DB::table('packages')
        ->whereIn('category_id', $categoriesInternational->pluck('id'))
        ->get();

    $hotelsInternational = $form->hotels->where('locality', 'I');
    $hotelsLocal = $form->hotels->where('locality', 'L');

    $occupanciesInternational = $form->occupancies->where('locality', 'I');
    $occupanciesLocal = $form->occupancies->where('locality', 'L');
@endphp

@section('content')
    <h3 class="text-dark mb-1">Form Management - Package Detail</h3>

    <div class="card">
        <h4 class="card-header text-center">Durations and Categories Detail</h4>
        <div class="card-body">
            <div class="row pt-3 pb-3">
                <div class="col-12">
                    <button type="button" class="btn btn-primary float-end" data-bs-toggle="modal"
                        data-bs-target="#updateFormModal">
                        Update Durations and Categories
                    </button>
                </div>
            </div>
            <div class="table-responsive">
                <table class="table table-bordered">
                    <tbody>
                        <tr>
                            <th class='w-25'>Year</th>
                            <td class="text-center" colspan="4">{{ $form->session->year }}</td>
                        </tr>
                        @if ($form->categories->count())
                            @php
                                $donePrintCategoryTH = false;
                            @endphp
                            @forelse ($categoriesInternational->values() as $index => $category)
                                <tr>
                                    @if (!$donePrintCategoryTH)
                                        <th rowspan="{{ $form->categories->count() }}">Categories</th>
                                        @php
                                            $donePrintCategoryTH = true;
                                        @endphp
                                    @endif
                                    @if ($index == 0)
                                        <th rowspan="{{ $categoriesInternational->count() }}">International
                                        </th>
                                    @endif
                                    <td class="text-center" colspan="2">{{ $category->name }} ({{ $category->code }})
                                    </td>
                                    <td class="text-center">
                                        {{ $category->needProof ? 'Need Proof' : 'No Need Proof' }}</td>
                                </tr>
                            @empty
                                <tr>
                                    @if (!$donePrintCategoryTH)
                                        <th rowspan="{{ $form->categories->count() }}">Categories</th>
                                        @php
                                            $donePrintCategoryTH = true;
                                        @endphp
                                    @endif
                                    <th>International</th>
                                    <td class="text-center" colspan="3">No Category Added For International</td>
                                </tr>
                            @endforelse
                            @forelse ($categoriesLocal->values() as $index => $category)
                                <tr>
                                    @if (!$donePrintCategoryTH)
                                        <th rowspan="{{ $form->categories->count() }}">Categories</th>
                                        @php
                                            $donePrintCategoryTH = true;
                                        @endphp
                                    @endif
                                    @if ($index == 0)
                                        <th rowspan="{{ $categoriesLocal->count() }}">Local
                                        </th>
                                    @endif
                                    <td class="text-center" colspan="2">{{ $category->name }} ({{ $category->code }})
                                    </td>
                                    <td class="text-center">
                                        {{ $category->needProof ? 'Need Proof' : 'No Need Proof' }}</td>
                                </tr>
                            @empty
                                <tr>
                                    @if (!$donePrintCategoryTH)
                                        <th rowspan="{{ $form->categories->count() }}">Categories</th>
                                        @php
                                            $donePrintCategoryTH = true;
                                        @endphp
                                    @endif
                                    <th>Local</th>
                                    <td class="text-center" colspan="3">No Category Added For Local</td>
                                </tr>
                            @endforelse
                        @else
                            <tr>
                                <th>Categories</th>
                                <td colspan="4">No Category Added Yet</td>
                            </tr>
                        @endif

                        @if ($form->durations->count())
                            @php
                                $donePrintDurationTH = false;
                            @endphp
                            @forelse ($durationsInternationalWihtoutOnsite->values() as $index => $duration)
                                <tr>
                                    @if (!$donePrintDurationTH)
                                        <th rowspan="{{ $form->durations->count() - 2 }}">Durations</th>
                                        @php
                                            $donePrintDurationTH = true;
                                        @endphp
                                    @endif
                                    @if ($index == 0)
                                        <th rowspan="{{ $durationsInternationalWihtoutOnsite->count() }}">International
                                        </th>
                                    @endif
                                    <td class="text-center">{{ $duration->name }}</td>
                                    <td class="text-center" colspan="2">
                                        {{ $duration->start->translatedFormat('j F Y') }} -
                                        {{ $duration->end->translatedFormat('j F Y') }}</td>
                                </tr>
                            @empty
                                <tr>
                                    @if (!$donePrintDurationTH)
                                        <th rowspan="{{ $form->durations->count() }}">Durations</th>
                                        @php
                                            $donePrintDurationTH = true;
                                        @endphp
                                    @endif
                                    <th>International</th>
                                    <td class="text-center" colspan="3">No Duration Added For International</td>
                                </tr>
                            @endforelse
                            @forelse ($durationsLocalWihtoutOnsite->values() as $index => $duration)
                                <tr>
                                    @if (!$donePrintDurationTH)
                                        <th rowspan="{{ $form->durations->count() }}">Durations</th>
                                        @php
                                            $donePrintDurationTH = true;
                                        @endphp
                                    @endif
                                    @if ($index == 0)
                                        <th rowspan="{{ $durationsLocalWihtoutOnsite->count() }}">Local
                                        </th>
                                    @endif
                                    <td class="text-center">{{ $duration->name }}</td>
                                    <td class="text-center" colspan="2">
                                        {{ $duration->start->translatedFormat('j F Y') }} -
                                        {{ $duration->end->translatedFormat('j F Y') }}</td>
                                </tr>
                            @empty
                                <tr>
                                    @if (!$donePrintDurationTH)
                                        <th rowspan="{{ $form->durations->count() }}">Durations</th>
                                        @php
                                            $donePrintDurationTH = true;
                                        @endphp
                                    @endif
                                    <th>Local</th>
                                    <td class="text-center" colspan="3">No Duration Added For Local</td>
                                </tr>
                            @endforelse
                        @else
                            <tr>
                                <th>Durations</th>
                                <td colspan="4">No Duration Added Yet</td>
                            </tr>
                        @endif
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <div class="pt-3 pb-3"></div>

    <div class="card">
        <h4 class="card-header text-center">Package Structure</h4>
        <div class="card-body">
            <div class="row pt-3 pb-3">
                <div class="col-12">
                    <button type="button" class="btn btn-primary float-end" data-bs-toggle="modal"
                        data-bs-target="#updatePackageModal" @disabled(!$form->categories->count())>
                        Modify Package Stucture
                    </button>
                </div>
            </div>

            @if ($form->categories->count())
                <div class="table-responsive">
                    <table class="table table-bordered text-center align-middle">
                        <tbody>
                            @php
                                $notYetPrint = true;
                            @endphp
                            @foreach ($categoriesInternational->values() as $indexCat => $category)
                                @foreach ($category->packages as $indexPack => $package)
                                    <tr {!! $package->fullPackage ? 'class="table-success"' : '' !!}>
                                        @if ($notYetPrint)
                                            <th rowspan="{{ $packagesInternational->count() }}">International</th>
                                            @php
                                                $notYetPrint = false;
                                            @endphp
                                        @endif
                                        @if ($indexPack == 0)
                                            <th rowspan="{{ $category->packages->count() }}">{{ $category->name }}
                                                ({{ $category->code }})
                                            </th>
                                        @endif
                                        <td class="w-50">{{ $package->description }}</td>
                                        <td class="text-center">{{ $package->code }}
                                            {{ $package->fullPackage ? '(Full Package)' : '' }}</td>
                                        </td>
                                    </tr>
                                @endforeach
                            @endforeach
                            @php
                                $notYetPrint = true;
                            @endphp
                            @foreach ($categoriesLocal->values() as $indexCat => $category)
                                @foreach ($category->packages as $indexPack => $package)
                                    <tr {!! $package->fullPackage ? 'class="table-success"' : '' !!}>
                                        @if ($notYetPrint)
                                            <th rowspan="{{ $packagesLocal->count() }}">Local</th>
                                            @php
                                                $notYetPrint = false;
                                            @endphp
                                        @endif
                                        @if ($indexPack == 0)
                                            <th rowspan="{{ $category->packages->count() }}">{{ $category->name }}
                                                ({{ $category->code }})
                                            </th>
                                        @endif
                                        <td class="w-50">{{ $package->description }}</td>
                                        <td class="text-center">{{ $package->code }}
                                            {{ $package->fullPackage ? '(Full Package)' : '' }}</td>
                                        </td>
                                    </tr>
                                @endforeach
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @else
                <div class="display-6 text-center">
                    <p>Please add <strong>Category</strong> first</p>
                </div>
            @endif

        </div>
    </div>

    <div class="pt-3 pb-3"></div>

    <div class="card">
        <h4 class="card-header text-center">Fee Structure</h4>
        <div class="card-body">
            <div class="row pt-3 pb-3">
                <div class="col-12">
                    <button type="button" class="btn btn-primary float-end" data-bs-toggle="modal"
                        data-bs-target="#modifyFeeModal" @disabled(!$form->durations->count() || !$form->getPackages()->count())>
                        Modify Fee Stucture
                    </button>
                </div>
            </div>

            @if ($form->durations->count() && $form->getPackages()->count())
                <div class="table-responsive">
                    <table class="table table-bordered text-center">
                        <thead>
                            <tr>
                                <th colspan="{{ $durationsInternational->count() + 2 }}" class="table-primary">
                                    International</th>
                            </tr>
                            <tr>
                                <th style="width: 30%"></th>
                                <th style="width: 10%">Code</th>
                                @php
                                    $widthEachColumnInternational = 60 / $durationsInternational->count();
                                @endphp
                                @foreach ($durationsInternationalWihtoutOnsite as $duration)
                                    <th style="width: {{ $widthEachColumnInternational }}%">
                                        {{ $duration->name }}
                                        <br>
                                        ({{ $duration->start->translatedFormat('j/m/Y') }} -
                                        {{ $duration->end->translatedFormat('j/m/Y') }})
                                    </th>
                                @endforeach
                                <th style="width: {{ $widthEachColumnInternational }}%">
                                    On-site
                                </th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($categoriesInternational as $category)
                                <tr>
                                    <th colspan="{{ $durationsInternational->count() + 2 }}">{{ $category->name }}
                                        ({{ $category->code }})
                                    </th>
                                </tr>
                                @foreach ($category->packages as $package)
                                    <tr {!! $package->fullPackage ? 'class="table-success"' : '' !!}>
                                        <td>{!! $package->description !!}</td>
                                        <td>{{ $package->code }} {{ $package->fullPackage ? '(Full Package)' : '' }}</td>
                                        @foreach ($durationsInternationalWihtoutOnsite as $duration)
                                            <td>
                                                USD${{ DB::table('fees')->where('parent_type', 'App\Models\Package')->where('parent_id', $package->id)->where('duration_id', $duration->id)->first()->amount ?? 0 }}
                                            </td>
                                        @endforeach
                                        <td>
                                            USD${{ DB::table('fees')->where('parent_type', 'App\Models\Package')->where('parent_id', $package->id)->where('duration_id', $OnSiteDurationInternational->id)->first()->amount ?? 0 }}
                                        </td>
                                    </tr>
                                @endforeach
                            @endforeach
                        </tbody>
                    </table>
                    <table class="table table-bordered text-center">
                        <thead>
                            <tr>
                                <th colspan="{{ $durationsLocal->count() + 2 }}" class="table-primary">
                                    Local</th>
                            </tr>
                            <tr>
                                <th style="width: 30%"></th>
                                <th style="width: 10%">Code</th>
                                @php
                                    $widthEachColumnLocal = 60 / $durationsLocal->count();
                                @endphp
                                @foreach ($durationsLocalWihtoutOnsite as $duration)
                                    <th style="width: {{ $widthEachColumnLocal }}%">
                                        {{ $duration->name }}
                                        <br>
                                        ({{ $duration->start->translatedFormat('j/m/Y') }} -
                                        {{ $duration->end->translatedFormat('j/m/Y') }})
                                    </th>
                                @endforeach
                                <th style="width: {{ $widthEachColumnLocal }}%">
                                    On-site
                                </th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($categoriesLocal as $category)
                                <tr>
                                    <th colspan="{{ $durationsLocal->count() + 2 }}">{{ $category->name }}
                                        ({{ $category->code }})
                                    </th>
                                </tr>
                                @foreach ($category->packages as $package)
                                    <tr {!! $package->fullPackage ? 'class="table-success"' : '' !!}>
                                        <td>{!! $package->description !!}</td>
                                        <td>{{ $package->code }} {{ $package->fullPackage ? '(Full Package)' : '' }}</td>
                                        @foreach ($durationsLocalWihtoutOnsite as $duration)
                                            <td>
                                                RM{{ DB::table('fees')->where('parent_type', 'App\Models\Package')->where('parent_id', $package->id)->where('duration_id', $duration->id)->first()->amount ?? 0 }}
                                            </td>
                                        @endforeach
                                        <td>
                                            RM{{ DB::table('fees')->where('parent_type', 'App\Models\Package')->where('parent_id', $package->id)->where('duration_id', $OnSiteDurationLocal->id)->first()->amount ?? 0 }}
                                        </td>
                                    </tr>
                                @endforeach
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @elseif ($form->durations->count())
                <div class="display-6 text-center">
                    <p>Please add <strong>Package</strong> to generate Fee Structure</p>
                </div>
            @elseif ($form->getPackages()->count())
                <div class="display-6 text-center">
                    <p>Please add <strong>Duration</strong> to generate Fee Structure</p>
                </div>
            @else
                <div class="display-6 text-center">
                    <p>Please add <strong>Duration</strong> and <strong>Package</strong> first</p>
                </div>
            @endif

        </div>
    </div>

    <div class="pt-3 pb-3"></div>

    <div class="card">
        <h4 class="card-header text-center">Extra List</h4>
        <div class="card-body">
            <div class="row pt-3 pb-3">
                <div class="col-6">
                    <button type="button" class="btn btn-success" data-bs-toggle="modal"
                        data-bs-target="#addExtraModal">
                        Add Extra
                    </button>
                </div>
                <div class="col-6">
                    <button type="submit" form="deleteExtra" class="btn btn-danger float-end">
                        Delete Extra
                    </button>
                </div>
            </div>
            <form action="{{ route('admin.competition.extra.delete') }}" method="post" id="deleteExtra">
                @csrf
                @method('DELETE')

                <input type="hidden" name="form_id" value="{{ $form->id }}">

                <div class="table-responsive">
                    <table class="table table-bordered" id="tableExtra">
                        <thead class="table-primary">
                            <tr>
                                <th style="width: 5%">
                                    <div class="checkbox"><input type="checkbox" id="checkAll"></div>
                                </th>
                                <th>Description</th>
                                <th style="width: 10%">Code</th>
                                <th style="width: 20%">Date</th>
                                <th style="width: 30%">Options</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($form->extras as $extra)
                                <tr>
                                    <td>
                                        <div class="checkbox"><input class="checkboxTick" type="checkbox"
                                                name="extra_id[]" value="{{ $extra->id }}"></div>
                                    </td>
                                    <td><a
                                            href="{{ route('admin.competition.extra.view', ['id' => $extra->id]) }}">{{ $extra->description }}</a>
                                    </td>
                                    <td>{{ $extra->code }}</td>
                                    <td>{{ $extra->date->format('j F Y') }}</td>
                                    <td>
                                        @if ($extra->options->count())
                                            <ul class="list-group">
                                                @foreach ($extra->options as $option)
                                                    <li class="list-group-item">{{ $option }}</li>
                                                @endforeach
                                            </ul>
                                        @else
                                            No Option Added
                                        @endif
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </form>
        </div>
    </div>

    <div class="pt-3 pb-3"></div>

    <div class="card">
        <h4 class="card-header text-center">Extra Fee Structure</h4>
        <div class="card-body">
            <div class="row pt-3 pb-3">
                <div class="col-12">
                    <button type="button" class="btn btn-primary float-end" data-bs-toggle="modal"
                        data-bs-target="#modifyExtraFeeModal" @disabled(!$form->durations->count() || !$form->extras->count())>
                        Modify Extra Fee Stucture
                    </button>
                </div>
            </div>

            @if ($form->durations->count() && $form->extras->count())
                <div class="table-responsive">
                    <table class="table table-bordered text-center">
                        <thead>
                            <tr>
                                <th colspan="{{ $durationsInternational->count() + 2 }}" class="table-primary">
                                    International</th>
                            </tr>
                            <tr>
                                <th style="width: 30%"></th>
                                <th style="width: 10%">Code</th>
                                @php
                                    $widthEachColumnInternational = 60 / $durationsInternational->count();
                                @endphp
                                @foreach ($durationsInternationalWihtoutOnsite as $duration)
                                    <th style="width: {{ $widthEachColumnInternational }}%">
                                        {{ $duration->name }}
                                        <br>
                                        ({{ $duration->start->translatedFormat('j/m/Y') }} -
                                        {{ $duration->end->translatedFormat('j/m/Y') }})
                                    </th>
                                @endforeach
                                <th style="width: {{ $widthEachColumnInternational }}%">
                                    On-site
                                </th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($form->extras as $extra)
                                <tr>
                                    <td>{{ $extra->description }} ({{ $extra->date->format('d M') }})</td>
                                    <td>{{ $extra->code }}</td>
                                    @foreach ($durationsInternationalWihtoutOnsite as $duration)
                                        <td>
                                            USD${{ DB::table('fees')->where('parent_type', 'App\Models\Extra')->where('parent_id', $extra->id)->where('duration_id', $duration->id)->first()->amount ?? 0 }}
                                        </td>
                                    @endforeach
                                    <td>
                                        USD${{ DB::table('fees')->where('parent_type', 'App\Models\Extra')->where('parent_id', $extra->id)->where('duration_id', $OnSiteDurationInternational->id)->first()->amount ?? 0 }}
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                    <table class="table table-bordered text-center">
                        <thead>
                            <tr>
                                <th colspan="{{ $durationsLocal->count() + 2 }}" class="table-primary">
                                    Local</th>
                            </tr>
                            <tr>
                                <th style="width: 30%"></th>
                                <th style="width: 10%">Code</th>
                                @php
                                    $widthEachColumnLocal = 60 / $durationsLocal->count();
                                @endphp
                                @foreach ($durationsLocalWihtoutOnsite as $duration)
                                    <th style="width: {{ $widthEachColumnLocal }}%">
                                        {{ $duration->name }}
                                        <br>
                                        ({{ $duration->start->translatedFormat('j/m/Y') }} -
                                        {{ $duration->end->translatedFormat('j/m/Y') }})
                                    </th>
                                @endforeach
                                <th style="width: {{ $widthEachColumnLocal }}%">
                                    On-site
                                </th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($form->extras as $extra)
                                <tr>
                                    <td>{{ $extra->description }} ({{ $extra->date->format('d M') }})</td>
                                    <td>{{ $extra->code }}</td>
                                    @foreach ($durationsLocalWihtoutOnsite as $duration)
                                        <td>
                                            RM{{ DB::table('fees')->where('parent_type', 'App\Models\Extra')->where('parent_id', $extra->id)->where('duration_id', $duration->id)->first()->amount ?? 0 }}
                                        </td>
                                    @endforeach
                                    <td>
                                        RM{{ DB::table('fees')->where('parent_type', 'App\Models\Extra')->where('parent_id', $extra->id)->where('duration_id', $OnSiteDurationLocal->id)->first()->amount ?? 0 }}
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @elseif ($form->durations->count())
                <div class="display-6 text-center">
                    <p>Please add <strong>Extra</strong> to generate Extra Fee Structure</p>
                </div>
            @elseif ($form->extras->count())
                <div class="display-6 text-center">
                    <p>Please add <strong>Duration</strong> to generate Extra Fee Structure</p>
                </div>
            @else
                <div class="display-6 text-center">
                    <p>Please add <strong>Duration</strong> and <strong>Extra</strong> first</p>
                </div>
            @endif

        </div>
    </div>

    <div class="pt-3 pb-3"></div>

    <div class="card">
        <h4 class="card-header text-center">Hotel and Occupancy</h4>
        <div class="card-body">
            <div class="row pt-3 pb-3">
                <div class="col-12">
                    <button type="button" class="btn btn-primary float-end" data-bs-toggle="modal"
                        data-bs-target="#modifyHotelModal">
                        Modify Hotel and Occupancy
                    </button>
                </div>
            </div>

            <div class="row">
                <div class="col-6 table-responsive">
                    <table class="table table-bordered text-center">
                        <thead>
                            <tr class="table-primary">
                                <th colspan="5">Hotel Accomodation List</th>
                            </tr>
                            <tr>
                                <th style="width: 15%">Locality</th>
                                <th>Description</th>
                                <th style="width: 5%">Code</th>
                                <th style="width: 15%">Check In</th>
                                <th style="width: 15%">Check Out</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($hotelsInternational->values() as $index => $hotel)
                                <tr>
                                    @if ($index == 0)
                                        <td rowspan="{{ $hotelsInternational->count() }}">International</td>
                                    @endif
                                    <td>{{ $hotel->description }} ({{ $hotel->getDaysAndNights() }})</td>
                                    <td>{{ $hotel->code }}</td>
                                    <td>{{ $hotel->checkIn->format('d M') }}</td>
                                    <td>{{ $hotel->checkOut->format('d M') }}</td>
                                </tr>
                            @endforeach
                            @foreach ($hotelsLocal->values() as $index => $hotel)
                                <tr>
                                    @if ($index == 0)
                                        <td rowspan="{{ $hotelsLocal->count() }}">Local</td>
                                    @endif
                                    <td>{{ $hotel->description }} ({{ $hotel->getDaysAndNights() }})</td>
                                    <td>{{ $hotel->code }}</td>
                                    <td>{{ $hotel->checkIn->format('d M') }}</td>
                                    <td>{{ $hotel->checkOut->format('d M') }}</td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
                <div class="col-6 table-responsive">
                    <table class="table table-bordered text-center">
                        <thead>
                            <tr class="table-primary">
                                <th colspan="4">Occupancy List</th>
                            </tr>
                            <tr>
                                <th style="width: 15%">Locality</th>
                                <th>Room Type</th>
                                <th style="width: 20%">Number of Person</th>
                                <th style="width: 15%">Book Before</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($occupanciesInternational->values() as $index => $occupancy)
                                <tr>
                                    @if ($index == 0)
                                        <td rowspan="{{ $occupanciesInternational->count() }}">International</td>
                                    @endif
                                    <td>{{ $occupancy->type }}</td>
                                    <td>{{ $occupancy->number }} Person{{$occupancy->number > 1 ? 's' : ''}}</td>
                                    <td>{{ $occupancy->bookBefore->format('d M') }}</td>
                                </tr>
                            @endforeach
                            @foreach ($occupanciesLocal->values() as $index => $occupancy)
                                <tr>
                                    @if ($index == 0)
                                        <td rowspan="{{ $occupanciesLocal->count() }}">Local</td>
                                    @endif
                                    <td>{{ $occupancy->type }}</td>
                                    <td>{{ $occupancy->number }} Person{{$occupancy->number > 1 ? 's' : ''}}</td>
                                    <td>{{ $occupancy->bookBefore->format('d M') }}</td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>

        </div>
    </div>

    <div class="pt-3 pb-3"></div>

    <div class="card">
        <h4 class="card-header text-center">Hotel Rate Structure</h4>
        <div class="card-body">
            <div class="row pt-3 pb-3">
                <div class="col-12">
                    <button type="button" class="btn btn-primary float-end" data-bs-toggle="modal"
                        data-bs-target="#modifyRateModal" @disabled(!$form->hotels->count() || !$form->occupancies->count())>
                        Modify Hotel Rate Stucture
                    </button>
                </div>
            </div>

            @if ($form->hotels->count() && $form->occupancies->count())
                <div class="table-responsive">
                    <table class="table table-bordered text-center">
                        <thead>
                            <tr>
                                <th colspan="{{ $occupanciesInternational->count() + 2 }}" class="table-primary">
                                    International</th>
                            </tr>
                            <tr>
                                <th style="width: 30%"></th>
                                <th style="width: 10%">Code</th>
                                @php
                                    $widthEachColumnInternational = 60 / $occupanciesInternational->count();
                                @endphp
                                @foreach ($occupanciesInternational as $occupancy)
                                    <th style="width: {{ $widthEachColumnInternational }}%">
                                        Rate (BEFORE {{strtoupper($occupancy->bookBefore->format('F d, Y'))}}) {{ $occupancy->type }} ({{$occupancy->number}} {{$occupancy->number > 1 ? 'Persons' : 'Person'}})
                                    </th>
                                @endforeach
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($hotelsInternational as $hotel)
                                <tr>
                                    <td>{{ $hotel->description }} ({{ $hotel->getDaysAndNights() }}: Check In: {{$hotel->checkIn->format('d M')}} Check Out: {{$hotel->checkOut->format('d M')}})</td>
                                    <td>{{ $hotel->code }}</td>
                                    @foreach ($occupanciesInternational as $occupancy)
                                        <td>
                                            USD${{ DB::table('rates')->where('hotel_id', $hotel->id)->where('occupancy_id', $occupancy->id)->first()->amount ?? 0 }}
                                        </td>
                                    @endforeach
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                    <table class="table table-bordered text-center">
                        <thead>
                            <tr>
                                <th colspan="{{ $occupanciesLocal->count() + 2 }}" class="table-primary">
                                    Local</th>
                            </tr>
                            <tr>
                                <th style="width: 30%"></th>
                                <th style="width: 10%">Code</th>
                                @php
                                    $widthEachColumnLocal = 60 / $occupanciesLocal->count();
                                @endphp
                                @foreach ($occupanciesLocal as $occupancy)
                                    <th style="width: {{ $widthEachColumnLocal }}%">
                                        Rate (BEFORE {{strtoupper($occupancy->bookBefore->format('F d, Y'))}}) {{ $occupancy->type }} ({{$occupancy->number}}  {{$occupancy->number > 1 ? 'Persons' : 'Person'}})
                                    </th>
                                @endforeach
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($hotelsLocal as $hotel)
                                <tr>
                                    <td>{{ $hotel->description }} ({{ $hotel->getDaysAndNights() }}: Check In: {{$hotel->checkIn->format('d M')}} Check Out: {{$hotel->checkOut->format('d M')}})</td>
                                    <td>{{ $hotel->code }}</td>
                                    @foreach ($occupanciesLocal as $occupancy)
                                        <td>
                                            RM{{ DB::table('rates')->where('hotel_id', $hotel->id)->where('occupancy_id', $occupancy->id)->first()->amount ?? 0 }}
                                        </td>
                                    @endforeach
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @elseif ($form->hotels->count())
                <div class="display-6 text-center">
                    <p>Please add <strong>Occupancy</strong> to generate Hotel Rate Structure</p>
                </div>
            @elseif ($form->occupancies->count())
                <div class="display-6 text-center">
                    <p>Please add <strong>Hotel</strong> to generate Hotel Rate Structure</p>
                </div>
            @else
                <div class="display-6 text-center">
                    <p>Please add <strong>Hotel</strong> and <strong>Occupancy</strong> first</p>
                </div>
            @endif

        </div>
    </div>

    <div class="pt-3 pb-3"></div>

    <div class="modal fade" id="updateFormModal" tabindex="-1" aria-labelledby="updateFormModalLabel"
        aria-hidden="true">
        <div class="modal-dialog modal-xl">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title fs-5" id="updateFormModalLabel">Update Form</h4>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form action="{{ route('admin.competition.package.modify') }}" method="post" id="updateForm">
                        @csrf
                        @method('PATCH')

                        <input type="hidden" name="form_id" value="{{ $form->id }}">

                        <label for="categories" class="form-label"><strong>Categories</strong></label>
                        <div class="table-responsive">
                            <table class="table table-bordered align-middle" id="tableCategory">
                                <thead class="table-primary">
                                    <tr>
                                        <th style="width: 20%">Locality</th>
                                        <th style="width: 50%">Category Name</th>
                                        <th style="width: 20%">Code</th>
                                        <th>Need Proof?</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @php
                                        $localityList = DB::table('locality')->get();
                                    @endphp
                                    @foreach (old('categories', $form->categories) as $index => $category)
                                        <tr>
                                            <td>
                                                <select
                                                    class="form-select {{ $errors->has('categories.' . $index . '.locality') ? 'is-invalid' : '' }}"
                                                    name="categories[{{ $index }}][locality]"
                                                    id="categories.{{ $index }}.locality">
                                                    <option selected disabled>Choose Category {{ $index + 1 }} Locality
                                                    </option>
                                                    @foreach ($localityList as $locality)
                                                        <option value="{{ $locality->code }}"
                                                            @selected($locality->code == $category['locality'])>{{ $locality->name }}</option>
                                                    @endforeach
                                                </select>
                                                @error('categories.' . $index . '.locality')
                                                    <div class="invalid-feedback">
                                                        {{ $message }}
                                                    </div>
                                                @enderror
                                            </td>
                                            <td>
                                                <input type="text"
                                                    class="form-control {{ $errors->has('categories.' . $index . '.name') ? 'is-invalid' : '' }}"
                                                    name="categories[{{ $index }}][name]"
                                                    id="categories.{{ $index }}.name"
                                                    placeholder="Enter Category {{ $index + 1 }} Name"
                                                    value="{{ $category['name'] }}">
                                                @error('categories.' . $index . '.name')
                                                    <div class="invalid-feedback">
                                                        {{ $message }}
                                                    </div>
                                                @enderror
                                            </td>
                                            <td>
                                                <input type="text"
                                                    class="form-control {{ $errors->has('categories.' . $index . '.code') ? 'is-invalid' : '' }}"
                                                    name="categories[{{ $index }}][code]"
                                                    id="categories.{{ $index }}.code"
                                                    placeholder="Enter Category {{ $index + 1 }} Code"
                                                    value="{{ $category['code'] }}">
                                                @error('categories.' . $index . '.code')
                                                    <div class="invalid-feedback">
                                                        {{ $message }}
                                                    </div>
                                                @enderror
                                            </td>
                                            <td>
                                                <div
                                                    class="form-check form-switch form-switch-lg d-flex justify-content-center">
                                                    <input class="form-check-input" type="checkbox" role="switch"
                                                        name="categories[{{ $index }}][needProof]"
                                                        id="categories.{{ $index }}.needProof" value=1
                                                        @checked($category['needProof'] ?? false)>
                                                </div>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                            <div class="d-flex justify-content-end">
                                <div class="btn-group pt-3 pb-3" role="group" aria-label="Basic example">
                                    <button type="button" id="addCategory" class="btn btn-success"><i
                                            class="fa-solid fa-plus"></i></button>
                                    <button type="button" id="removeCategory" class="btn btn-danger"><i
                                            class="fa-solid fa-minus"></i></button>
                                </div>
                            </div>
                        </div>

                        <div class="p-3"></div>

                        <label for="durations" class="form-label"><strong>Registration Durations</strong></label>
                        <div class="table-responsive">
                            <table class="table table-bordered align-middle" id="tableDuration">
                                <thead class="table-primary">
                                    <tr>
                                        <th style="width: 20%">Locality</th>
                                        <th style="width: 50%">Duration Name</th>
                                        <th style="width: 15%">Start</th>
                                        <th>End</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach (old('durations', $form->durations) as $index => $duration)
                                        <tr>
                                            <td>
                                                <select
                                                    class="form-select {{ $errors->has('durations.' . $index . '.locality') ? 'is-invalid' : '' }}"
                                                    name="durations[{{ $index }}][locality]"
                                                    id="durations.{{ $index }}.locality">
                                                    <option selected disabled>Choose Duration {{ $index + 1 }} Locality
                                                    </option>
                                                    @foreach ($localityList as $locality)
                                                        <option value="{{ $locality->code }}"
                                                            @selected($locality->code == $duration['locality'])>{{ $locality->name }}</option>
                                                    @endforeach
                                                </select>
                                                @error('durations.' . $index . '.locality')
                                                    <div class="invalid-feedback">
                                                        {{ $message }}
                                                    </div>
                                                @enderror
                                            </td>
                                            <td>
                                                <input type="text"
                                                    class="form-control {{ $errors->has('durations.' . $index . '.name') ? 'is-invalid' : '' }}"
                                                    name="durations[{{ $index }}][name]"
                                                    id="durations.{{ $index }}.name"
                                                    placeholder="Enter Duration {{ $index + 1 }} Name"
                                                    value="{{ $duration['name'] }}">
                                                @error('durations.' . $index . '.name')
                                                    <div class="invalid-feedback">
                                                        {{ $message }}
                                                    </div>
                                                @enderror
                                            </td>
                                            <td>
                                                <input type="date"
                                                    class="form-control {{ $errors->has('durations.' . $index . '.start') ? 'is-invalid' : '' }}"
                                                    name="durations[{{ $index }}][start]"
                                                    id="durations.{{ $index }}.start"
                                                    value="{{ is_string($duration['start']) ? $duration['start'] : $duration['start']->format('Y-m-d') }}">
                                                @error('durations.' . $index . '.start')
                                                    <div class="invalid-feedback">
                                                        {{ $message }}
                                                    </div>
                                                @enderror
                                            </td>
                                            <td>
                                                <input type="date"
                                                    class="form-control {{ $errors->has('durations.' . $index . '.end') ? 'is-invalid' : '' }}"
                                                    name="durations[{{ $index }}][end]"
                                                    id="durations.{{ $index }}.end"
                                                    value="{{ is_string($duration['end']) ? $duration['end'] : $duration['end']->format('Y-m-d') }}">
                                                @error('durations.' . $index . '.end')
                                                    <div class="invalid-feedback">
                                                        {{ $message }}
                                                    </div>
                                                @enderror
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                            <div class="d-flex justify-content-end">
                                <div class="btn-group pt-3 pb-3" role="group" aria-label="Basic example">
                                    <button type="button" id="addDuration" class="btn btn-success"><i
                                            class="fa-solid fa-plus"></i></button>
                                    <button type="button" id="removeDuration" class="btn btn-danger"><i
                                            class="fa-solid fa-minus"></i></button>
                                </div>
                            </div>
                        </div>

                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                    <button type="submit" class="btn btn-primary" form="updateForm">Save</button>
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade" id="updatePackageModal" tabindex="-1" aria-labelledby="updatePackageModalLabel"
        aria-hidden="true">
        <div class="modal-dialog modal-xl">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title fs-5" id="updatePackageModalLabel">Update Package</h4>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form action="{{ route('admin.competition.package.update') }}" method="post" id="updatePackage">
                        @csrf
                        @method('PATCH')

                        <input type="hidden" name="form_id" value="{{ $form->id }}">

                        <div class="table-responsive">
                            <table class="table table-bordered" id="tablePackage">
                                <thead class="table-primary">
                                    <tr>
                                        <td style="width: 20%">Category</td>
                                        <td>Description</td>
                                        <td style="width: 20%">Code</td>
                                        <td style="width: 10%">Full Package?</td>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach (old('packages', $form->getPackages()) as $index => $package)
                                        <tr>
                                            <td>
                                                <select
                                                    class="form-select {{ $errors->has('packages.' . $index . '.category_id') ? 'is-invalid' : '' }}"
                                                    name="packages[{{ $index }}][category_id]"
                                                    id="packages.{{ $index }}.category_id">
                                                    <option selected disabled>Choose Package {{ $index + 1 }}'s
                                                        Category
                                                    </option>
                                                    @foreach ($form->categories as $category)
                                                        <option value="{{ $category->id }}" @selected($category->id == $package['category_id'])>
                                                            {{ $category->name }} ({{ $category->code }})</option>
                                                    @endforeach
                                                </select>
                                                @error('packages.' . $index . '.category_id')
                                                    <div class="invalid-feedback">
                                                        {{ $message }}
                                                    </div>
                                                @enderror
                                            </td>
                                            <td>
                                                <textarea class="form-control {{ $errors->has('packages.' . $index . '.description') ? 'is-invalid' : '' }}"
                                                    id="packages.{{ $index }}.description" name="packages[{{ $index }}][description]"
                                                    placeholder="Enter Package {{ $index + 1 }} Description" rows="3">{!! $package['description'] !!}</textarea>
                                                @error('packages.' . $index . '.description')
                                                    <div class="invalid-feedback">
                                                        {{ $message }}
                                                    </div>
                                                @enderror
                                            </td>
                                            <td>
                                                <input type="text"
                                                    class="form-control {{ $errors->has('packages.' . $index . '.code') ? 'is-invalid' : '' }}"
                                                    id="packages.{{ $index }}.code"
                                                    name="packages[{{ $index }}][code]"
                                                    placeholder="Enter Package {{ $index + 1 }} Code"
                                                    value="{{ $package['code'] }}">
                                                @error('packages.' . $index . '.code')
                                                    <div class="invalid-feedback">
                                                        {{ $message }}
                                                    </div>
                                                @enderror
                                            </td>
                                            <td>
                                                <div
                                                    class="form-check form-switch form-switch-lg d-flex justify-content-center">
                                                    <input class="form-check-input" type="checkbox" role="switch"
                                                        name="packages[{{ $index }}][fullPackage]"
                                                        id="packages.{{ $index }}.fullPackage" value=1
                                                        @checked($package['fullPackage'] ?? false)>
                                                </div>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                            <div class="d-flex justify-content-end">
                                <div class="btn-group pt-3 pb-3" role="group" aria-label="Basic example">
                                    <button type="button" id="addPackage" class="btn btn-success"><i
                                            class="fa-solid fa-plus"></i></button>
                                    <button type="button" id="removePackage" class="btn btn-danger"><i
                                            class="fa-solid fa-minus"></i></button>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                    <button type="submit" class="btn btn-primary" form="updatePackage">Save</button>
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade" id="modifyFeeModal" tabindex="-1" aria-labelledby="modifyFeeModalLabel" aria-hidden=true>
        <div class="modal-dialog modal-xl">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title fs-5" id="modifyFeeModalLabel">Modify Fee Structure</h4>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form action="{{ route('admin.competition.fee.update') }}" method="post" id="modifyFee">
                        @csrf
                        @method('PATCH')

                        <input type="hidden" name="form_id" value="{{ $form->id }}">

                        <div class="table-responsive">
                            <table class="table table-bordered text-center">
                                <thead>
                                    <tr>
                                        <th colspan="{{ $durationsLocal->count() + 2 }}" class="table-primary">
                                            International</th>
                                    </tr>
                                    <tr>
                                        <th style="width: 30%"></th>
                                        <th style="width: 10%">Code</th>
                                        @php
                                            $widthEachColumnInternational = 60 / $durationsInternational->count();
                                        @endphp
                                        @foreach ($durationsInternationalWihtoutOnsite as $duration)
                                            <th style="width: {{ $widthEachColumnInternational }}%">
                                                {{ $duration->name }}
                                                <br>
                                                ({{ $duration->start->translatedFormat('j/m/Y') }} -
                                                {{ $duration->end->translatedFormat('j/m/Y') }})
                                            </th>
                                        @endforeach
                                        <th style="width: {{ $widthEachColumnInternational }}%">
                                            On-site
                                        </th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @php
                                        $feeIndex = 0;
                                    @endphp
                                    @foreach ($categoriesInternational as $category)
                                        <tr>
                                            <th colspan="{{ $durationsInternational->count() + 2 }}">
                                                {{ $category->name }}
                                                ({{ $category->code }})
                                            </th>
                                        </tr>
                                        @foreach ($category->packages as $package)
                                            <tr {!! $package->fullPackage ? 'class="table-success"' : '' !!}>
                                                <td>{!! $package->description !!}</td>
                                                <td>{{ $package->code }}
                                                    {{ $package->fullPackage ? '(Full Package)' : '' }}</td>
                                                @foreach ($durationsInternationalWihtoutOnsite as $duration)
                                                    <td>
                                                        <input type="hidden"
                                                            name="fee[{{ $feeIndex }}][duration_id]"
                                                            value="{{ $duration->id }}">
                                                        <input type="hidden"
                                                            name="fee[{{ $feeIndex }}][package_id]"
                                                            value="{{ $package->id }}">
                                                        <div class="input-group mb-3">
                                                            <span class="input-group-text">USD$</span>
                                                            <input type="number"
                                                                class="form-control {{ $errors->has('fee.' . $feeIndex . '.amount') }}"
                                                                name="fee[{{ $feeIndex }}][amount]"
                                                                value="{{ old('fee.' . $feeIndex . '.amount',DB::table('fees')->where('parent_type', 'App\Models\Package')->where('parent_id', $package->id)->where('duration_id', $duration->id)->first()->amount ?? 0) }}">
                                                        </div>
                                                        @error('fee.' . $feeIndex . '.amount')
                                                            <div class="invalid-feedback">
                                                                {{ $message }}
                                                            </div>
                                                        @enderror
                                                    </td>
                                                    @php
                                                        $feeIndex++;
                                                    @endphp
                                                @endforeach
                                                <td>
                                                    <input type="hidden" name="fee[{{ $feeIndex }}][duration_id]"
                                                        value="{{ $OnSiteDurationInternational->id }}">
                                                    <input type="hidden" name="fee[{{ $feeIndex }}][package_id]"
                                                        value="{{ $package->id }}">
                                                    <div class="input-group mb-3">
                                                        <span class="input-group-text">USD$</span>
                                                        <input type="number"
                                                            class="form-control {{ $errors->has('fee.' . $feeIndex . '.amount') }}"
                                                            name="fee[{{ $feeIndex }}][amount]"
                                                            value="{{ old('fee.' . $feeIndex . '.amount',DB::table('fees')->where('parent_type', 'App\Models\Package')->where('parent_id', $package->id)->where('duration_id', $OnSiteDurationInternational->id)->first()->amount ?? 0) }}">
                                                    </div>
                                                    @error('fee.' . $feeIndex . '.amount')
                                                        <div class="invalid-feedback">
                                                            {{ $message }}
                                                        </div>
                                                    @enderror
                                                </td>
                                                @php
                                                    $feeIndex++;
                                                @endphp
                                            </tr>
                                        @endforeach
                                    @endforeach
                                </tbody>
                            </table>
                            <table class="table table-bordered text-center">
                                <thead>
                                    <tr>
                                        <th colspan="{{ $durationsLocal->count() + 2 }}" class="table-primary">
                                            Local</th>
                                    </tr>
                                    <tr>
                                        <th style="width: 30%"></th>
                                        <th style="width: 10%">Code</th>
                                        @php
                                            $widthEachColumnLocal = 60 / $durationsLocal->count();
                                        @endphp
                                        @foreach ($durationsLocalWihtoutOnsite as $duration)
                                            <th style="width: {{ $widthEachColumnLocal }}%">
                                                {{ $duration->name }}
                                                <br>
                                                ({{ $duration->start->translatedFormat('j/m/Y') }} -
                                                {{ $duration->end->translatedFormat('j/m/Y') }})
                                            </th>
                                        @endforeach
                                        <th style="width: {{ $widthEachColumnLocal }}%">
                                            On-site
                                        </th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($categoriesLocal as $category)
                                        <tr>
                                            <th colspan="{{ $durationsLocal->count() + 2 }}">{{ $category->name }}
                                                ({{ $category->code }})
                                            </th>
                                        </tr>
                                        @foreach ($category->packages as $package)
                                            <tr {!! $package->fullPackage ? 'class="table-success"' : '' !!}>
                                                <td>{!! $package->description !!}</td>
                                                <td>{{ $package->code }}
                                                    {{ $package->fullPackage ? '(Full Package)' : '' }}</td>
                                                @foreach ($durationsLocalWihtoutOnsite as $duration)
                                                    <td>
                                                        <input type="hidden"
                                                            name="fee[{{ $feeIndex }}][duration_id]"
                                                            value="{{ $duration->id }}">
                                                        <input type="hidden"
                                                            name="fee[{{ $feeIndex }}][package_id]"
                                                            value="{{ $package->id }}">
                                                        <div class="input-group mb-3">
                                                            <span class="input-group-text">RM</span>
                                                            <input type="number"
                                                                class="form-control {{ $errors->has('fee.' . $feeIndex . '.amount') }}"
                                                                name="fee[{{ $feeIndex }}][amount]"
                                                                value="{{ old('fee.' . $feeIndex . '.amount',DB::table('fees')->where('parent_type', 'App\Models\Package')->where('parent_id', $package->id)->where('duration_id', $duration->id)->first()->amount ?? 0) }}">
                                                        </div>
                                                        @error('fee.' . $feeIndex . '.amount')
                                                            <div class="invalid-feedback">
                                                                {{ $message }}
                                                            </div>
                                                        @enderror
                                                    </td>
                                                    @php
                                                        $feeIndex++;
                                                    @endphp
                                                @endforeach
                                                <td>
                                                    <input type="hidden" name="fee[{{ $feeIndex }}][duration_id]"
                                                        value="{{ $OnSiteDurationLocal->id }}">
                                                    <input type="hidden" name="fee[{{ $feeIndex }}][package_id]"
                                                        value="{{ $package->id }}">
                                                    <div class="input-group mb-3">
                                                        <span class="input-group-text">RM</span>
                                                        <input type="number"
                                                            class="form-control {{ $errors->has('fee.' . $feeIndex . '.amount') }}"
                                                            name="fee[{{ $feeIndex }}][amount]"
                                                            value="{{ old('fee.' . $feeIndex . '.amount',DB::table('fees')->where('parent_type', 'App\Models\Package')->where('parent_id', $package->id)->where('duration_id', $OnSiteDurationLocal->id)->first()->amount ?? 0) }}">
                                                    </div>
                                                    @error('fee.' . $feeIndex . '.amount')
                                                        <div class="invalid-feedback">
                                                            {{ $message }}
                                                        </div>
                                                    @enderror
                                                </td>
                                                @php
                                                    $feeIndex++;
                                                @endphp
                                            </tr>
                                        @endforeach
                                    @endforeach
                                </tbody>
                            </table>
                        </div>

                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                    <button type="submit" class="btn btn-primary" form="modifyFee">Save</button>
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade" id="addExtraModal" tabindex="-1" aria-labelledby="addExtraModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-xl">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title fs-5" id="addExtraModalLabel">Add Extra</h4>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form action="{{ route('admin.competition.extra.create') }}" method="post" id="addExtra">
                        @csrf

                        <input type="hidden" name="form_id" value="{{ $form->id }}">

                        <div class="mb-3">
                            <label for="extra.description" class="form-label">Description</label>
                            <input type="text" class="form-control {{ $errors->has('extra.description') }}"
                                id="extra.description" name="extra[description]" placeholder="Enter Extra Description"
                                value="{{ old('extra.description') }}">
                            @error('extra.description')
                                <div class="invalid-feedback">
                                    {{ $message }}
                                </div>
                            @enderror
                        </div>

                        <div class="mb-3 row">
                            <div class="col-md-6">
                                <label for="extra.code" class="form-label">Code</label>
                                <input type="text" class="form-control {{ $errors->has('extra.code') }}"
                                    id="extra.code" name="extra[code]" placeholder="Enter Extra Code"
                                    value="{{ old('extra.code') }}">
                                @error('extra.code')
                                    <div class="invalid-feedback">
                                        {{ $message }}
                                    </div>
                                @enderror
                            </div>
                            <div class="col-md-6">
                                <label for="extra.date" class="form-label">Date</label>
                                <input type="date" class="form-control {{ $errors->has('extra.date') }}"
                                    id="extra.date" name="extra[date]" placeholder="Enter Extra date"
                                    value="{{ old('extra.date') }}">
                                @error('extra.date')
                                    <div class="invalid-feedback">
                                        {{ $message }}
                                    </div>
                                @enderror
                            </div>
                        </div>

                        <div class="mb-3">
                            <label for="extra" class="form-label">Options</label>
                            <div class="table-responsive" id="extra.options">
                                <table class="table table-bordered" id="tableOptions">
                                    <thead class="table-primary">
                                        <tr>
                                            <th>Option</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach (old('extra.options', []) as $index => $option)
                                            <tr>
                                                <td>
                                                    <input type="text"
                                                        class="form-control {{ $errors->has('extra.option.' . $index) }}"
                                                        name="extra[option][{{ $index }}]"
                                                        id="extra.option.{{ $index }}"
                                                        placeholder="Extra Option {{ $index + 1 }}"
                                                        value="{{ $option }}">
                                                    @error('extra.option.' . $index)
                                                        <div class="invalid-feeback">
                                                            {{ $message }}
                                                        </div>
                                                    @enderror
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                                <div class="d-flex justify-content-end">
                                    <div class="btn-group pt-3 pb-3" role="group" aria-label="Basic example">
                                        <button type="button" id="addOption" class="btn btn-success"><i
                                                class="fa-solid fa-plus"></i></button>
                                        <button type="button" id="removeOption" class="btn btn-danger"><i
                                                class="fa-solid fa-minus"></i></button>
                                    </div>
                                </div>
                            </div>
                        </div>

                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                    <button type="submit" class="btn btn-primary" form="addExtra">Save</button>
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade" id="modifyExtraFeeModal" tabindex="-1" aria-labelledby="modifyExtraFeeModalLabel"
        aria-hidden=true>
        <div class="modal-dialog modal-xl">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title fs-5" id="modifyExtraFeeModalLabel">Modify Extra Fee Structure</h4>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form action="{{ route('admin.competition.fee.modify') }}" method="post" id="modifyExtraFee">
                        @csrf
                        @method('PATCH')

                        <input type="hidden" name="form_id" value="{{ $form->id }}">

                        <div class="table-responsive">
                            <table class="table table-bordered text-center">
                                <thead>
                                    <tr>
                                        <th colspan="{{ $durationsInternational->count() + 2 }}" class="table-primary">
                                            International</th>
                                    </tr>
                                    <tr>
                                        <th style="width: 30%"></th>
                                        <th style="width: 10%">Code</th>
                                        @php
                                            $widthEachColumnInternational = 60 / $durationsInternational->count();
                                        @endphp
                                        @foreach ($durationsInternationalWihtoutOnsite as $duration)
                                            <th style="width: {{ $widthEachColumnInternational }}%">
                                                {{ $duration->name }}
                                                <br>
                                                ({{ $duration->start->translatedFormat('j/m/Y') }} -
                                                {{ $duration->end->translatedFormat('j/m/Y') }})
                                            </th>
                                        @endforeach
                                        <th style="width: {{ $widthEachColumnInternational }}%">
                                            On-site
                                        </th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @php
                                        $feeIndex = 0;
                                    @endphp
                                    @foreach ($form->extras as $extra)
                                        <tr>
                                            <td>{{ $extra->description }} ({{ $extra->date->format('d M') }})</td>
                                            <td>{{ $extra->code }}</td>
                                            @foreach ($durationsInternationalWihtoutOnsite as $duration)
                                                <td>
                                                    <input type="hidden"
                                                        name="extraFee[{{ $feeIndex }}][duration_id]"
                                                        value="{{ $duration->id }}">
                                                    <input type="hidden" name="extraFee[{{ $feeIndex }}][extra_id]"
                                                        value="{{ $extra->id }}">
                                                    <div class="input-group mb-3">
                                                        <span class="input-group-text">USD$</span>
                                                        <input type="number"
                                                            class="form-control {{ $errors->has('extraFee.' . $feeIndex . '.amount') }}"
                                                            name="extraFee[{{ $feeIndex }}][amount]"
                                                            value="{{ old('extraFee.' . $feeIndex . '.amount',DB::table('fees')->where('parent_type', 'App\Models\Extra')->where('parent_id', $extra->id)->where('duration_id', $duration->id)->first()->amount ?? 0) }}">
                                                    </div>
                                                    @error('extraFee.' . $feeIndex . '.amount')
                                                        <div class="invalid-feedback">
                                                            {{ $message }}
                                                        </div>
                                                    @enderror
                                                </td>
                                                @php
                                                    $feeIndex++;
                                                @endphp
                                            @endforeach
                                            <td>
                                                <input type="hidden" name="extraFee[{{ $feeIndex }}][duration_id]"
                                                    value="{{ $OnSiteDurationInternational->id }}">
                                                <input type="hidden" name="extraFee[{{ $feeIndex }}][extra_id]"
                                                    value="{{ $extra->id }}">
                                                <div class="input-group mb-3">
                                                    <span class="input-group-text">USD$</span>
                                                    <input type="number"
                                                        class="form-control {{ $errors->has('extraFee.' . $feeIndex . '.amount') }}"
                                                        name="extraFee[{{ $feeIndex }}][amount]"
                                                        value="{{ old('extraFee.' . $feeIndex . '.amount',DB::table('fees')->where('parent_type', 'App\Models\Extra')->where('parent_id', $extra->id)->where('duration_id', $OnSiteDurationInternational->id)->first()->amount ?? 0) }}">
                                                </div>
                                                @error('extraFee.' . $feeIndex . '.amount')
                                                    <div class="invalid-feedback">
                                                        {{ $message }}
                                                    </div>
                                                @enderror
                                            </td>
                                            @php
                                                $feeIndex++;
                                            @endphp
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                            <table class="table table-bordered text-center">
                                <thead>
                                    <tr>
                                        <th colspan="{{ $durationsLocal->count() + 2 }}" class="table-primary">
                                            Local</th>
                                    </tr>
                                    <tr>
                                        <th style="width: 30%"></th>
                                        <th style="width: 10%">Code</th>
                                        @php
                                            $widthEachColumnLocal = 60 / $durationsLocal->count();
                                        @endphp
                                        @foreach ($durationsLocalWihtoutOnsite as $duration)
                                            <th style="width: {{ $widthEachColumnLocal }}%">
                                                {{ $duration->name }}
                                                <br>
                                                ({{ $duration->start->translatedFormat('j/m/Y') }} -
                                                {{ $duration->end->translatedFormat('j/m/Y') }})
                                            </th>
                                        @endforeach
                                        <th style="width: {{ $widthEachColumnLocal }}%">
                                            On-site
                                        </th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($form->extras as $extra)
                                        <tr>
                                            <td>{{ $extra->description }} ({{ $extra->date->format('d M') }})</td>
                                            <td>{{ $extra->code }}</td>
                                            @foreach ($durationsLocalWihtoutOnsite as $duration)
                                                <td>
                                                    <input type="hidden"
                                                        name="extraFee[{{ $feeIndex }}][duration_id]"
                                                        value="{{ $duration->id }}">
                                                    <input type="hidden" name="extraFee[{{ $feeIndex }}][extra_id]"
                                                        value="{{ $extra->id }}">
                                                    <div class="input-group mb-3">
                                                        <span class="input-group-text">RM</span>
                                                        <input type="number"
                                                            class="form-control {{ $errors->has('extraFee.' . $feeIndex . '.amount') }}"
                                                            name="extraFee[{{ $feeIndex }}][amount]"
                                                            value="{{ old('extraFee.' . $feeIndex . '.amount',DB::table('fees')->where('parent_type', 'App\Models\Extra')->where('parent_id', $extra->id)->where('duration_id', $duration->id)->first()->amount ?? 0) }}">
                                                    </div>
                                                    @error('extraFee.' . $feeIndex . '.amount')
                                                        <div class="invalid-feedback">
                                                            {{ $message }}
                                                        </div>
                                                    @enderror
                                                </td>
                                                @php
                                                    $feeIndex++;
                                                @endphp
                                            @endforeach
                                            <td>
                                                <input type="hidden" name="extraFee[{{ $feeIndex }}][duration_id]"
                                                    value="{{ $OnSiteDurationLocal->id }}">
                                                <input type="hidden" name="extraFee[{{ $feeIndex }}][extra_id]"
                                                    value="{{ $extra->id }}">
                                                <div class="input-group mb-3">
                                                    <span class="input-group-text">RM</span>
                                                    <input type="number"
                                                        class="form-control {{ $errors->has('extraFee.' . $feeIndex . '.amount') }}"
                                                        name="extraFee[{{ $feeIndex }}][amount]"
                                                        value="{{ old('extraFee.' . $feeIndex . '.amount',DB::table('fees')->where('parent_type', 'App\Models\Extra')->where('parent_id', $extra->id)->where('duration_id', $OnSiteDurationLocal->id)->first()->amount ?? 0) }}">
                                                </div>
                                                @error('extraFee.' . $feeIndex . '.amount')
                                                    <div class="invalid-feedback">
                                                        {{ $message }}
                                                    </div>
                                                @enderror
                                            </td>
                                            @php
                                                $feeIndex++;
                                            @endphp
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>

                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                    <button type="submit" class="btn btn-primary" form="modifyExtraFee">Save</button>
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade" id="modifyHotelModal" tabindex="-1" aria-labelledby="modifyHotelModalLabel"
        aria-hidden=true>
        <div class="modal-dialog modal-xl">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title fs-5" id="modifyHotelModalLabel">Modify Extra Fee Structure</h4>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form action="{{ route('admin.competition.hotel.update') }}" method="post" id="modifyHotel">
                        @csrf
                        @method('PATCH')

                        <input type="hidden" name="form_id" value="{{ $form->id }}">

                        <label for="hotels" class="form-label"><strong>Hotel Accomodation</strong></label>
                        <div class="table-responsive" id="hotels">
                            <table class="table table-bordered align-middle" id="tableHotel">
                                <thead class="table-primary">
                                    <tr>
                                        <th style="width: 15%">Locality</th>
                                        <th>Description</th>
                                        <th style="width: 10%">Code</th>
                                        <th style="width: 15%">Check In</th>
                                        <th style="width: 15%">Check Out</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach (old('hotels', $form->hotels) as $index => $hotel)
                                        <tr>
                                            <td>
                                                <select
                                                    class="form-select {{ $errors->has('hotels.' . $index . '.locality') ? 'is-invalid' : '' }}"
                                                    name="hotels[{{ $index }}][locality]"
                                                    id="hotels.{{ $index }}.locality">
                                                    <option selected disabled>Choose Hotel {{ $index + 1 }} Locality
                                                    </option>
                                                    @foreach ($localityList as $locality)
                                                        <option value="{{ $locality->code }}"
                                                            @selected($locality->code == $hotel['locality'])>{{ $locality->name }}</option>
                                                    @endforeach
                                                </select>
                                                @error('hotels.' . $index . '.locality')
                                                    <div class="invalid-feedback">
                                                        {{ $message }}
                                                    </div>
                                                @enderror
                                            </td>
                                            <td>
                                                <input type="text"
                                                    class="form-control {{ $errors->has('hotels.' . $index . '.description') ? 'is-invalid' : '' }}"
                                                    name="hotels[{{ $index }}][description]"
                                                    id="hotels.{{ $index }}.description"
                                                    placeholder="Enter Hotel {{ $index + 1 }} Description"
                                                    value="{{ $hotel['description'] }}">
                                                @error('hotels.' . $index . '.description')
                                                    <div class="invalid-feedback">
                                                        {{ $message }}
                                                    </div>
                                                @enderror
                                            </td>
                                            <td>
                                                <input type="text"
                                                    class="form-control {{ $errors->has('hotels.' . $index . '.code') ? 'is-invalid' : '' }}"
                                                    name="hotels[{{ $index }}][code]"
                                                    id="hotels.{{ $index }}.code"
                                                    placeholder="Enter Hotel {{ $index + 1 }} Code"
                                                    value="{{ $hotel['code'] }}">
                                                @error('hotels.' . $index . '.code')
                                                    <div class="invalid-feedback">
                                                        {{ $message }}
                                                    </div>
                                                @enderror
                                            </td>
                                            <td>
                                                <input type="date"
                                                    class="form-control {{ $errors->has('hotels.' . $index . '.checkIn') ? 'is-invalid' : '' }}"
                                                    name="hotels[{{ $index }}][checkIn]"
                                                    id="hotels.{{ $index }}.checkIn"
                                                    value="{{ is_string($hotel['checkIn']) ? $hotel['checkIn'] : $hotel['checkIn']->format('Y-m-d') }}">
                                                @error('hotels.' . $index . '.checkIn')
                                                    <div class="invalid-feedback">
                                                        {{ $message }}
                                                    </div>
                                                @enderror
                                            </td>
                                            <td>
                                                <input type="date"
                                                    class="form-control {{ $errors->has('hotels.' . $index . '.checkOut') ? 'is-invalid' : '' }}"
                                                    name="hotels[{{ $index }}][checkOut]"
                                                    id="hotels.{{ $index }}.checkOut"
                                                    value="{{ is_string($hotel['checkOut']) ? $hotel['checkOut'] : $hotel['checkOut']->format('Y-m-d') }}">
                                                @error('hotels.' . $index . '.checkOut')
                                                    <div class="invalid-feedback">
                                                        {{ $message }}
                                                    </div>
                                                @enderror
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                            <div class="d-flex justify-content-end">
                                <div class="btn-group pt-3 pb-3" role="group" aria-label="Basic example">
                                    <button type="button" id="addHotel" class="btn btn-success"><i
                                            class="fa-solid fa-plus"></i></button>
                                    <button type="button" id="removeHotel" class="btn btn-danger"><i
                                            class="fa-solid fa-minus"></i></button>
                                </div>
                            </div>
                        </div>

                        <div class="p-3"></div>

                        <label for="occupancies" class="form-label"><strong>Occupancy</strong></label>
                        <div class="table-responsive" id="occupancies">
                            <table class="table table-bordered align-middle" id="tableOccupancy">
                                <thead class="table-primary">
                                    <tr>
                                        <th style="width: 15%">Locality</th>
                                        <th>Room Type</th>
                                        <th style="width: 20%">Number of Person</th>
                                        <th style="width: 15%">Book Before</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach (old('occupancies', $form->occupancies) as $index => $occupancy)
                                        <tr>
                                            <td>
                                                <select
                                                    class="form-select {{ $errors->has('occupancies.' . $index . '.locality') ? 'is-invalid' : '' }}"
                                                    name="occupancies[{{ $index }}][locality]"
                                                    id="occupancies.{{ $index }}.locality">
                                                    <option selected disabled>Choose Occupancy {{ $index + 1 }} Locality
                                                    </option>
                                                    @foreach ($localityList as $locality)
                                                        <option value="{{ $locality->code }}"
                                                            @selected($locality->code == $occupancy['locality'])>{{ $locality->name }}</option>
                                                    @endforeach
                                                </select>
                                                @error('occupancies.' . $index . '.locality')
                                                    <div class="invalid-feedback">
                                                        {{ $message }}
                                                    </div>
                                                @enderror
                                            </td>
                                            <td>
                                                <input type="text"
                                                    class="form-control {{ $errors->has('occupancies.' . $index . '.type') ? 'is-invalid' : '' }}"
                                                    name="occupancies[{{ $index }}][type]"
                                                    id="occupancies.{{ $index }}.type"
                                                    placeholder="Enter Occupancy {{ $index + 1 }} Room Type"
                                                    value="{{ $occupancy['type'] }}">
                                                @error('occupancies.' . $index . '.type')
                                                    <div class="invalid-feedback">
                                                        {{ $message }}
                                                    </div>
                                                @enderror
                                            </td>
                                            <td>
                                                <input type="text"
                                                    class="form-control {{ $errors->has('occupancies.' . $index . '.number') ? 'is-invalid' : '' }}"
                                                    name="occupancies[{{ $index }}][number]"
                                                    id="occupancies.{{ $index }}.number"
                                                    placeholder="Enter Occupancy {{ $index + 1 }} Number of Person"
                                                    value="{{ $occupancy['number'] }}">
                                                @error('occupancies.' . $index . '.number')
                                                    <div class="invalid-feedback">
                                                        {{ $message }}
                                                    </div>
                                                @enderror
                                            </td>
                                            <td>
                                                <input type="date"
                                                    class="form-control {{ $errors->has('occupancies.' . $index . '.bookBefore') ? 'is-invalid' : '' }}"
                                                    name="occupancies[{{ $index }}][bookBefore]"
                                                    id="occupancies.{{ $index }}.bookBefore"
                                                    value="{{ is_string($occupancy['bookBefore']) ? $occupancy['bookBefore'] : $occupancy['bookBefore']->format('Y-m-d') }}">
                                                @error('occupancies.' . $index . '.bookBefore')
                                                    <div class="invalid-feedback">
                                                        {{ $message }}
                                                    </div>
                                                @enderror
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                            <div class="d-flex justify-content-end">
                                <div class="btn-group pt-3 pb-3" role="group" aria-label="Basic example">
                                    <button type="button" id="addOccupancy" class="btn btn-success"><i
                                            class="fa-solid fa-plus"></i></button>
                                    <button type="button" id="removeOccupancy" class="btn btn-danger"><i
                                            class="fa-solid fa-minus"></i></button>
                                </div>
                            </div>
                        </div>

                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                    <button type="submit" class="btn btn-primary" form="modifyHotel">Save</button>
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade" id="modifyRateModal" tabindex="-1" aria-labelledby="modifyRateModalLabel"
        aria-hidden=true>
        <div class="modal-dialog modal-xl">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title fs-5" id="modifyRateModalLabel">Modify Hotel Rate Structure</h4>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form action="{{ route('admin.competition.rate.update') }}" method="post" id="modifyRate">
                        @csrf
                        @method('PATCH')

                        <input type="hidden" name="form_id" value="{{ $form->id }}">

                        <div class="table-responsive">
                            <table class="table table-bordered text-center">
                                <thead>
                                    <tr>
                                        <th colspan="{{ $occupanciesInternational->count() + 2 }}" class="table-primary">
                                            International</th>
                                    </tr>
                                    <tr>
                                        <th style="width: 30%"></th>
                                        <th style="width: 10%">Code</th>
                                        @php
                                            $widthEachColumnInternational = 60 / $occupanciesInternational->count();
                                        @endphp
                                        @foreach ($occupanciesInternational as $occupancy)
                                            <th style="width: {{ $widthEachColumnInternational }}%">
                                                Rate (BEFORE {{strtoupper($occupancy->bookBefore->format('F d, Y'))}}) {{ $occupancy->type }} ({{$occupancy->number}}  {{$occupancy->number > 1 ? 'Persons' : 'Person'}})
                                            </th>
                                        @endforeach
                                    </tr>
                                </thead>
                                <tbody>
                                    @php
                                        $rateIndex = 0;
                                    @endphp
                                    @foreach ($hotelsInternational as $hotel)
                                        <tr>
                                            <td>{{ $hotel->description }} ({{ $hotel->getDaysAndNights() }}: Check In: {{$hotel->checkIn->format('d M')}} Check Out: {{$hotel->checkOut->format('d M')}})</td>
                                            <td>{{ $hotel->code }}</td>
                                            @foreach ($occupanciesInternational as $occupancy)
                                                <td>
                                                    <input type="hidden"
                                                        name="rates[{{ $rateIndex }}][occupancy_id]"
                                                        value="{{ $occupancy->id }}">
                                                    <input type="hidden" name="rates[{{ $rateIndex }}][hotel_id]"
                                                        value="{{ $hotel->id }}">
                                                    <div class="input-group mb-3">
                                                        <span class="input-group-text">USD$</span>
                                                        <input type="number"
                                                            class="form-control {{ $errors->has('rates.' . $rateIndex . '.amount') }}"
                                                            name="rates[{{ $rateIndex }}][amount]"
                                                            value="{{ old('rates.' . $rateIndex . '.amount',DB::table('rates')->where('hotel_id', $hotel->id)->where('occupancy_id', $occupancy->id)->first()->amount ?? 0) }}">
                                                    </div>
                                                    @error('rates.' . $rateIndex . '.amount')
                                                        <div class="invalid-feedback">
                                                            {{ $message }}
                                                        </div>
                                                    @enderror
                                                </td>
                                                @php
                                                    $rateIndex++;
                                                @endphp
                                            @endforeach
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                            <table class="table table-bordered text-center">
                                <thead>
                                    <tr>
                                        <th colspan="{{ $occupanciesLocal->count() + 2 }}" class="table-primary">
                                            Local</th>
                                    </tr>
                                    <tr>
                                        <th style="width: 30%"></th>
                                        <th style="width: 10%">Code</th>
                                        @php
                                            $widthEachColumnLocal = 60 / $occupanciesLocal->count();
                                        @endphp
                                        @foreach ($occupanciesLocal as $occupancy)
                                            <th style="width: {{ $widthEachColumnLocal }}%">
                                                Rate (BEFORE {{strtoupper($occupancy->bookBefore->format('F d, Y'))}}) {{ $occupancy->type }} ({{$occupancy->number}}  {{$occupancy->number > 1 ? 'Persons' : 'Person'}})
                                            </th>
                                        @endforeach
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($hotelsLocal as $hotel)
                                        <tr>
                                            <td>{{ $hotel->description }} ({{ $hotel->getDaysAndNights() }}: Check In: {{$hotel->checkIn->format('d M')}} Check Out: {{$hotel->checkOut->format('d M')}})</td>
                                            <td>{{ $hotel->code }}</td>
                                            @foreach ($occupanciesLocal as $occupancy)
                                                <td>
                                                    <input type="hidden"
                                                        name="rates[{{ $rateIndex }}][occupancy_id]"
                                                        value="{{ $occupancy->id }}">
                                                    <input type="hidden" name="rates[{{ $rateIndex }}][hotel_id]"
                                                        value="{{ $hotel->id }}">
                                                    <div class="input-group mb-3">
                                                        <span class="input-group-text">RM</span>
                                                        <input type="number"
                                                            class="form-control {{ $errors->has('rates.' . $rateIndex . '.amount') }}"
                                                            name="rates[{{ $rateIndex }}][amount]"
                                                            value="{{ old('rates.' . $rateIndex . '.amount',DB::table('rates')->where('hotel_id', $hotel->id)->where('occupancy_id', $occupancy->id)->first()->amount ?? 0) }}">
                                                    </div>
                                                    @error('rates.' . $rateIndex . '.amount')
                                                        <div class="invalid-feedback">
                                                            {{ $message }}
                                                        </div>
                                                    @enderror
                                                </td>
                                                @php
                                                    $rateIndex++;
                                                @endphp
                                            @endforeach
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>

                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                    <button type="submit" class="btn btn-primary" form="modifyRate">Save</button>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('scripts')
    <script>
        $(document).ready(function() {

            $('#checkAll').click(function() {
                var checked = this.checked;
                $('input[class="checkboxTick"]').each(function() {
                    this.checked = checked;
                });
            });
        });

        //Category
        const addCategoryButton = document.getElementById('addCategory');
        const currentIndexCategory =
            {{ count(old('categories', $form->categories)) }};

        let iC = currentIndexCategory;
        addCategoryButton.addEventListener('click', function() {

            var stringHtmlScaleElements = `<tr>
                                            <td>
                                                <select class="form-select" name="categories[` + iC +
                `][locality]" id="categories.` + iC + `.locality">
                                                    <option selected disabled>Choose Category ` + (iC + 1) + ` Locality
                                                    </option>
                                                    @foreach ($localityList as $locality)
                                                        <option value="{{ $locality->code }}">{{ $locality->name }}</option>
                                                    @endforeach
                                                </select>
                                            </td>
                                            <td>
                                                <input type="text" class="form-control" name="categories[` + iC + `][name]"
                                                    id="categories.` + iC + `.name" placeholder="Enter Category ` + (
                    iC + 1) + ` Name">
                                            </td>
                                            <td>
                                                <input type="text" class="form-control" name="categories[` + iC + `][code]"
                                                    id="categories.` + iC + `.code" placeholder="Enter Category ` + (
                    iC + 1) +
                ` Code">
                                            </td>
                                            <td>
                                                <div class="form-check form-switch form-switch-lg d-flex justify-content-center">
                                                    <input class="form-check-input" type="checkbox" role="switch" name="categories[` +
                iC + `][needProof]"
                                                        id="categories.` + iC + `.needProof" value=1>
                                                </div>
                                            </td>
                                        </tr>`;

            $("#tableCategory tbody").append(stringHtmlScaleElements);

            iC++;
        });

        $("#removeCategory").on("click", function() {
            if (iC != 0) {
                iC--
                $('#tableCategory tr:last').remove();
            }
        });

        //Duration
        const addDurationButton = document.getElementById('addDuration');
        const currentIndexDuration =
            {{ count(old('durations', $form->durations)) }};

        let iD = currentIndexDuration;
        addDurationButton.addEventListener('click', function() {

            var stringHtmlScaleElements = `<tr>
                                            <td>
                                                <select class="form-select" name="durations[` + iD +
                `][locality]" id="durations.` + iD + `.locality">
                                                    <option selected disabled>Choose Duration ` + (iD + 1) + ` Locality
                                                    </option>
                                                    @foreach ($localityList as $locality)
                                                        <option value="{{ $locality->code }}">{{ $locality->name }}</option>
                                                    @endforeach
                                                </select>
                                            </td>
                                            <td>
                                                <input type="text" class="form-control" name="durations[` + iD +
                `][name]" id="durations.` + iD + `.name"
                                                    placeholder="Enter Duration ` + (iD + 1) + ` Name">
                                            </td>
                                            <td>
                                                <input type="date" class="form-control" name="durations[` + iD + `][start]"
                                                    id="durations.` + iD + `.start">
                                            </td>
                                            <td>
                                                <input type="date" class="form-control" name="durations[` + iD +
                `][end]" id="durations.` + iD + `.end">
                                            </td>
                                        </tr>`;

            $("#tableDuration").append(stringHtmlScaleElements);

            iD++;
        });

        $("#removeDuration").on("click", function() {
            if (iD != 0) {
                iD--
                $('#tableDuration tr:last').remove();
            }
        });

        //Package
        const addPackageButton = document.getElementById('addPackage');
        const currentIndexPackage =
            {{ count(old('packages', $form->getPackages())) }};

        let iP = currentIndexPackage;
        addPackageButton.addEventListener('click', function() {

            var stringHtmlScaleElements = `<tr>
                                            <td>
                                                <select class="form-select" name="packages[` + iP +
                `][category_id]" id="packages.` + iP + `.category_id">
                                                    <option selected disabled>Choose Package ` + (iP + 1) + `'s
                                                        Category
                                                    </option>
                                                    @foreach ($form->categories as $category)
                                                        <option value="{{ $category->id }}">
                                                            {{ $category->name }} ({{ $category->code }})</option>
                                                    @endforeach
                                                </select>
                                            </td>
                                            <td>
                                                <textarea class="form-control" id="packages.` + iP +
                `.description" name="packages[` + iP + `][description]"
                                                    placeholder="Enter Package ` + (iP + 1) + ` Description" rows="3"></textarea>
                                            </td>
                                            <td>
                                                <input type="text" class="form-control" id="packages.` + iP +
                `.code" name="packages[` + iP + `][code]"
                                                    placeholder="Enter Package ` + (iP + 1) +
                ` Code">
                                            </td>
                                            <td>
                                                <div class="form-check form-switch form-switch-lg d-flex justify-content-center">
                                                    <input class="form-check-input" type="checkbox" role="switch" name="packages[` +
                iP + `][fullPackage]"
                                                        id="packages.` + iP + `.fullPackage" value=1>
                                                </div>
                                            </td>
                                        </tr>`;

            $("#tablePackage").append(stringHtmlScaleElements);

            iP++;
        });

        $("#removePackage").on("click", function() {
            if (iP != 0) {
                iP--
                $('#tablePackage tr:last').remove();
            }
        });

        //Extra Option
        const addOptionButton = document.getElementById('addOption');
        const currentIndexOption =
            {{ count(old('extra.options', [])) }};

        let iO = currentIndexOption;
        addOptionButton.addEventListener('click', function() {

            var stringHtmlScaleElements = `<tr>
                                            <td>
                                                <input type="text"
                                                    class="form-control"
                                                    name="extra[option][` + iO + `]"
                                                    id="extra.option.` + iO + `"
                                                    placeholder="Extra Option ` + (iO + 1) + `">
                                            </td>
                                        </tr>`;

            $("#tableOptions").append(stringHtmlScaleElements);

            iO++;
        });

        $("#removeOption").on("click", function() {
            if (iO != 0) {
                iO--
                $('#tableOptions tr:last').remove();
            }
        });

        //Hotel
        const addHotelButton = document.getElementById('addHotel');
        const currentIndexHotel =
            {{ count(old('hotels', $form->hotels)) }};

        let iH = currentIndexHotel;
        addHotelButton.addEventListener('click', function() {

            var stringHtmlScaleElements = `<tr>
                                            <td>
                                                <select
                                                    class="form-select"
                                                    name="hotels[` + iH + `][locality]"
                                                    id="hotels.` + iH + `.locality">
                                                    <option selected disabled>Choose Hotel ` + (iH + 1) + ` Locality
                                                    </option>
                                                    @foreach ($localityList as $locality)
                                                        <option value="{{ $locality->code }}">{{ $locality->name }}</option>
                                                    @endforeach
                                                </select>
                                            </td>
                                            <td>
                                                <input type="text"
                                                    class="form-control"
                                                    name="hotels[` + iH + `][description]"
                                                    id="hotels.` + iH + `.description"
                                                    placeholder="Enter Hotel ` + (iH + 1) + ` Description">
                                            </td>
                                            <td>
                                                <input type="text"
                                                    class="form-control"
                                                    name="hotels[` + iH + `][code]"
                                                    id="hotels.` + iH + `.code"
                                                    placeholder="Enter Hotel ` + (iH + 1) + ` Code">
                                            </td>
                                            <td>
                                                <input type="date"
                                                    class="form-control"
                                                    name="hotels[` + iH + `][checkIn]"
                                                    id="hotels.` + iH + `.checkIn">
                                            </td>
                                            <td>
                                                <input type="date"
                                                    class="form-control"
                                                    name="hotels[` + iH + `][checkOut]"
                                                    id="hotels.` + iH + `.checkOut">
                                            </td>
                                        </tr>`;

            $("#tableHotel tbody").append(stringHtmlScaleElements);

            iH++;
        });

        $("#removeHotel").on("click", function() {
            if (iH != 0) {
                iH--
                $('#tableHotel tr:last').remove();
            }
        });

        //Occupancy
        const addOccupancyButton = document.getElementById('addOccupancy');
        const currentIndexOccupancy =
            {{ count(old('occupancies', $form->occupancies)) }};

        let iOC = currentIndexOccupancy;
        addOccupancyButton.addEventListener('click', function() {

            var stringHtmlScaleElements = `<tr>
                                            <td>
                                                <select
                                                    class="form-select"
                                                    name="occupancies[` + iOC + `][locality]"
                                                    id="occupancies.` + iOC + `.locality">
                                                    <option selected disabled>Choose Occupancy ` + (iOC + 1) +  ` Locality
                                                    </option>
                                                    @foreach ($localityList as $locality)
                                                        <option value="{{ $locality->code }}">{{ $locality->name }}</option>
                                                    @endforeach
                                                </select>
                                            </td>
                                            <td>
                                                <input type="text"
                                                    class="form-control"
                                                    name="occupancies[` + iOC + `][type]"
                                                    id="occupancies.` + iOC + `.type"
                                                    placeholder="Enter Occupancy ` + (iOC + 1) +    ` Room Type">
                                            </td>
                                            <td>
                                                <input type="text"
                                                    class="form-control"
                                                    name="occupancies[` + iOC + `][number]"
                                                    id="occupancies.` + iOC + `.number"
                                                    placeholder="Enter Occupancy ` + (iOC + 1) +    ` Number of Person">
                                            </td>
                                            <td>
                                                <input type="date"
                                                    class="form-control"
                                                    name="occupancies[` + iOC + `][bookBefore]"
                                                    id="occupancies.` + iOC + `.bookBefore">
                                            </td>
                                        </tr>`;

            $("#tableOccupancy tbody").append(stringHtmlScaleElements);

            iOC++;
        });

        $("#removeOccupancy").on("click", function() {
            if (iOC != 0) {
                iOC--
                $('#tableOccupancy tr:last').remove();
            }
        });

        @if ($errors->has('categories.*') || $errors->has('durations.*'))
            const updateFormModal = new bootstrap.Modal('#updateFormModal');
            updateFormModal.show();
        @endif

        @if ($errors->has('packages.*'))
            const updateFormModal = new bootstrap.Modal('#updatePackageModal');
            updateFormModal.show();
        @endif

        @if ($errors->has('fee.*'))
            const updateFormModal = new bootstrap.Modal('#modifyFeeModal');
            updateFormModal.show();
        @endif

        @if ($errors->has('extra.*'))
            const updateFormModal = new bootstrap.Modal('#addExtraModal');
            updateFormModal.show();
        @endif

        @if ($errors->has('extraFee.*'))
            const updateFormModal = new bootstrap.Modal('#modifyExtraFeeModal');
            updateFormModal.show();
        @endif

        @if ($errors->has('occupancies.*') || $errors->has('hotels.*'))
            const updateFormModal = new bootstrap.Modal('#modifyHotelModal');
            updateFormModal.show();
        @endif

        @if ($errors->has('rates.*'))
            const updateFormModal = new bootstrap.Modal('#modifyRateModal');
            updateFormModal.show();
        @endif
    </script>
@endsection
