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
    </style>

    <section class="main-content">
        <div class="row">
            <div class="col-md-10 offset-md-1">
                <div class="invoice-wrapper">
                    {{-- Action Buttons --}}
                    <div class="mt-4 text-center">
                        @if ($estimate->status === 'sent' || $estimate->status === 'revised')
                            @if(Auth::user()->user_type == 'client')

                            <form action="{{ route('estimates.accept', $estimate->id) }}" method="POST"
                                class="d-inline-block me-2">
                                <input type="hidden" name="slug" value="{{ $estimate->slug }}">
                                @csrf
                                <button type="submit" class="btn btn-primary">
                                    <i class="fas fa-check-circle me-1"></i>
                                    Accept & Create Invoice
                                </button>
                            </form>

                            <form action="{{ route('estimates.reject', $estimate->id) }}" method="POST"
                                class="d-inline-block">
                                <input type="hidden" name="slug" value="{{ $estimate->slug }}">
                                @csrf
                                <button type="submit" class="btn btn-outline-danger">
                                    <i class="fas fa-times-circle me-1"></i>
                                    Reject Estimate
                                </button>
                            </form>
                            @endif

                        @else
                            <p class="text-muted"><em>This estimate has already been {{ $estimate->status }}.</em></p>

                        @endif
                    </div>



                    {{-- Invoice Header --}}
                    <div class="invoice-header">
                        <div class="invoice-title">Estimate</div>
                        <div class="invoice-meta">
                            <strong>#{{ $estimate->estimate_number }}</strong> —
                            <span class="status">
                                    @if($estimate->status == "sent")
                                        @if($estimate->status == "sent")
                                            <span class="status sent">New</span>
                                        @endif
                                    @else
                                         {{strtoupper($estimate->status)}} {{($estimate->is_adjusted == '1') ? '( + Adjusted)' : ''}}
                                    @endif
                            </span><br>
                            Date: {{ \Carbon\Carbon::parse($estimate->issue_date)->format('F d, Y') }}<br>
                            Valid Until: {{ \Carbon\Carbon::parse($estimate->valid_until)->format('F d, Y') }}
                            @if($estimate->invoices)
                            @if($estimate->invoices->status == "unpaid" && Auth::user()->user_type != 'client')

                                <a href="{{ route('estimate.edit', ['estimate' => $estimate->slug]) }}"><i class="fas fa-pencil"></i> Edit</a>
                                
                            @endif
                            @endif

                        </div>
                    </div>

                    {{-- Address Boxes --}}
                    <div class="address-section">
                        <div class="address-box">
                            <h4>From</h4>
                            <p>
                                <strong>{{ $estimate->company->name ?? 'Company Name' }}</strong><br>
                                {{ $estimate->company->address ?? 'Company Address' }}<br>
                                Email: {{ $estimate->company->email ?? '-' }}<br>
                                Phone: {{ $estimate->company->phone ?? '-' }}
                            </p>
                        </div>

                        <div class="address-box">
                            <h4>Invoice To</h4>
                            <p>
                                <strong>{{ $estimate->client->name ?? 'Client Name' }}</strong><br>
                                {{ $estimate->client->address ?? 'Client Address' }}<br>
                                Email: {{ $estimate->client->email ?? '-' }}<br>
                                Phone: {{ $estimate->client->phone ?? '-' }}
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
                            @foreach ($estimate->items as $item)
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
                                <td class="text-right">${{ number_format($estimate->subtotal, 2) }}</td>
                            </tr>

                            {{-- Taxes --}}
                            @foreach ($estimate->taxes as $tax)
                                <tr>
                                    <th class="bg-light">{{ $tax->name }} ({{ $tax->percent }}%)</th>
                                    <td class="text-right">
                                        ${{ number_format(($tax->percent / 100) * $estimate->subtotal, 2) }}</td>
                                </tr>
                            @endforeach
                            
                            {{-- Discounts --}}
                            @if($estimate->discounts)
                                @foreach ($estimate->discounts as $discount)
                                    <tr>
                                        <th class="bg-light">{{ $discount->name }}</th>
                                        <td class="text-right text-danger">– ${{ number_format($discount->value, 2) }}</td>
                                    </tr>
                                @endforeach
                            @endif

                            <tr>
                                <th class="bg-light font-weight-bold">Total</th>
                                <td class="text-right font-weight-bold">${{ number_format($estimate->total, 2) }}</td>
                            </tr>

                            <tr>
                                <th class="bg-light">Amount Paid</th>
                                <td class="text-right text-success">${{ number_format($estimate->total, 2) }}</td>
                            </tr>


                        </tbody>
                    </table>

                    {{-- Notes and Terms --}}
                    @if ($estimate->note || $estimate->terms)
                        <div class="mt-4">
                            @if ($estimate->note)
                                <h5>Note</h5>
                                <p>{{ $estimate->note }}</p>
                            @endif
                            @if ($estimate->terms)
                                <h5>Terms</h5>
                                <p>{{ $estimate->terms }}</p>
                            @endif
                        </div>
                    @endif

                </div>
            </div>
        </div>
    </section>

@endsection
