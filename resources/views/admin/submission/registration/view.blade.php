@extends('admin.layouts.app')

@section('styles')
@endsection

@section('content')
    <h3 class="text-dark mb-1">Paper - Submission Detail</h3>

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
@endsection

@section('scripts')
@endsection
