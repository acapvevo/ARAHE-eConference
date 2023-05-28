@extends('admin.layouts.app')

@section('styles')
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
    <link rel="stylesheet"
        href="https://cdn.jsdelivr.net/npm/select2-bootstrap-5-theme@1.3.0/dist/select2-bootstrap-5-theme.min.css" />
@endsection

@section('content')
    <h3 class="text-dark mb-1">Packages Managament - Participant Detail</h3>

    <div class="card">
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-bordered">
                    <tbody>
                        <tr>
                            <th class="text-center table-primary" colspan='3'>Packages Details</th>
                        </tr>
                        <tr>
                            <th class='w-25'>Main</th>
                            <td colspan="2">{{ $summary->getPackage()->code }}
                                {{ $summary->getPackage()->fullPackage ? '(FULL PACKAGE)' : '' }}</td>
                        </tr>
                        <tr>
                            <th class='w-25'>Extra</th>
                            <td colspan="2">
                                @if ($summary->extras->isNotEmpty())
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
                                @else
                                    Not Included
                                @endif
                            </td>
                        </tr>
                        <tr>
                            <th class='w-25'>Hotel</th>
                            <td colspan="2">
                                @if ($summary->getPackage()->fullPackage)
                                    Included
                                @elseif ($summary->hotel_id && $summary->occupancy_id)
                                    {{ $summary->getHotel()->code }} - {{ $summary->getOccupancy()->type }}
                                @else
                                    Not Included
                                @endif
                            </td>
                        </tr>
                        <tr>
                            <th class="text-center table-primary" colspan='3'>Registration Details</th>
                        </tr>
                        <tr>
                            <th class='w-25'>Registration ID</th>
                            <td colspan="2">{{ $summary->registration->code }}</td>
                        </tr>
                        <tr>
                            <th class='w-25'>Role</th>
                            <td colspan="2">{{ $summary->registration->getType()->name }}</td>
                        </tr>
                        <tr>
                            <th class='w-25'>Locality</th>
                            <td colspan="2">{{ $summary->registration->category->getLocality()->name }}</td>
                        </tr>
                        <tr>
                            <th class='w-25'>Category</th>
                            <td colspan="2">{{ $summary->registration->category->name }}</td>
                        </tr>
                        @if ($summary->registration->proof)
                            <tr>
                                <th class='w-25'>Proof for Chosen Category</th>
                                <td colspan="2">
                                    <form action="{{ route('admin.submission.package.download') }}" method="post"
                                        target="_blank">
                                        @csrf
                                        <button type="submit" class="btn btn-link" name="summary_id"
                                            value="{{ $summary->registration->id }}">{{ $summary->registration->proof }}</button>
                                    </form>
                                </td>
                            </tr>
                        @endif
                        @if ($summary->registration->link)
                            <tr>
                                <th class='w-25'>Registration Patner</th>
                                <td colspan="2">{{ $summary->registration->linkParticipant->name }}</td>
                            </tr>
                        @endif
                        <tr>
                            <th class='w-25'>Dietary Preference</th>
                            <td colspan="2">{{ $summary->registration->getDietary()->name }}</td>
                        </tr>
                        <tr>
                            <th class='w-25'>Status</th>
                            <td colspan="2">{{ $summary->registration->getStatusDescription() }}</td>
                        </tr>
                        <tr>
                            <th class="text-center table-primary" colspan='3'>Participant</th>
                        </tr>
                        <tr>
                            <th class='w-25'>Name</th>
                            <td colspan="2">{{ $summary->registration->participant->name }}</td>
                        </tr>
                        <tr>
                            <th class='w-25'>Email</th>
                            <td colspan="2">{{ $summary->registration->participant->email }}</td>
                        </tr>
                        <tr>
                            <th class='w-25'>Institution</th>
                            <td colspan="2">{{ $summary->registration->participant->institution->name }}</td>
                        </tr>
                        <tr>
                            <th class='w-25'>Faculty</th>
                            <td colspan="2">{{ $summary->registration->participant->institution->faculty }}</td>
                        </tr>
                        <tr>
                            <th class='w-25'>Department</th>
                            <td colspan="2">{{ $summary->registration->participant->institution->department }}</td>
                        </tr>
                        <tr>
                            <th class='w-25'>Address</th>
                            <td colspan="2">{{ $summary->registration->participant->address->lineOne }},<br>
                                {{ $summary->registration->participant->address->lineTwo }},<br>
                                {!! $summary->registration->participant->address->lineThree
                                    ? $summary->registration->participant->address->lineThree . ',<br>'
                                    : '' !!}
                                {{ $summary->registration->participant->address->postcode }}
                                {{ $summary->registration->participant->address->city }},<br>
                                {{ $summary->registration->participant->address->state }},<br>
                                {{ $summary->registration->participant->address->country }}
                            </td>
                        </tr>
                        <tr>
                            <th class='w-25'>Phone Number</th>
                            <td colspan="2">{{ $summary->registration->participant->contact->phoneNumber }}</td>
                        </tr>
                        <tr>
                            <th class='w-25'>Fax Number</th>
                            <td colspan="2">{{ $summary->registration->participant->contact->faxNumber }}</td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
@endsection

@section('scripts')
@endsection
