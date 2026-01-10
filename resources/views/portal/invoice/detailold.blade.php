@extends('portal.master')
@section('content')

    <style>
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            font-size: 14px;
            color: #333;
        }

        .invoice-wrapper {
            background: #fff;
            padding: 30px;
            border: 1px solid #ddd;
            border-radius: 6px;
            margin-top: 20px;
        }

        .invoice-header {
            border-bottom: 2px solid #007bff;
            padding-bottom: 20px;
            margin-bottom: 30px;
        }

        .invoice-title {
            font-size: 32px;
            font-weight: bold;
            color: #007bff;
        }

        .invoice-meta {
            font-size: 16px;
            color: #666;
            margin-top: 10px;
        }

        .status {
            display: inline-block;
            padding: 4px 10px;
            border-radius: 4px;
            font-weight: 600;
            color: #fff;
            background: #28a745;
            text-transform: uppercase;
        }

        .address-section {
            display: flex;
            justify-content: space-between;
            flex-wrap: wrap;
            margin-bottom: 30px;
        }

        .address-box {
            flex: 1;
            min-width: 260px;
            padding: 15px;
            border: 1px solid #ccc;
            border-radius: 6px;
            background-color: #f9f9f9;
        }

        .address-box h4 {
            color: #007bff;
            margin-bottom: 10px;
        }

        table.product-table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
        }

        .product-table th,
        .product-table td {
            border: 1px solid #ccc;
            padding: 10px;
            text-align: left;
        }

        .product-table th {
            background-color: #f5f5f5;
        }

        .summary-table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 30px;
        }

        .summary-table th,
        .summary-table td {
            padding: 12px;
            font-size: 15px;
            border: 1px solid #ccc;
        }

        .summary-table th.bg-light {
            background-color: #f8f9fa;
        }

        .text-right {
            text-align: right;
        }

        .font-weight-bold {
            font-weight: 700;
        }

        .text-danger {
            color: #dc3545;
        }

        .text-success {
            color: #28a745;
        }

        .text-muted {
            color: #6c757d;
        }

        .btn + .btn {
            margin-left: 10px;
        }

        .table {
            margin-top: 20px;
        }
    </style>

    <section class="main-content">
        <div class="row">
            <div class="col-md-10 offset-md-1">
                <div class="invoice-wrapper">
                    @include('portal.flash-message')
                    {{-- Invoice Header --}}
                    <div class="invoice-header">
                        <div class="invoice-title">Invoice</div>
                        <div class="invoice-meta">
                            <strong>#{{ ucfirst($invoice->invoice_number) }}</strong> —
                            <span class="status">{{ strtoupper($invoice->status) }}</span><br>
                            Date: {{ \Carbon\Carbon::parse($invoice->issue_date)->format('F d, Y') }}<br>
                            Valid Until: {{ \Carbon\Carbon::parse($invoice->valid_until)->format('F d, Y') }}
                        </div>
                    </div>
                    @if($invoice->is_installment)
                        <div class="alert alert-info">
                            This invoice is on an installment plan.
                        </div>
                    @endif

                    {{-- Address Boxes --}}
                    <div class="address-section">
                        <div class="address-box">
                            <h4>From</h4>
                            <p>
                                <strong>{{ $invoice->company->name ?? 'Company Name' }}</strong><br>
                                {{ $invoice->company->address ?? 'Company Address' }}<br>
                                Email: {{ $invoice->company->email ?? '-' }}<br>
                                Phone: {{ $invoice->company->mobile_no ?? '-' }}
                            </p>
                        </div>

                        <div class="address-box">
                            <h4>Invoice To</h4>
                            <p>
                                <strong>{{ $invoice->client->first_name ?? 'Client Name' }}</strong><br>
                                Email: {{ $invoice->client->email ?? '-' }}<br>
                                Phone: {{ $invoice->client->mobile_no ?? '-' }}
                            </p>
                        </div>
                    </div>

                    {{-- Product Table --}}
                    <table class="product-table">
                        <thead>
                            <tr>
                                <th>Description</th>
                                <th>Qty</th>
                                <th>Unit Price</th>
                                <th>Total</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($invoice->invoiceItems as $item)
                                <tr>
                                    <td>{{ $item->name ?? 'Item' }}</td>
                                    <td>{{ $item->quantity }}</td>
                                    <td>${{ number_format($item->price, 2) }}</td>
                                    <td>${{ number_format($item->total_price, 2) }}</td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>

                    {{-- Summary Section --}}
                    <table class="summary-table mt-4">
                        <tbody>
                            <tr>
                                <th class="bg-light">Subtotal</th>
                                <td class="text-right">${{ number_format($invoice->subtotal, 2) }}</td>
                            </tr>

                            {{-- Taxes --}}
                            @foreach ($invoice->invoiceTax as $tax)
                                <tr>
                                    <th class="bg-light">{{ $tax->name }} ({{ $tax->percent }}%)</th>
                                    <td class="text-right">
                                        ${{ number_format(($tax->percent / 100) * $invoice->subtotal, 2) }}</td>
                                </tr>
                            @endforeach

                            {{-- Discounts --}}
                            @foreach ($invoice->invoiceDiscount as $discount)
                                <tr>
                                    <th class="bg-light">{{ $discount->name }}</th>
                                    <td class="text-right text-danger">– ${{ number_format($discount->value, 2) }}</td>
                                </tr>
                            @endforeach

                            <tr>
                                <th class="bg-light font-weight-bold">Total</th>
                                <td class="text-right font-weight-bold">${{ number_format($invoice->total, 2) }}</td>
                            </tr>

                            <tr>
                                <th class="bg-light">Amount Paid</th>
                                <td class="text-right text-success">${{ number_format($invoice->total, 2) }}</td>
                            </tr>
                        </tbody>
                    </table>

                    {{-- Notes and Terms --}}
                    @if ($invoice->note || $invoice->terms)
                        <div class="mt-4">
                            @if ($invoice->note)
                                <h5>Note</h5>
                                <p>{{ $invoice->note }}</p>
                            @endif
                            @if ($invoice->terms)
                                <h5>Terms</h5>
                                <p>{{ $invoice->terms }}</p>
                            @endif
                        </div>
                    @endif

                    {{-- Payment Actions --}}
                    @if($invoice->status === 'unpaid')
                        <div class="mt-4 text-center">
                            {{-- Full Payment --}}
                            @if(!$invoice->installmentPlan)
                                <a href="{{ route('invoice.pay', $invoice->slug) }}" type="button" class="btn btn-success">Fully Paid</button>
                            @endif
                            {{-- Convert to Installments --}}

                            @if(!$invoice->installmentPlan)
                                <a href="{{ route('invoice.convert-to-installment', $invoice->id) }}" class="btn btn-primary ml-2">
                                    Convert to Installment Plan
                                </a>
                            @else
                                <p class="text-muted mt-2">Installment plan already created.</p>
                            @endif
                        </div>
                    @endif

                    {{-- Installment Plan Schedule --}}
                    @if($invoice->installmentPlan)
                        <h5 class="mt-5">Installment Schedule</h5>
                        <table class="table table-bordered mt-2">
                            <thead>
                                <tr>
                                    <th>#</th>
                                    <th>Due Date</th>
                                    <th>Amount</th>
                                    <th>Status</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($invoice->installmentPlan->payments as $index => $payment)
                                    <tr>
                                        <td>{{ $payment->installment_number }}</td>
                                        <td>{{ \Carbon\Carbon::parse($payment->due_date)->format('F d, Y') }}</td>
                                        <td>${{ number_format($payment->amount, 2) }}</td>
                                        <td>
                                            @if($payment->is_paid)
                                                <span class="text-success">Paid</span>
                                            @else
                                                @if(Auth::user()->user_type == 'client')
                                                        @if($payment->status == 'unpaid')
                                                            <a href="{{ route('invoice-installment.pay', ['invoice' => encrypt($payment->id)]) }}" type="button" class="btn btn-primary">Pay Now</button>
                                                        @else
                                                            <span class="text-danger">Canclled</span>
                                                        @endif
                                                @else
                                                    <span class="text-danger">{{ ucfirst($payment->status) }}</span>
                                                @endif
                                            @endif
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    @endif

                </div>
            </div>
        </div>
    </section>

@endsection
