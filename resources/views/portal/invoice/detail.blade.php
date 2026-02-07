@extends('portal.master')
@section('content')

    <style>
        :root {
            --primary-color: #A0C242;
            --primary-dark: #8AA835;
            --primary-light: #E8F4D3;
            --secondary-color: #2C3E50;
            --light-bg: #F8F9FA;
            --border-color: #E0E0E0;
            --text-color: #333333;
            --text-light: #6C757D;
        }

        body {
            font-family: "Poppins", sans-serif !important;
            font-size: 14px;
            color: var(--text-color);
            background-color: #f5f7fa;
            line-height: 1.4;
        }

        /* Mobile First Approach */
        .invoice-wrapper {
            background: #fff;
            padding: 15px;
            border-radius: 8px;
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.05);
            margin-top: 15px;
            border: 1px solid var(--border-color);
            width: 100%;
            overflow-x: hidden;
            box-sizing: border-box;
        }

        .invoice-header {
            border-bottom: 2px solid var(--primary-color);
            padding-bottom: 15px;
            margin-bottom: 20px;
        }

        .invoice-title {
            font-size: 18px;
            font-weight: 700;
            color: #1f2937;
            margin-bottom: 8px;
            line-height: 1.2;
        }

        .invoice-meta {
            display: flex;
            flex-direction: column;
            gap: 10px;
            font-size: 14px;
        }

        .invoice-number {
            background: var(--primary-light);
            padding: 5px 10px;
            border-radius: 15px;
            font-weight: 600;
            color: var(--secondary-color);
            display: inline-block;
            width: fit-content;
        }

        .status {
            display: inline-block;
            padding: 5px 12px;
            border-radius: 15px;
            font-weight: 600;
            color: #fff;
            text-transform: uppercase;
            font-size: 12px;
            width: fit-content;
        }

        .status.unpaid {
            background: #dc3545;
        }

        .status.paid {
            background: #28a745;
        }

        .status.partial {
            background: #ffc107;
            color: #212529;
        }

        .status.draft {
            background: var(--primary-color);
        }

        .installment-alert {
            background: linear-gradient(135deg, #e3f2fd, #bbdefb);
            border: 1px solid #90caf9;
            border-radius: 6px;
            padding: 12px;
            margin-bottom: 20px;
            font-size: 13px;
        }

        .address-section {
            display: flex;
            flex-direction: column;
            gap: 15px;
            margin-bottom: 25px;
        }

        .address-box {
            width: 100%;
            padding: 15px;
            border: 1px solid var(--border-color);
            border-radius: 8px;
            background: linear-gradient(135deg, #f8f9fa, #ffffff);
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.03);
            box-sizing: border-box;
        }

        .address-box h4 {
            color: #1f2937;
            margin-bottom: 12px;
            font-weight: 600;
            border-bottom: 1px solid var(--primary-light);
            padding-bottom: 6px;
            font-size: 16px;
        }

        /* Table Responsiveness */
        .product-table,
        .installment-table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 15px;
            border-radius: 6px;
            overflow: hidden;
            min-width: 500px;
        }

        .product-table th,
        .installment-table th {
            background: #F8F9FA;
            color: var(--secondary-color);
            font-weight: 600;
            padding: 12px 8px;
            text-align: left;
            border: none;
            font-size: 13px;
            white-space: nowrap;
        }

        .product-table td,
        .installment-table td {
            padding: 10px 8px;
            border-bottom: 1px solid var(--border-color);
            font-size: 13px;
        }

        .product-table tr:hover,
        .installment-table tr:hover {
            background-color: var(--light-bg);
        }

        .summary-table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
            background: var(--light-bg);
            border-radius: 6px;
            overflow: hidden;
            font-size: 14px;
        }

        .summary-table th,
        .summary-table td {
            padding: 12px 10px;
            border-bottom: 1px solid var(--border-color);
        }

        .summary-table th {
            color: var(--secondary-color);
            font-weight: 600;
            text-align: left;
        }

        .summary-table td {
            text-align: right;
            font-weight: 500;
        }

        .summary-table tr:last-child {
            background: var(--primary-color);
            color: white;
            font-weight: 700;
        }

        .summary-table tr:last-child td {
            border-bottom: none;
        }

        /* Button Styles for Mobile */
        .btn {
            border-radius: 6px;
            font-weight: 600;
            padding: 10px 15px;
            transition: all 0.3s ease;
            border: none;
            text-decoration: none;
            display: block;
            text-align: center;
            width: 100%;
            margin-bottom: 8px;
            box-sizing: border-box;
            font-size: 14px;
        }

        .btn-success {
            background: #28a745;
            color: white;
        }

        .btn-success:hover {
            background: #218838;
            transform: translateY(-1px);
            color: white;
        }

        .btn-primary {
            background: var(--primary-color);
            color: white;
        }

        .btn-primary:hover {
            background: var(--primary-dark);
            transform: translateY(-1px);
            color: white;
        }

        .btn-sm {
            padding: 6px 10px;
            font-size: 12px;
            width: auto;
        }

        .notes-section {
            background: var(--light-bg);
            padding: 15px;
            border-radius: 6px;
            margin-top: 20px;
            border-left: 3px solid var(--primary-color);
        }

        .notes-section h5 {
            color: var(--primary-color);
            margin-bottom: 12px;
            font-weight: 600;
            font-size: 16px;
        }

        .payment-status {
            padding: 4px 8px;
            border-radius: 12px;
            font-size: 10px;
            font-weight: 600;
            text-transform: uppercase;
            display: inline-block;
        }

        .status-paid {
            background: #d4edda;
            color: #155724;
        }

        .status-unpaid {
            background: #f8d7da;
            color: #721c24;
        }

        .status-cancelled {
            background: #e2e3e5;
            color: #383d41;
        }

        .action-buttons {
            display: flex;
            flex-direction: column;
            gap: 8px;
            margin-top: 20px;
        }

        /* Utility Classes */
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
            color: #fcfcfc !important;
        }

        .text-muted {
            color: var(--text-light);
        }

        /* Table Container for Horizontal Scroll */
        .table-container {
            width: 100%;
            overflow-x: auto;
            -webkit-overflow-scrolling: touch;
            margin-bottom: 15px;
        }

        /* Tablet Styles */
        @media (min-width: 768px) {
            .invoice-wrapper {
                padding: 25px;
                border-radius: 10px;
            }

            .invoice-title {
                font-size: 18px;
            }

            .invoice-meta {
                flex-direction: row;
                align-items: center;
                gap: 15px;
            }

            .address-section {
                flex-direction: row;
            }

            .address-box {
                flex: 1;
                min-width: 280px;
                padding: 20px;
            }

            .action-buttons {
                flex-direction: row;
                justify-content: center;
            }

            .btn {
                width: auto;
                margin-bottom: 0;
            }

            .btn+.btn {
                margin-left: 10px;
            }

            .installment-alert {
                padding: 15px;
                font-size: 14px;
            }

            .notes-section {
                padding: 20px;
            }
        }

        /* Desktop Styles */
        @media (min-width: 992px) {
            .invoice-wrapper {
                padding: 30px;
            }

            .invoice-title {
                font-size: 18px;
            }

            .address-box {
                padding: 20px;
            }

            .product-table th,
            .installment-table th {
                padding: 15px;
                font-size: 14px;
            }

            .product-table td,
            .installment-table td {
                padding: 12px 15px;
                font-size: 14px;
            }

            .summary-table th,
            .summary-table td {
                padding: 15px;
                font-size: 15px;
            }
        }

        /* Small Mobile Optimization */
        @media (max-width: 480px) {
            .invoice-wrapper {
                padding: 12px;
                margin-top: 10px;
            }

            .invoice-title {
                font-size: 18px;
            }

            .address-box {
                padding: 12px;
            }

            .address-box h4 {
                font-size: 15px;
            }

            .btn {
                padding: 8px 12px;
                font-size: 13px;
            }

            .product-table th,
            .product-table td,
            .installment-table th,
            .installment-table td {
                padding: 8px 6px;
                font-size: 12px;
            }

            .summary-table th,
            .summary-table td {
                padding: 10px 8px;
                font-size: 13px;
            }

            .notes-section {
                padding: 12px;
            }

            .notes-section h5 {
                font-size: 15px;
            }

            .installment-alert {
                padding: 10px;
                font-size: 12px;
            }

            .payment-status {
                font-size: 9px;
                padding: 3px 6px;
            }
        }

        /* Fix for very small screens */
        @media (max-width: 360px) {
            .invoice-wrapper {
                padding: 10px;
            }

            .invoice-title {
                font-size: 18px;
            }

            .address-box {
                padding: 10px;
            }

            .btn {
                padding: 8px 10px;
                font-size: 12px;
            }

            .product-table,
            .installment-table {
                min-width: 450px;
            }
        }

        /* Print Styles */
        @media print {
            .invoice-wrapper {
                box-shadow: none;
                border: 1px solid #000;
                padding: 20px;
            }

            .btn,
            .action-buttons {
                display: none !important;
            }

            .product-table,
            .installment-table {
                min-width: auto;
            }
        }

        /* Installment Table Specific Styles */
        .installment-table {
            background: white;
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.03);
        }

        .installment-table td {
            vertical-align: middle;
        }

        /* Status Icons */
        .fas {
            font-size: 0.9em;
        }

        /* Ensure proper text wrapping */
        .address-box p,
        .notes-section p {
            word-wrap: break-word;
            overflow-wrap: break-word;
        }

        /* Improve touch targets for mobile */
        .btn,
        .product-table td,
        .installment-table td {
            -webkit-tap-highlight-color: transparent;
        }

        /* Loading states */
        .btn:active {
            transform: translateY(0);
        }

        /* Focus states for accessibility */
        .btn:focus,
        .form-control:focus {
            outline: 2px solid var(--primary-color);
            outline-offset: 2px;
        }
    </style>

    <section class="main-content">
        <div class="row">
            <div class="col-md-10 offset-md-1">
                <div class="invoice-wrapper">
                    @include('portal.flash-message')

                    {{-- Invoice Header --}}
                    <div class="invoice-header">
                        <div class="invoice-title">
                            Invoice
                        </div>
                        <div class="invoice-meta">
                            <span class="invoice-number">#{{ ucfirst($invoice->invoice_number) }}</span>
                            <span class="status {{ $invoice->status }}">{{ strtoupper($invoice->status) }}</span>
                            <div class="text-muted">
                                <i class="fas fa-calendar me-1"></i>Date:
                                {{ \Carbon\Carbon::parse($invoice->issue_date)->format('F d, Y') }}<br>
                                <i class="fas fa-clock me-1"></i>Valid Until:
                                {{ \Carbon\Carbon::parse($invoice->valid_until)->format('F d, Y') }}
                            </div>
                        </div>
                    </div>

                    @if ($invoice->is_installment)
                        <div class="installment-alert">
                            <i class="fas fa-info-circle me-2"></i>
                            <strong>Installment Plan:</strong> This invoice is on an installment payment plan.
                        </div>
                    @endif

                    {{-- Address Boxes --}}
                    <div class="address-section">
                        <div class="address-box">
                            <h4><i class="fas fa-building me-2"></i>From</h4>
                            <p>
                                <strong>{{ $invoice->company->name ?? 'Company Name' }}</strong><br>
                                {{ $invoice->company->address ?? 'Company Address' }}<br>
                                <i class="fas fa-envelope me-1"></i>Email: {{ $invoice->company->email ?? '-' }}<br>
                                <i class="fas fa-phone me-1"></i>Phone: {{ $invoice->company->mobile_no ?? '-' }}
                            </p>
                        </div>

                        <div class="address-box">
                            <h4><i class="fas fa-user me-2"></i>Invoice To</h4>
                            <p>
                                <strong>{{ $invoice->estimate->organization->name ?? 'Client Name' }}</strong><br>
                                {{ $invoice->estimate->organization->address_one ?? 'Client Address' }}<br>
                                <i class="fas fa-envelope me-1"></i>Email: {{ $invoice->estimate->organization->email ?? '-' }}<br>
                                <i class="fas fa-phone me-1"></i>Phone: {{ $invoice->estimate->organization->phone ?? '-' }}
                            </p>
                        </div>
                    </div>

                    {{-- Product Table --}}
                    <div class="table-container">
                        <!-- <table class="product-table">
                            <thead>
                                <tr>
                                    <th>Description</th>
                                    <th>Qty</th>
                                    <th>Unit Price</th>
                                    <th>Total</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($invoice->invoiceItems ?? [] as $item)
                                    @php
                                        $unitPrice = (float) ($item->price ?? 0);
                                        $qty = (int) ($item->quantity ?? 0);
                                        $lineTotal = $qty * $unitPrice;
                                    @endphp
                                    <tr>
                                        <td>{{ $item->name ?? 'Item' }}</td>
                                        <td>{{ $qty }}</td>
                                        <td>${{ number_format($unitPrice, 2) }}</td>
                                        <td>${{ number_format($lineTotal, 2) }}</td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>

                    {{-- Summary Section (calculated from items/taxes/discounts) --}}
                    @php
                        $summarySubtotal = ($invoice->invoiceItems ?? collect())->sum(fn($item) => (int)($item->quantity ?? 0) * (float)($item->price ?? 0));
                        $summaryTaxTotal = 0;
                        foreach ($invoice->invoiceTax ?? [] as $t) {
                            $summaryTaxTotal += ($t->percent / 100) * $summarySubtotal;
                        }
                        $summaryDiscountTotal = 0;
                        foreach ($invoice->invoiceDiscount ?? [] as $d) {
                            $dType = strtolower((string)($d->type ?? 'fixed'));
                            if ($dType === 'percent' || $dType === 'percentage') {
                                $summaryDiscountTotal += ($d->value / 100) * $summarySubtotal;
                            } else {
                                $summaryDiscountTotal += (float) ($d->value ?? 0);
                            }
                        }
                        $summaryTotal = $summarySubtotal + $summaryTaxTotal - $summaryDiscountTotal;
                    @endphp
                    <table class="summary-table">
                        <tbody>
                            <tr>
                                <th>Subtotal</th>
                                <td>${{ number_format($summarySubtotal, 2) }}</td>
                            </tr>

                            {{-- Taxes --}}
                            @foreach ($invoice->invoiceTax ?? [] as $tax)
                                @php $taxAmount = ($tax->percent / 100) * $summarySubtotal; @endphp
                                <tr>
                                    <th>{{ $tax->name }} ({{ $tax->percent }}%)</th>
                                    <td>${{ number_format($taxAmount, 2) }}</td>
                                </tr>
                            @endforeach

                            {{-- Discounts --}}
                            @foreach ($invoice->invoiceDiscount ?? [] as $discount)
                                @php
                                    $dType = strtolower((string)($discount->type ?? 'fixed'));
                                    $discountAmount = ($dType === 'percent' || $dType === 'percentage')
                                        ? ($discount->value / 100) * $summarySubtotal
                                        : (float) ($discount->value ?? 0);
                                @endphp
                                <tr>
                                    <th>{{ $discount->name ?? 'Discount' }}</th>
                                    <td class="text-danger">– ${{ number_format($discountAmount, 2) }}</td>
                                </tr>
                            @endforeach

                            <tr>
                                <th>Total</th>
                                <td class="font-weight-bold">${{ number_format($summaryTotal, 2) }}</td>
                            </tr>

                            <tr>
                                <th>Amount Paid</th>
                                <td class="text-success">${{ number_format($invoice->total, 2) }}</td>
                            </tr>
                        </tbody>
                    </table> -->
                    <table class="table product-table" id="productTable">
                        <thead>
                            <tr>
                                <th>Product Name</th>
                                <th>Quantity</th>
                                <th>Product Price</th>
                                <th>Total</th>
                            </tr>
                        </thead>
                        <tbody>
                            @php
                                $subtotal = 0;
                                $taxTotal = 0;
                                $discountTotal = 0;
                            @endphp

                            @if($invoice->estimate && $invoice->estimate->items->count())
                                @foreach($invoice->estimate->items as $item)
                                    @php
                                        $subtotal += $item->total_price;

                                        // Sum per-item taxes
                                        if ($item->itemTaxes && $item->itemTaxes->count()) {
                                            foreach ($item->itemTaxes as $tax) {
                                                $taxTotal += round($item->total_price * ($tax->percentage / 100), 2);
                                            }
                                        }
                                    @endphp
                                    <tr data-id="{{ $item->id }}">
                                        <td>
                                            {{ $item->name }}
                                            @if($item->itemTaxes && $item->itemTaxes->count())
                                                <small class="text-muted d-block" data-taxes='[
                                                    @foreach($item->itemTaxes as $tax)
                                                        {"id":{{ $tax->id }},"name":"{{ $tax->name }}","percent":{{ $tax->percentage }}}@if(!$loop->last),@endif
                                                    @endforeach
                                                ]'>
                                                    Apply Taxes:
                                                    @foreach($item->itemTaxes as $tax)
                                                        {{ $tax->name }}@if(!$loop->last), @endif
                                                    @endforeach
                                                </small>
                                            @endif
                                        </td>
                                        <td>{{ $item->quantity }} {{ $item->unit ?? '' }}</td>
                                        <td>${{ number_format($item->price, 2) }}</td>
                                        <td class="item-total">${{ number_format($item->total_price, 2) }}</td>
                                    </tr>
                                @endforeach
                            @else
                                <tr class="no-items">
                                    <td colspan="5" class="text-center">No products added yet.</td>
                                </tr>
                            @endif
                        </tbody>
                        <tfoot>
                            {{-- Subtotal --}}
                            <tr>
                                <th colspan="4" class="">Subtotal:</th>
                                <th id="subtotal">${{ number_format($subtotal, 2) }}</th>
                            </tr>

                            {{-- Taxes --}}
                            @if($invoice->estimate && $invoice->estimate->taxes->count())
                                <tr>
                                    <th colspan="4" class="">Tax:
                                        <div class="d-flex flex-wrap gap-2 justify-content-end">
                                            @foreach($invoice->estimate->taxes as $tax)
                                                <div class="border rounded px-2 py-1 d-flex align-items-center gap-1" data-tax-id="{{ $tax->id }}">
                                                    <small class="fw-semibold">
                                                        {{ $tax->name }} ({{ $tax->percent }}%)
                                                    </small>
                                                </div>
                                            @endforeach
                                        </div>
                                    </th>
                                    <th id="tax_amount">${{ number_format($taxTotal, 2) }}</th>
                                </tr>
                            @endif

                            {{-- Discounts --}}
                            @if($invoice->estimate && $invoice->estimate->discounts->count())
                                <tr class="fw-bold discount-row">
                                    @foreach($invoice->estimate->discounts as $discount)
                                        @php
                                            $discountAmount = strtolower($discount->type) === 'percent'
                                                ? round($subtotal * ($discount->value / 100), 2)
                                                : (float) $discount->value;
                                            $discountTotal += $discountAmount;
                                        @endphp
                                        <th colspan="4" class="">
                                            Discount {{ $discount->name }}
                                        </th>
                                        <th class="discount_percent">
                                            {{ $discount->value }} %
                                        </th>
                                    @endforeach
                                </tr>
                            @endif

                            {{-- Total --}}
                            @php
                                $total = $subtotal + $taxTotal - $discountTotal;
                            @endphp
                            <tr class="fw-bold">
                                <th colspan="4" class="">Total:</th>
                                <th id="total">${{ number_format($total, 2) }}</th>
                            </tr>
                        </tfoot>
                    </table>

                    {{-- Payment Actions --}}
                    @if (Auth::user()->user_type === 'client')
                        @if ($invoice->status === 'unpaid')
                            <div class="action-buttons">
                                {{-- Full Payment --}}
                                <!-- @if (!$invoice->installmentPlan)
                                    <a href="{{ route('invoice.pay', $invoice->slug) }}" class="btn btn-success">
                                        <i class="fas fa-credit-card me-2"></i>Fully Paid
                                    </a>
                                @endif -->
                                

                                {{-- Convert to Installments --}}
                                <!-- @if (!$invoice->installmentPlan) -->
                                    <!-- <a href="{{ route('invoice.convert-to-installment', $invoice->id) }}"
                                        class="btn btn-primary">
                                        <i class="fas fa-calendar-alt me-2"></i>Convert to Installment Plan
                                    </a> -->
                                <!-- @else -->
                                    <p class="text-muted mt-2">
                                        <i class="fas fa-info-circle me-2"></i>Installment plan already created.
                                    </p>
                                <!-- @endif -->
                            </div>
                        @endif
                    @endif

                    {{-- Installment Plan Schedule --}}
                    @if ($invoice->installmentPlan)
                        <h5 class="mt-5" style="color: #1f2937;">
                            <i class="fas fa-calendar-check me-2"></i>Payment Schedule
                        </h5>
                        <div class="table-container">
                            <table class="installment-table">
                                <thead>
                                    <tr>
                                        <th>#</th>
                                        <th>Due Date</th>
                                        <th>Amount</th>
                                        <th>Status</th>
                                        <th>Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($invoice->installmentPlan->payments as $index => $payment)
                                        <tr>
                                            <td>{{ $payment->installment_number }}</td>
                                            <td>
                                                <i class="fas fa-calendar me-2 text-muted"></i>
                                                {{ \Carbon\Carbon::parse($payment->due_date)->format('F d, Y') }}
                                            </td>
                                            <td class="font-weight-bold">${{ number_format($payment->amount, 2) }}</td>
                                            <td>
                                                @if ($payment->is_paid)
                                                    <span class="payment-status status-paid">Paid</span>
                                                @else
                                                    @if (Auth::user()->user_type == 'client')
                                                        @if ($payment->status == 'unpaid')
                                                            <span class="payment-status status-unpaid">Unpaid</span>
                                                        @else
                                                            <span class="payment-status status-cancelled">Cancelled</span>
                                                        @endif
                                                    @else
                                                        <span class="payment-status status-{{ $payment->status }}">
                                                            {{ ucfirst($payment->status) }}
                                                        </span>
                                                    @endif
                                                @endif
                                            </td>
                                            <td>
                                                @if (Auth::user()->user_type == 'client' && !$payment->is_paid && $payment->status == 'unpaid')
                                                    <a href="{{ route('invoice-installment.pay', ['invoice' => encrypt($payment->id)]) }}"
                                                        class="btn btn-primary btn-sm">
                                                        <i class="fas fa-credit-card me-1"></i>Pay Now
                                                    </a>
                                                @elseif($payment->is_paid)
                                                    <span class="text-success">
                                                        <i class="fas fa-check-circle me-1"></i>Paid
                                                    </span>
                                                @endif
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @else
                        @if (!$invoice->installmentPlan)
                            <a href="{{ route('invoice.pay', $invoice->slug) }}" class="btn btn-success">
                                <i class="fas fa-credit-card me-2"></i>Fully Paid
                            </a>
                        @endif
                    @endif

                    {{-- Notes and Terms --}}
                    @if ($invoice->estimate->note)
                        <div class="notes-section note-box">
                            <h5 class="note-title">
                                <i class="fas fa-file-contract me-2"></i>
                                Note
                            </h5>
                            <div class="note-content">
                                {!! $invoice->estimate->note !!}
                            </div>
                        </div>
                    @endif
                    {{-- Notes and Terms --}}
                    @if ($invoice->estimate->terms)
                        <div class="notes-section">
                            @if ($invoice->estimate->terms)
                                <h5><i class="fas fa-sticky-note me-2"></i>Terms And Condtion</h5>
                                <p>{!! $invoice->estimate->terms !!}</p>
                            @endif
                        </div>
                    @endif

                </div>
            </div>
        </div>
    </section>

    
@endsection
