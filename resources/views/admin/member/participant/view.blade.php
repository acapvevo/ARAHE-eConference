@extends('admin.layouts.app')

@section('styles')
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
                        type="button" role="tab" aria-controls="nav-record" aria-selected="true">Participation
                        Record</button>
                </div>
            </nav>
            <div class="tab-content pt-3 pb-3" id="nav-tabContent">
                <div class="tab-pane fade show active" id="nav-profile" role="tabpanel" aria-labelledby="nav-profile-tab"
                    tabindex="0">
                    <div class="row pb-3">
                        <div class="col">
                            <form action="{{ route('admin.member.participant.update', ['id' => $participant->id]) }}"
                                method="post">
                                @csrf
                                @method('PATCH')

                                <button type="submit" class="btn btn-primary float-end" value="reviewer"
                                    name="submit" @disabled(isset($participant->reviewer))>Hire as Reviewer</button>
                            </form>
                        </div>
                    </div>
                    <div class="table-responsive">
                        <table class="table table-bordered">
                            <tbody>
                                <tr>
                                    <th colspan="2" class="text-center"><strong>Account Details</strong></th>
                                </tr>
                                <tr>
                                    <th class="w-25">Title: </th>
                                    <td>{{ $participant->getTitle() }}</td>
                                </tr>
                                <tr>
                                    <th class="w-25">Name: </th>
                                    <td>{{ $participant->name }}</td>
                                </tr>
                                <tr>
                                    <th class="w-25">Date of Birth: </th>
                                    <td>{{ $participant->date_of_birth->format('j F Y') }}</td>
                                </tr>
                                <tr>
                                    <th class="w-25">Type of Participation: </th>
                                    <td>{{ $participant->getType() }}</td>
                                </tr>
                                <tr>
                                    <th class="w-25">Email: </th>
                                    <td>{{ $participant->email }}</td>
                                </tr>

                                <tr>
                                    <th colspan="2" class="text-center"><strong>Institution</strong></th>
                                </tr>
                                <tr>
                                    <th class="w-25">University: </th>
                                    <td>{{ $participant->institution->name }}</td>
                                </tr>
                                <tr>
                                    <th class="w-25">Faculty: </th>
                                    <td>{{ $participant->institution->faculty }}</td>
                                </tr>
                                <tr>
                                    <th class="w-25">Department: </th>
                                    <td>{{ $participant->institution->department }}</td>
                                </tr>


                                <tr>
                                    <th colspan="2" class="text-center"><strong>Address</strong></th>
                                </tr>
                                <tr>
                                    <th class="w-25">Line 1: </th>
                                    <td>{{ $participant->address->lineOne }}</td>
                                </tr>
                                <tr>
                                    <th class="w-25">Line 2: </th>
                                    <td>{{ $participant->address->lineTwo }}</td>
                                </tr>
                                <tr>
                                    <th class="w-25">Line 3: </th>
                                    <td>{{ $participant->address->lineThree }}</td>
                                </tr>
                                <tr>
                                    <th class="w-25">City: </th>
                                    <td>{{ $participant->address->city }}</td>
                                </tr>
                                <tr>
                                    <th class="w-25">Postcode: </th>
                                    <td>{{ $participant->address->postcode }}</td>
                                </tr>
                                <tr>
                                    <th class="w-25">State: </th>
                                    <td>{{ $participant->address->state }}</td>
                                </tr>
                                <tr>
                                    <th class="w-25">Country: </th>
                                    <td>{{ $participant->address->country }}</td>
                                </tr>

                                <tr>
                                    <th colspan="2" class="text-center"><strong>Contact</strong></th>
                                </tr>
                                <tr>
                                    <th class="w-25">Phone Number: </th>
                                    <td>{{ $participant->contact->phoneNumber }}</td>
                                </tr>
                                <tr>
                                    <th class="w-25">Fax Number: </th>
                                    <td>{{ $participant->contact->faxNumber }}</td>
                                </tr>

                                <tr>
                                    <th colspan="2" class="text-center"><strong>Emergency Person Details</strong></th>
                                </tr>
                                <tr>
                                    <th class="w-25">Name: </th>
                                    <td>{{ $participant->emergency->name }}</td>
                                </tr>
                                <tr>
                                    <th class="w-25">Email: </th>
                                    <td>{{ $participant->emergency->email }}</td>
                                </tr>
                                <tr>
                                    <th class="w-25">Phone Number: </th>
                                    <td>{{ $participant->emergency->phoneNumber }}</td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
                <div class="tab-pane fade" id="nav-record" role="tabpanel" aria-labelledby="nav-record-tab" tabindex="0">
                    <nav>
                        @foreach ($participant->getSubmissions() as $index => $submission)
                            <div class="nav nav-tabs" id="nav-tab" role="tablist">
                                <button class="nav-link active" id="nav-{{ $index }}-tab" data-bs-toggle="tab"
                                    data-bs-target="#nav-{{ $index }}" type="button" role="tab"
                                    aria-controls="nav-{{ $index }}"
                                    aria-selected="true">{{ $submission->registration->form->session->year }}</button>
                            </div>
                        @endforeach
                    </nav>
                    <div class="tab-content pt-3 pb-3" id="nav-tabContent">
                        @foreach ($participant->getSubmissions() as $index => $submission)
                            <div class="tab-pane fade show active" id="nav-{{ $index }}" role="tabpanel"
                                aria-labelledby="nav-{{ $index }}-tab" tabindex="0">

                                <div class="table-responsive">
                                    <table class="table table-bordered" id="table_id">
                                        <tbody>
                                            <tr>
                                                <th class='w-25'>Registration ID</th>
                                                <td colspan="2">{{ $submission->registration->code }}</td>
                                            </tr>
                                            <tr>
                                                <th class='w-25'>Title</th>
                                                <td colspan="2">{{ $submission->title }}</td>
                                            </tr>
                                            @forelse ($submission->authors ?? [] as $index => $author)
                                                <tr>
                                                    @if ($index == 0)
                                                        <th class='w-25' rowspan="{{ $submission->authors->count() }}">Authors</th>
                                                    @endif
                                                    <td>{{ $author['name'] }}</td>
                                                    <td>{{ $author['email'] }}</td>
                                                </tr>
                                            @empty
                                                <tr>
                                                    <th class='w-25'>Authors</th>
                                                    <td  colspan="2"></td>
                                                </tr>
                                            @endforelse
                                            @forelse ($submission->coAuthors ?? [] as $index => $coAuthor)
                                                <tr>
                                                    @if ($index == 0)
                                                        <th class='w-25' rowspan="{{ $submission->coAuthors->count() }}">Co-Authors</th>
                                                    @endif
                                                    <td>{{ $coAuthor['name'] }}</td>
                                                    <td>{{ $coAuthor['email'] }}</td>
                                                </tr>
                                            @empty
                                                <tr>
                                                    <th class='w-25'>Co-Authors</th>
                                                    <td colspan="2"></td>
                                                </tr>
                                            @endforelse
                                            <tr>
                                                <th class='w-25'>Presenter</th>
                                                <td colspan="2">{{ $submission->presenter }}</td>
                                            </tr>
                                            <tr>
                                                <th class='w-25'>Abstract</th>
                                                <td colspan="2">{{ $submission->abstract }}</td>
                                            </tr>
                                            <tr>
                                                <th class='w-25'>Abstract File</th>
                                                @if (isset($submission->abstractFile))
                                                    <td colspan="2">
                                                        <form action="{{ route('admin.submission.assign.download') }}"
                                                            method="post" target="_blank">
                                                            @csrf
                                                            <input type="hidden" name="type" value="abstractFile">
                                                            <input type="hidden" name="filename" value="{{ $submission->abstractFile }}">
                                                            <button type="submit" class="btn btn-link" name="submission_id"
                                                                value="{{ $submission->id }}">{{ $submission->abstractFile }}</button>
                                                        </form>
                                                    </td>
                                                @else
                                                    <td colspan="2"></td>
                                                @endif
                                            </tr>
                                            <tr>
                                                <th class='w-25'>Keywords</th>
                                                <td colspan="2">{{ $submission->keywords }}</td>
                                            </tr>
                                            <tr>
                                                <th class='w-25'>Paper File</th>
                                                @if (isset($submission->paperFile))
                                                    <td colspan="2">
                                                        <form action="{{ route('admin.submission.assign.download') }}"
                                                            method="post" target="_blank">
                                                            @csrf
                                                            <input type="hidden" name="type" value="paperFile">
                                                            <input type="hidden" name="filename" value="{{ $submission->paperFile }}">
                                                            <button type="submit" class="btn btn-link" name="submission_id"
                                                                value="{{ $submission->id }}">{{ $submission->paperFile }}</button>
                                                        </form>
                                                    </td>
                                                @else
                                                    <td colspan="2"></td>
                                                @endif
                                            </tr>
                                            <tr>
                                                <th class='w-25'>Status</th>
                                                <td colspan="2">{{ $submission->getStatusDescription() }}</td>
                                            </tr>
                                        </tbody>
                                    </table>
                                </div>

                            </div>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('scripts')
@endsection
