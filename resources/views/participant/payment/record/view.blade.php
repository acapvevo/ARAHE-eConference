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
                            <th class="w-25">Registration ID</th>
                            <td>{{ $bill->summary->registration->code }}</td>
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
                            <td>{{ $bill->summary->getLocality()->currency }} {{ number_format($bill->summary->total, 2) }}
                            </td>
                        </tr>
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
                        <tr>
                            <th class="w-25">Receipt</th>
                            <td>
                                @if ($bill->receipt)
                                    <form action="{{ route('participant.payment.record.receipt') }}" method="post"
                                        target="_blank">
                                        @csrf

                                        <button type="submit" class="btn btn-link" value="{{ $bill->id }}"
                                            name="bill_id">{{ $bill->receipt }}</button>
                                    </form>
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
                        <tr>
                            <th class="w-25">Payment Method</th>
                            <td>{{ strtoupper($checkoutSession->payment_intent->payment_method->type ?? '') }}</td>
                        </tr>
                        <tr>
                            <th class="w-25">Payment Link</th>
                            <td>{!! $checkoutSession->url ? '<a href="' . $checkoutSession->url . '" target="_blank">Payment URL</a>' : '' !!}</td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
@endsection

@section('scripts')
@endsection
