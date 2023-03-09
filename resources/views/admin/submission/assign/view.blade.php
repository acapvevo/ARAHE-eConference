@extends('admin.layouts.app')

@section('styles')
    <link rel="stylesheet" href="https://cdn.datatables.net/1.12.1/css/dataTables.bootstrap5.min.css">
@endsection

@section('content')
    <h3 class="text-dark mb-1">Assign Reviewer - Submission Detail</h3>

    <div class="card">
        <div class="card-body">
            <div class="row pt-3 pb-3">
                <div class="col d-flex justify-content-end">
                    <button type="button" class="btn btn-primary" data-bs-toggle="modal"
                        data-bs-target="#assignReviewerModal">
                        Assign Reviewer
                    </button>
                    &nbsp;&nbsp;&nbsp;
                    <button type="submit" class="btn btn-danger" form="assignReviewer" value="reject" name="submit">
                        Reject Submission
                    </button>
                </div>
            </div>
            <div class="table-responsive">
                <table class="table table-bordered">
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
                            <td colspan="2">{!! $submission->abstract !!}</td>
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
                        <tr>
                            <th class="text-center table-primary" colspan='3'>Participant</th>
                        </tr>
                        <tr>
                            <th class='w-25'>Name</th>
                            <td colspan="2">{{ $submission->registration->participant->name }}</td>
                        </tr>
                        <tr>
                            <th class='w-25'>Email</th>
                            <td colspan="2">{{ $submission->registration->participant->email }}</td>
                        </tr>
                        <tr>
                            <th class='w-25'>Institution</th>
                            <td colspan="2">{{ $submission->registration->participant->institution->name }}</td>
                        </tr>
                        <tr>
                            <th class='w-25'>Faculty</th>
                            <td colspan="2">{{ $submission->registration->participant->institution->faculty }}</td>
                        </tr>
                        <tr>
                            <th class='w-25'>Department</th>
                            <td colspan="2">{{ $submission->registration->participant->institution->department }}</td>
                        </tr>
                        <tr>
                            <th class='w-25'>Address</th>
                            <td colspan="2">{{ $submission->registration->participant->address->lineOne }},<br>
                                {{ $submission->registration->participant->address->lineTwo }},<br>
                                {!! $submission->registration->participant->address->lineThree ? $submission->registration->participant->address->lineThree . ',<br>' : '' !!}
                                {{ $submission->registration->participant->address->postcode }} {{ $submission->registration->participant->address->city }},<br>
                                {{ $submission->registration->participant->address->state }},<br>
                                {{ $submission->registration->participant->address->country}}
                            </td>
                        </tr>
                        <tr>
                            <th class='w-25'>Phone Number</th>
                            <td colspan="2">{{ $submission->registration->participant->contact->phoneNumber }}</td>
                        </tr>
                        <tr>
                            <th class='w-25'>Fax Number</th>
                            <td colspan="2">{{ $submission->registration->participant->contact->faxNumber }}</td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <div class="modal fade" id="assignReviewerModal" tabindex="-1" aria-labelledby="assignReviewerModalLabel"
        aria-hidden="true">
        <div class="modal-dialog modal-xl">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title fs-5" id="assignReviewerModalLabel">Assign Reviewer</h4>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">

                    @error('reviewer_id')
                    <div class="card text-bg-danger mb-3">
                      <div class="card-body">
                        <p class="card-text text-white">Please Choose a Reviewer</p>
                      </div>
                    </div>
                    <div class="pt-3 pb-3"></div>
                    @enderror

                    <form action="{{ route('admin.submission.assign.update', ['id' => $submission->id]) }}" method="post"
                        id="assignReviewer">
                        @csrf
                        @method('PATCH')

                        <div class="table-responsive">
                            <table class="table table-bordered" id="table_id">
                                <thead class="table-primary">
                                    <tr>
                                        <th style="width:5%">#</th>
                                        <th>Reviewer</th>
                                        <th style="width:15%">No. of Submission Assigned for
                                            ARAHE{{ $submission->registration->form->session->year }}</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($reviewers as $index => $reviewer)
                                        @php
                                            $record = $reviewer->records->first(function ($record) use ($submission) {
                                                return $record->form_id == $submission->registration->form_id;
                                            });
                                        @endphp

                                        <tr>
                                            <td>
                                                <div class="form-check">
                                                    <input class="form-check-input" type="radio" name="reviewer_id"
                                                        id="reviewer_id{{ $index }}" value="{{ $reviewer->id }}">
                                                </div>
                                            </td>
                                            <td>{{ $reviewer->participant->name }}</td>
                                            <td class="text-center">{{ $record->assign }}</td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>

                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                    <button type="submit" class="btn btn-primary" form="assignReviewer" name="submit"
                        value="assign">Assign</button>
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
                "autoWidth": false,
                "columns": [{
                        "width": "5%"
                    },
                    null,
                    {
                        "width": "30%"
                    }
                ]
            });
        });
    </script>
@endsection
