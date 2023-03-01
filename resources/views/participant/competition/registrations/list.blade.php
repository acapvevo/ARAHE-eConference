@extends('participant.layouts.app')

@section('styles')
    <link rel="stylesheet" href="https://cdn.datatables.net/1.12.1/css/dataTables.bootstrap5.min.css">
@endsection

@section('content')
    <h3 class="text-dark mb-1">Registration - Form List</h3>

    <div class="card">
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-bordered" id="table_id">
                    <thead class="table-primary">
                        <tr>
                            <th>Year</th>
                            <th>Registration ID</th>
                            <th>Status</th>
                            <th style="width: 30%">Regitration Period</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($forms as $form)
                            <tr>
                                <td>
                                    @if (!$form->session->isRegistrationOpen() && !isset($form->registration))
                                        {{ $form->session->year }}
                                    @else
                                        <a
                                            href="{{ route('participant.competition.registration.view', ['form_id' => $form->id]) }}">{{ $form->session->year }}</a>
                                    @endif
                                </td>
                                <td>{{ isset($form->registration) ? $form->registration->code : '' }}</td>
                                <td>
                                    @if ($form->session->isRegistrationOpen() && !isset($form->registration))
                                        Not Registered Yet
                                    @elseif (!$form->session->isRegistrationOpen() && !isset($form->registration))
                                        Registration Closed
                                    @else
                                    {{ $form->registration->getStatusLabel() }}
                                    @endif
                                </td>
                                <td>{{ $form->session->returnDateString('registration', 'start') }} -
                                    {{ $form->session->returnDateString('registration', 'end') }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
@endsection

@section('scripts')
    <script src="https://cdn.datatables.net/1.12.1/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/1.12.1/js/dataTables.bootstrap5.min.js"></script>

    <script>
        $(document).ready(function() {
            $('#table_id').DataTable();
        });
    </script>
@endsection
