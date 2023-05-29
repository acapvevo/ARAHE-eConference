@extends('admin.layouts.app')

@section('styles')
    <link rel="stylesheet" href="https://cdn.datatables.net/1.12.1/css/dataTables.bootstrap5.min.css">
@endsection

@section('content')
    <h3 class="text-dark mb-1">Registration - Participant List</h3>

    <div class="card">
        <h4 class="card-header text-center">
            ARAHE {{ $form->session->year }}
        </h4>
        <div class="card-body">
            <div class="pt-3 pb-3 d-grid gap-2 d-md-flex justify-content-md-end">
                <form action="{{ route('admin.submission.registration.export') }}" method="post">
                    @csrf
                    <button type="submit" class="btn btn-primary" name="form_id" value="{{ $form->id }}">
                        Export</button>
                </form>
            </div>
            <nav>
                <div class="nav nav-tabs nav-justified" id="nav-tab" role="tablist">
                    <button class="nav-link active" id="nav-registration-tab" data-bs-toggle="tab"
                        data-bs-target="#nav-registration" type="button" role="tab" aria-controls="nav-registration"
                        aria-selected="true">Registrations</button>
                    <button class="nav-link" id="nav-package-tab" data-bs-toggle="tab" data-bs-target="#nav-package"
                        type="button" role="tab" aria-controls="nav-package" aria-selected="false">Packages</button>
                </div>
            </nav>
            <div class="tab-content pt-3" id="nav-tabContent">
                <div class="tab-pane fade show active" id="nav-registration" role="tabpanel"
                    aria-labelledby="nav-registration-tab" tabindex="0">

                    <div class="table-responsive">
                        <table class="table table-bordered" id="table_id">
                            <thead class="table-primary">
                                <tr>
                                    <th>Participant</th>
                                    <th>Registration ID</th>
                                    <th>Register As</th>
                                    <th>Status</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($registrations as $registration)
                                    <tr>
                                        <td style="width:30%"><a
                                                href="{{ route('admin.submission.registration.view', ['id' => $registration->id]) }}">{{ $registration->participant->name }}</a>
                                        </td>
                                        <td>{{ $registration->code }}</td>
                                        <td>{{ $registration->getType()->name }}</td>
                                        <td>{{ $registration->getStatusLabel() }}</td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>

                </div>
                <div class="tab-pane fade" id="nav-package" role="tabpanel" aria-labelledby="nav-package-tab"
                    tabindex="0">

                    <nav>
                        <div class="nav nav-pills nav-justified" id="nav-pills" role="tablist">
                            <button class="nav-link active" id="nav-fullPackage-tab" data-bs-toggle="tab"
                                data-bs-target="#nav-fullPackage" type="button" role="tab"
                                aria-controls="nav-fullPackage" aria-selected="true">Package</button>
                            <button class="nav-link" id="nav-accom-tab" data-bs-toggle="tab" data-bs-target="#nav-accom"
                                type="button" role="tab" aria-controls="nav-accom"
                                aria-selected="false">Accomadation</button>
                            <button class="nav-link" id="nav-extra-tab" data-bs-toggle="tab" data-bs-target="#nav-extra"
                                type="button" role="tab" aria-controls="nav-extra" aria-selected="false">Extra</button>
                        </div>
                    </nav>
                    <div class="tab-content pt-3" id="nav-tabContent">
                        <div class="tab-pane fade show active" id="nav-fullPackage" role="tabpanel"
                            aria-labelledby="nav-fullPackage-tab" tabindex="0">

                            <div class="table-responsive">
                                <table class="table table-bordered" id="full_table">
                                    <thead class="table-primary">
                                        <tr>
                                            <th>Registration ID</th>
                                            <th>Participant</th>
                                            <th>Package</th>
                                            <th>Extras</th>
                                            <th>Hotel</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($summaries as $summary)
                                            <tr>
                                                <td style="width:15%"><a
                                                        href="{{ route('admin.submission.registration.view', ['id' => $summary->id]) }}">{{ $summary->registration->code }}</a>
                                                </td>
                                                <td style="width:25%">{{ $summary->registration->participant->name }}</td>
                                                <td style="width:5%">{{ $summary->getPackage()->code }}</td>
                                                <td>
                                                    <ul>
                                                        @foreach ($summary->extras as $extra)
                                                            @php
                                                                $extraInfo = DB::table('extras')
                                                                    ->where('id', $extra['id'])
                                                                    ->first();
                                                                $options = collect(json_decode($extraInfo->options));
                                                            @endphp
                                                            <li> {{ $extraInfo->description }}
                                                                {{ $options->isNotEmpty() ? ' - ' . $options[$extra['option']] : '' }}
                                                            </li>
                                                        @endforeach
                                                    </ul>
                                                </td>
                                                @if ($summary->getPackage()->fullPackage)
                                                    <td colspan="2">Included in Package
                                                        {{ $summary->getPackage()->code }}</td>
                                                @else
                                                    <td>{{ $summary->getHotel()->code }} - {{ $summary->getOccupancy()->type }}</td>
                                                @endif
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        </div>
                        <div class="tab-pane fade" id="nav-accom" role="tabpanel" aria-labelledby="nav-accom-tab"
                            tabindex="0">

                            <div class="table-responsive">
                                <table class="table table-bordered" id="accom_table">
                                    <thead class="table-primary">
                                        <tr>
                                            <th>Registration ID</th>
                                            <th>Participant</th>
                                            <th>Code</th>
                                            <th>Occupancy</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($acommadationSummaries as $summary)
                                            <tr>
                                                <td style="width:15%"><a
                                                        href="{{ route('admin.submission.registration.view', ['id' => $summary->id]) }}">{{ $summary->registration->code }}</a>
                                                </td>
                                                <td style="width:25%">{{ $summary->registration->participant->name }}</td>
                                                @if ($summary->getPackage()->fullPackage)
                                                    <td colspan="2">Included in Package
                                                        {{ $summary->getPackage()->code }}</td>
                                                    <td style="display: none"></td>
                                                @else
                                                    <td>{{ $summary->getHotel()->code }}</td>
                                                    <td>{{ $summary->getOccupancy()->type }}</td>
                                                @endif
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        </div>
                        <div class="tab-pane fade" id="nav-extra" role="tabpanel" aria-labelledby="nav-extra-tab"
                            tabindex="0">

                            <div class="table-responsive">
                                <table class="table table-bordered" id="extra_table">
                                    <thead class="table-primary">
                                        <tr>
                                            <th>Registration ID</th>
                                            <th>Participant</th>
                                            <th>Extras</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($extraSummaries as $summary)
                                            <tr>
                                                <td style="width:15%"><a
                                                        href="{{ route('admin.submission.registration.view', ['id' => $summary->id]) }}">{{ $summary->registration->code }}</a>
                                                </td>
                                                <td style="width:25%">{{ $summary->registration->participant->name }}</td>
                                                <td>
                                                    <ul>
                                                        @foreach ($summary->extras as $extra)
                                                            @php
                                                                $extraInfo = DB::table('extras')
                                                                    ->where('id', $extra['id'])
                                                                    ->first();
                                                                $options = collect(json_decode($extraInfo->options));
                                                            @endphp
                                                            <li> {{ $extraInfo->description }}
                                                                {{ $options->isNotEmpty() ? ' - ' . $options[$extra['option']] : '' }}
                                                            </li>
                                                        @endforeach
                                                    </ul>
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>

                </div>
            </div>
        </div>
    </div>
@endsection

@section('scripts')
    <script src="https://cdn.datatables.net/1.12.1/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/1.12.1/js/dataTables.bootstrap5.min.js"></script>

    <script>
        $(document).ready(function() {
            $('#table_id').DataTable({
                "order": [],
                "autoWidth": false,
                "columns": [{
                        "width": "5%"
                    }, {
                        "width": "40%"
                    },
                    null,
                    null
                ]
            });

            $('#full_table').DataTable({
                "order": [],
            });
            $('#accom_table').DataTable({
                "order": [],
            });
            $('#extra_table').DataTable({
                "order": [],
            });
        });
    </script>
@endsection
