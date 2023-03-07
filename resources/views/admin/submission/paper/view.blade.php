@extends('admin.layouts.app')

@section('styles')
@endsection

@section('content')
    <h3 class="text-dark mb-1">Paper - Submission Detail</h3>

    <div class="card">
        <div class="card-body">
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
                                <td colspan="2"></td>
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
                                    <form action="{{ route('admin.submission.paper.download') }}" method="post"
                                        target="_blank">
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
                                    <form action="{{ route('admin.submission.paper.download') }}" method="post"
                                        target="_blank">
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
                            <th class="text-center table-primary" colspan='3'>Review</th>
                        </tr>
                        <tr>
                            <th class='w-25'>Reviewer</th>
                            <td colspan="2">{{ $submission->reviewer->participant->name ?? '' }}</td>
                        </tr>
                        <tr>
                            <th class='w-25'>Total Mark</th>
                            <td colspan="2">
                                {{ $submission->calculatePercentage() === 0 ? '' : number_format($submission->calculatePercentage(), 2) . '%' }}
                            </td>
                        </tr>
                        <tr>
                            <th class='w-25'>Comment</th>
                            <td colspan="2">{!! $submission->comment ?? '' !!}</td>
                        </tr>
                        <tr>
                            <th class='w-25'>Paper with Correction</th>
                            @if (isset($submission->correctionFile))
                                <td colspan="2">
                                    <form action="{{ route('admin.submission.paper.download') }}" method="post"
                                        target="_blank">
                                        @csrf
                                        <input type="hidden" name="type" value="correctionFile">
                                        <input type="hidden" name="filename" value="{{ $submission->correctionFile }}">
                                        <button type="submit" class="btn btn-link" name="submission_id"
                                            value="{{ $submission->id }}">{{ $submission->correctionFile }}</button>
                                    </form>
                                </td>
                            @else
                                <td colspan="2"></td>
                            @endif
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
@endsection

@section('scripts')
@endsection
