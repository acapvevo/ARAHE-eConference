@extends('participant.layouts.app')

@section('styles')
@endsection

@section('content')
    <h3 class="text-dark mb-1">Payment Record - Bill Detail</h3>

    <div class="card">
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-bordered" id="table_id">
                    <tbody>
                        <tr>
                            <th class="w-25">Year</th>
                            <td>{{ $bill->submission->form->session->year }}</td>
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
                            <th class="w-25">Payment Link</th>
                            <td><a href="{{ $paymentLink }}" target="_blank">{{ $paymentLink }}</a></td>
                        </tr>
                        <tr>
                            <th colspan="2" class="text-center">ToyyibPay Infomation</th>
                        </tr>
                        <tr>
                            <th class="w-25">Bill Name</th>
                            <td>{{ $infoToyyibpay->billName ?? '' }}</td>
                        </tr>
                        <tr>
                            <th class="w-25">Bill Description</th>
                            <td>{{ $infoToyyibpay->billDescription ?? '' }}</td>
                        </tr>
                        <tr>
                            <th class="w-25">Bill Payment Channel</th>
                            <td>{{ $infoToyyibpay->billpaymentChannel ?? '' }}</td>
                        </tr>
                        <tr>
                            <th class="w-25">Bill Payment Invoice Number</th>
                            <td>{{ $infoToyyibpay->billpaymentInvoiceNo ?? '' }}</td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
@endsection

@section('scripts')
@endsection
