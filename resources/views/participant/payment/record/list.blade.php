@extends('participant.layouts.app')

@section('styles')
    <link rel="stylesheet" href="https://cdn.datatables.net/1.12.1/css/dataTables.bootstrap5.min.css">
@endsection

@section('content')
    <h3 class="text-dark mb-1">Payment Record - Bill List</h3>

    <div class="card">
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-bordered" id="table_id">
                    <thead class="table-primary">
                        <tr>
                            <th>Year</th>
                            <th>Date Attempt</th>
                            <th>Date Completed</th>
                            <th>Amount</th>
                            <th>Status</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($bills as $bill)
                            <tr>
                                <td><a
                                        href="{{ route('participant.payment.record.view', ['id' => $bill->id]) }}">{{ $bill->submission->form->session->year }}</a>
                                </td>
                                <td>{{ $bill->getPayAttemptAt() }}</td>
                                <td>{{ $bill->getPayCompleteAt() }}</td>
                                <td>RM {{number_format($bill->amount/100, 2)}}</td>
                                <td>{{$bill->getStatusPayment()->label}}</td>
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
