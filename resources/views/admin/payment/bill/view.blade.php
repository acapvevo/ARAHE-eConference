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
                            <th class="w-25">Name</th>
                            <td>{{ $bill->summary->registration->participant->name }}</td>
                        </tr>
                        <tr>
                            <th class="w-25">Date and Time Payment Attempt</th>
                            <td>{{ $bill->getPayAttemptAt() }}</td>
                        </tr>
                        <tr>
                            <th class="w-25">Date and Time Payment Complete</th>
                            <td>{{ $bill->getPayCompleteAt() }}</td>
                        </tr>
                        <tr>
                            <th class="w-25">Amount Paid</th>
                            <td>{{ $bill->getCurrency() }} {{ number_format($bill->summary->total, 2) }}
                            </td>
                        </tr>
                        <tr>
                            <th class="w-25">Method</th>
                            <td>{{ $bill->getPaymentMethod() }}
                            </td>
                        </tr>
                        <tr>
                            <th class="w-25">Receipt</th>
                            <td>
                                @if ($bill->receipt)
                                    <form action="{{ route('admin.payment.bill.download') }}" method="post">
                                        @csrf
                                        <input type="hidden" name="id" value="{{ $bill->id }}">
                                        <button type="submit" class="btn btn-link" name="attribute"
                                            value="receipt" formtarget="_blank">{{ $bill->receipt }}</button>
                                    </form>
                                @endif
                            </td>
                        </tr>
                        @if ($bill->proof)
                            <tr>
                                <th class="w-25">Payment Proof</th>
                                <td>
                                    <form action="{{ route('admin.payment.bill.download') }}" method="post">
                                        @csrf
                                        <input type="hidden" name="id" value="{{ $bill->id }}">
                                        <button type="submit" class="btn btn-link" name="attribute"
                                            value="proof" formtarget="_blank">{{ $bill->proof }}</button>
                                    </form>
                                </td>
                                </td>
                            </tr>
                        @endif
                        <tr>
                            <th class="w-25">Status</th>
                            <td>
                                @if ($bill->status == 2)
                                    {{ $bill->getStatusPayment()->description }}
                                    <strong>{{ $bill->getPayExpiredAt() }}</strong>
                                @else
                                    {{ $bill->getStatusPayment()->description }}
                                @endif
                            </td>
                        </tr>
                        @if ($bill->checkoutSession_id)
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
                        @endif
                    </tbody>
                </table>
            </div>
        </div>
    </div>
@endsection

@section('scripts')
@endsection
