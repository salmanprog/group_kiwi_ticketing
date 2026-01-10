@extends('portal.master')

@section('content')
<section class="main-content py-4">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-8 col-lg-6">
                @include('portal.flash-message')

                <div class="card shadow-sm border-0">
                    <div class="card-body text-center">
                        <div class="alert alert-success mb-4">
                            <h4 class="alert-heading">
                                <i class="fas fa-check-circle text-success"></i> Payment Successful!
                            </h4>
                            <p class="mb-0">
                                Thank you! Your payment for <strong>Invoice #{{ $invoice->invoice_number }}</strong> was successful.
                            </p>
                        </div>

                        <div class="mb-3">
                            <p><strong>Amount Paid:</strong> ${{ number_format($invoice->total, 2) }}</p>
                            <p><strong>Date:</strong> {{ $invoice->paid_at ? $invoice->paid_at->format('F j, Y') : now()->format('F j, Y') }}</p>
                        </div>

                        <div class="d-grid gap-2">
                            <a href="{{ route('invoice.index') }}" class="btn btn-success">
                                <i class="fas fa-home"></i> Back to Invoice
                            </a>
                        </div>
                    </div>
                </div>

                @include('portal.footer')
            </div>
        </div>
    </div>
</section>

@push('scripts')
<script src="{{ asset('admin/assets/lib/summernote/summernote.js') }}"></script>
<script>
    $(function () {
        $('.summernote').summernote({
            height: '400px',
        });
    });
</script>
@endpush
@endsection
