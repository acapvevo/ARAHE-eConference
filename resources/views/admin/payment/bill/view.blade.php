@extends('admin.layouts.app')

@section('styles')
@endsection

@section('content')
    <h3 class="text-dark mb-1">Payment Management - Bill Detail</h3>

    <div class="card">
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-bordered" id="table_id">
                    <tbody>
                        <tr>
                            <th class="w-25">Participant</th>
                            <td>{{ $bill->submission->participant->name }}</td>
                        </tr>
                        <tr>
                            <th class="w-25">Date Attempt</th>
                            <td>{{ $bill->getPayAttemptAt() }}</td>
                        </tr>
                        <tr>
                            <th class="w-25">Date Complete</th>
                            <td>{{ $bill->getPayCompleteAt() }}</td>
                        </tr>
                        <tr>
                            <th class="w-25">Amount Paid</th>
                            <td>RM {{ number_format($bill->amount / 100, 2) }}</td>
                        </tr>
                        <tr>
                            <th class="w-25">Status</th>
                            <td>{{ $bill->getStatusPayment()->description }}</td>
                        </tr>
                        <tr>
                            <th colspan="2" class="text-center">ToyyibPay Infomation</th>
                        </tr>
                        <tr>
                            <th class="w-25">Bill Name</th>
                            <td>{{ $infoToyyibPay->billName ?? '' }}</td>
                        </tr>
                        <tr>
                            <th class="w-25">Bill Description</th>
                            <td>{{ $infoToyyibPay->billDescription ?? '' }}</td>
                        </tr>
                        <tr>
                            <th class="w-25">Bill Payment Channel</th>
                            <td>{{ $infoToyyibPay->billpaymentChannel ?? '' }}</td>
                        </tr>
                        <tr>
                            <th class="w-25">Bill Payment Invoice Number</th>
                            <td>{{ $infoToyyibPay->billpaymentInvoiceNo ?? '' }}</td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
@endsection

@section('scripts')
@endsection
