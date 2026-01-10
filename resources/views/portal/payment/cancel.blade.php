@extends('portal.master')
@section('content')

<div class="container mt-5">
    <div class="alert alert-warning text-center">
        <h4>Payment Cancelled</h4>
        <p>You have cancelled the payment for Invoice <strong>#{{ $invoice->invoice_number }}</strong>.</p>
        <a href="{{ route('invoice.pay', $invoice->slug) }}" class="btn btn-primary mt-3">
            Try Again
        </a>
    </div>
</div>

@endsection
