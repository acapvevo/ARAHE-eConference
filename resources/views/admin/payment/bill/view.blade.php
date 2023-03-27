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
                            <th class="w-25">Registration ID</th>
                            <td>{{ $bill->summary->registration->code }}</td>
                        </tr>
                        <tr>
                            <th class="w-25">Registration ID</th>
                            <td>{{ $bill->summary->registration->participant->name }}</td>
                        </tr>
                        <tr>
                            <th class="w-25">Date and Time Payment Attempt</th>
                            <td class="date">{{ $bill->getPayAttemptAt() }}</td>
                        </tr>
                        <tr>
                            <th class="w-25">Date and Time Payment Complete</th>
                            <td class="date">{{ $bill->getPayCompleteAt() }}</td>
                        </tr>
                        <tr>
                            <th class="w-25">Amount Paid</th>
                            <td>{{ $bill->summary->getLocality()->currency }} {{ number_format($bill->summary->total, 2) }}
                            </td>
                        </tr>
                        <tr>
                            <th class="w-25">Status</th>
                            <td>
                                @if ($bill->status == 2)
                                    {{ $bill->getStatusPayment()->description }}
                                    <strong class="date">{{ $bill->getPayExpiredAt() }}</strong>
                                @else
                                    {{ $bill->getStatusPayment()->description }}
                                @endif
                            </td>
                        </tr>
                        <tr>
                            <th colspan="2" class="text-center">Stripe Infomation</th>
                        </tr>
                        <tr>
                            <th class="w-25">Bill Status</th>
                            <td>{{ strtoupper($checkoutSession->status ?? '') }}</td>
                        </tr>
                        <tr>
                            <th class="w-25">Payment Status</th>
                            <td>{{ strtoupper($checkoutSession->payment_status ?? '') }}</td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
@endsection

@section('scripts')
@endsection
