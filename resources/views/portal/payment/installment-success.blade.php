@extends('portal.master')

@section('content')
    <section class="main-content py-4">
        <div class="container">
            <div class="row justify-content-center">
                <div class="col-md-8 col-lg-6">

                    {{-- Flash Message --}}
                    @include('portal.flash-message')
                    {{-- Success Card --}}
                    <div class="card shadow-sm border-0">
                        <div class="card-body text-center">

                            {{-- Success Alert --}}
                            <div class="alert alert-success mb-4">
                                <h4 class="alert-heading">
                                    <i class="fas fa-check-circle text-success"></i>
                                    Installment Payment Successful!
                                </h4>
                                <p class="mb-0">
                                    Thank you! Your <strong>installment payment</strong> for
                                    <strong>Invoice #{{ $data['invoice'] }}</strong> was successfully processed.
                                </p>
                            </div>

                            {{-- Installment Payment Details --}}
                            <div class="mb-3">
                                <p>
                                    <strong>Amount Paid:</strong>
                                    ${{ number_format($data['installmentPayment']->amount, 2) }}
                                </p>
                                <p>
                                    <strong>Payment Date:</strong>
                                    {{ $data['installmentPayment']->paid_at
                                        ? $data['installmentPayment']->paid_at->format('F j, Y')
                                        : now()->format('F j, Y') }}
                                </p>
                                @if (!empty($data['installmentPayment']->installment_number))
                                    <p>
                                        <strong>Installment:</strong>
                                        #{{ $data['installmentPayment']->installment_number }}
                                    </p>
                                @endif
                            </div>

                            {{-- Optional Actions --}}
                            <div class="d-grid gap-2">
                                {{-- Example Button (Uncomment if needed) --}}
                                <a href="{{ route('invoice.show', ['invoice' => $data['invoice']]) }}"
                                    class="btn btn-outline-primary btn-primary">
                                    Back to Invoice
                                </a>

                            </div>

                        </div>
                    </div>

                    {{-- Footer --}}
                    @include('portal.footer')

                </div>
            </div>
        </div>
    </section>


    @push('scripts')
        <script src="{{ asset('admin/assets/lib/summernote/summernote.js') }}"></script>
        <script>
            $(function() {
                $('.summernote').summernote({
                    height: '400px',
                });
            });
        </script>

        <style>
            .btn-primary {
                background: #9FC23F !important;
                border: 1px solid #fff !important;
                border-radius: 8px;
                padding: 10px 20px;
                color: white;
                text-decoration: none;
                font-weight: 600;
                transition: all 0.3s ease;
                display: inline-flex;
                align-items: center;
                gap: 8px;
            }

            .btn-primary:hover {
                border: 1px solid #ffffff !important;
                background-color: #8ab02e !important;
            }
        </style>
    @endpush
@endsection
