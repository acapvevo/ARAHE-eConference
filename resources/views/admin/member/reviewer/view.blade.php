@extends('admin.layouts.app')

@section('styles')
    <link rel="stylesheet" href="https://cdn.datatables.net/1.12.1/css/dataTables.bootstrap5.min.css">
@endsection

@section('content')
    <h3 class="text-dark mb-1">Participant Management - Participant Detail</h3>

    <div class="card">
        <div class="card-body">
            <nav>
                <div class="nav nav-tabs nav-justified" id="nav-maintab" role="tablist">
                    <button class="nav-link active" id="nav-profile-tab" data-bs-toggle="tab" data-bs-target="#nav-profile"
                        type="button" role="tab" aria-controls="nav-profile" aria-selected="false">Profile</button>
                    <button class="nav-link" id="nav-record-tab" data-bs-toggle="tab" data-bs-target="#nav-record"
                        type="button" role="tab" aria-controls="nav-record" aria-selected="true">Submission Review
                        Record</button>
                </div>
            </nav>
            <div class="tab-content pt-3 pb-3" id="nav-tabContent">
                <div class="tab-pane fade show active" id="nav-profile" role="tabpanel" aria-labelledby="nav-profile-tab"
                    tabindex="0">
                    <div class="row pb-3">
                        <div class="col">
                            <form action="{{ route('admin.member.reviewer.update', ['id' => $reviewer->id]) }}"
                                method="post">
                                @csrf
                                @method('PATCH')

                                <button type="submit"
                                    class="btn btn-{{ $reviewer->active ? 'danger' : 'success' }} float-end"
                                    value="{{ $reviewer->active ? 'inactive' : 'active' }}" name="submit">Set to
                                    {{ $reviewer->active ? 'Inactive' : 'Active' }}</button>
                            </form>
                        </div>
                    </div>
                    <div class="table-responsive">
                        <table class="table table-bordered">
                            <tbody>
                                <tr>
                                    <th class="w-25">Active Status</th>
                                    <td>{{ $reviewer->active ? 'Active' : 'Inactive' }}</td>
                                </tr>
                                <tr>
                                    <th colspan="2" class="text-center"><strong>Account Details</strong></th>
                                </tr>
                                <tr>
                                    <th class="w-25">Title: </th>
                                    <td>{{ $reviewer->participant->getTitle() }}</td>
                                </tr>
                                <tr>
                                    <th class="w-25">Name: </th>
                                    <td>{{ $reviewer->participant->name }}</td>
                                </tr>
                                <tr>
                                    <th class="w-25">Date of Birth: </th>
                                    <td>{{ $reviewer->participant->date_of_birth->format('j F Y') }}</td>
                                </tr>
                                <tr>
                                    <th class="w-25">Email: </th>
                                    <td>{{ $reviewer->participant->email }}</td>
                                </tr>

                                <tr>
                                    <th colspan="2" class="text-center"><strong>Institution</strong></th>
                                </tr>
                                <tr>
                                    <th class="w-25">University: </th>
                                    <td>{{ $reviewer->participant->institution->name }}</td>
                                </tr>
                                <tr>
                                    <th class="w-25">Faculty: </th>
                                    <td>{{ $reviewer->participant->institution->faculty }}</td>
                                </tr>
                                <tr>
                                    <th class="w-25">Department: </th>
                                    <td>{{ $reviewer->participant->institution->department }}</td>
                                </tr>


                                <tr>
                                    <th colspan="2" class="text-center"><strong>Address</strong></th>
                                </tr>
                                <tr>
                                    <th class="w-25">Line 1: </th>
                                    <td>{{ $reviewer->participant->address->lineOne }}</td>
                                </tr>
                                <tr>
                                    <th class="w-25">Line 2: </th>
                                    <td>{{ $reviewer->participant->address->lineTwo }}</td>
                                </tr>
                                <tr>
                                    <th class="w-25">Line 3: </th>
                                    <td>{{ $reviewer->participant->address->lineThree }}</td>
                                </tr>
                                <tr>
                                    <th class="w-25">City: </th>
                                    <td>{{ $reviewer->participant->address->city }}</td>
                                </tr>
                                <tr>
                                    <th class="w-25">Postcode: </th>
                                    <td>{{ $reviewer->participant->address->postcode }}</td>
                                </tr>
                                <tr>
                                    <th class="w-25">State: </th>
                                    <td>{{ $reviewer->participant->address->state }}</td>
                                </tr>
                                <tr>
                                    <th class="w-25">Country: </th>
                                    <td>{{ $reviewer->participant->address->country }}</td>
                                </tr>

                                <tr>
                                    <th colspan="2" class="text-center"><strong>Contact</strong></th>
                                </tr>
                                <tr>
                                    <th class="w-25">Phone Number: </th>
                                    <td>{{ $reviewer->participant->contact->phoneNumber }}</td>
                                </tr>
                                <tr>
                                    <th class="w-25">Fax Number: </th>
                                    <td>{{ $reviewer->participant->contact->faxNumber }}</td>
                                </tr>

                                <tr>
                                    <th colspan="2" class="text-center"><strong>Emergency Person Details</strong></th>
                                </tr>
                                <tr>
                                    <th class="w-25">Name: </th>
                                    <td>{{ $reviewer->participant->emergency->name }}</td>
                                </tr>
                                <tr>
                                    <th class="w-25">Email: </th>
                                    <td>{{ $reviewer->participant->emergency->email }}</td>
                                </tr>
                                <tr>
                                    <th class="w-25">Phone Number: </th>
                                    <td>{{ $reviewer->participant->emergency->phoneNumber }}</td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
                <div class="tab-pane fade" id="nav-record" role="tabpanel" aria-labelledby="nav-record-tab" tabindex="0">
                    <div class="table-responsive">
                        <table class="table table-bordered" id="table_id">
                            <thead class="table-primary">
                                <tr>
                                    <th>Year</th>
                                    <th>Participant</th>
                                    <th>Title</th>
                                    <th>Status</th>
                                </tr>
                            </thead>
                            @php
                                $submissions = $reviewer->submissions->sortByDesc(function ($submission) {
                                    return $submission->registration->form->session->year;
                                });
                            @endphp
                            <tbody>
                                @foreach ($submissions as $submission)
                                    <tr>
                                        <td>{{ $submission->registration->form->session->year }}</td>
                                        <td>{{ $submission->registration->participant->name }}</td>
                                        <td>{{ $submission->title }}</td>
                                        <td>{{ $submission->getStatusLabel() }}</td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
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
                    {
                        "width": "15%"
                    }
                ]
            });
        });
    </script>
@endsection
