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
                                    <td colspan="2"><img src="{{ $participant->getImageSrc() }}"width="200"
                                            height="200" class="img-fluid rounded-circle mx-auto d-block"
                                            alt="Profile Picture"></td>
                                </tr>
                                <tr>
                                    <th class="w-25">Name</th>
                                    <td>{{ $participant->name }}</td>
                                </tr>
                                <tr>
                                    <th class="w-25">Email</th>
                                    <td>{{ $participant->email }}</td>
                                </tr>
                                <tr>
                                    <th class="w-25">Joined Since</th>
                                    <td>{{ $participant->getJoinedSince() }}</td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
                <div class="tab-pane fade" id="nav-record" role="tabpanel" aria-labelledby="nav-record-tab" tabindex="0">
                    <nav>
                        @foreach ($participant->submissions as $index => $submission)
                            <div class="nav nav-tabs" id="nav-tab" role="tablist">
                                <button class="nav-link active" id="nav-{{ $index }}-tab" data-bs-toggle="tab"
                                    data-bs-target="#nav-{{ $index }}" type="button" role="tab"
                                    aria-controls="nav-{{ $index }}"
                                    aria-selected="true">{{ $submission->form->session->year }}</button>
                            </div>
                        @endforeach
                    </nav>
                    <div class="tab-content pt-3 pb-3" id="nav-tabContent">
                        @foreach ($participant->submissions as $index => $submission)
                            <div class="tab-pane fade show active" id="nav-{{ $index }}" role="tabpanel"
                                aria-labelledby="nav-{{ $index }}-tab" tabindex="0">

                                <div class="table-responsive">
                                    <table class="table table-bordered" id="table_id">
                                        <tbody>
                                            <tr>
                                                <th class='w-25'>Title</th>
                                                <td>{{ $submission->title }}</td>
                                            </tr>
                                            <tr>
                                                <th class='w-25'>Abstract</th>
                                                <td>{{ $submission->abstract }}</td>
                                            </tr>
                                            <tr>
                                                <th class='w-25'>Paper</th>
                                                @if (isset($submission->paper))
                                                    <td>
                                                        <form action="{{ route('admin.member.participant.download') }}"
                                                            method="post" target="_blank">
                                                            @csrf
                                                            <input type="hidden" name="type" value="paper">
                                                            <input type="hidden" name="filename"
                                                                value="{{ $submission->paper }}">
                                                            <button type="submit" class="btn btn-link" name="submission_id"
                                                                value="{{ $submission->id }}">{{ $submission->paper }}</button>
                                                        </form>
                                                    </td>
                                                @else
                                                    <td></td>
                                                @endif
                                            </tr>
                                            <tr>
                                                <th class='w-25'>Status</th>
                                                <td>{{ $submission->getStatusDescription() }}</td>
                                            </tr>
                                            <tr>
                                                <th class="text-center" colspan='2'>Review</th>
                                            </tr>
                                            <tr>
                                                <th class='w-25'>Reviewer</th>
                                                <td>{{ $submission->reviewer->participant->name ?? '' }}</td>
                                            </tr>
                                            <tr>
                                                <th class='w-25'>Mark</th>
                                                <td>{{ $submission->mark === 0 ? '' : $submission->mark }}</td>
                                            </tr>
                                            <tr>
                                                <th class='w-25'>Comment</th>
                                                <td>{!! $submission->comment ?? '' !!}</td>
                                            </tr>
                                            <tr>
                                                <th class='w-25'>Paper with Correction</th>
                                                @if (isset($submission->correction))
                                                    <td>
                                                        <form action="{{ route('admin.member.participant.download') }}"
                                                            method="post" target="_blank">
                                                            @csrf
                                                            <input type="hidden" name="type" value="correction">
                                                            <input type="hidden" name="filename"
                                                                value="{{ $submission->correction }}">
                                                            <button type="submit" class="btn btn-link"
                                                                name="submission_id"
                                                                value="{{ $submission->id }}">{{ $submission->correction }}</button>
                                                        </form>
                                                    </td>
                                                @else
                                                    <td></td>
                                                @endif
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
