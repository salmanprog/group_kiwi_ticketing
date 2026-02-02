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
            background-color: #f5f7fa;
            color: var(--text-color);
            font-size: 14px !important;
            line-height: 1.4;
        }

        /* Mobile First Approach */
        .estimate-wrapper {
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

        .estimate-header {
            border-bottom: 2px solid var(--primary-color);
            padding-bottom: 15px;
            margin-bottom: 20px;
        }

        .estimate-title {
            font-size: 18px;
            font-weight: 700;
            color: #1f2937;
            margin-bottom: 8px;
            line-height: 1.2;
        }

        .estimate-meta {
            display: flex;
            flex-direction: column;
            gap: 10px;
            font-size: 14px;
        }

        .estimate-number {
            background: var(--primary-light);
            padding: 5px 10px;
            border-radius: 15px;
            font-weight: 600;
            color: var(--secondary-color);
            display: inline-block;
            width: fit-content;
        }

        .status {
            padding: 5px 12px;
            border-radius: 15px;
            font-size: 12px;
            font-weight: 600;
            display: inline-block;
            width: fit-content;
        }

        .status.draft {
            background-color: var(--primary-color);
            color: #fff;
        }

        .status.sent {
            background-color: #36a3f7;
            color: #fff;
        }

        .status.approved {
            background-color: #28a745;
            color: #fff;
        }

        .status.rejected {
            background-color: #dc3545;
            color: #fff;
        }

        .status.revised {
            background-color: #ffc107;
            color: #212529;
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
            margin-bottom: 12px;
            color: #1f2937;
            font-weight: 600;
            border-bottom: 1px solid var(--primary-light);
            padding-bottom: 6px;
            font-size: 16px;
        }

        .form-section {
            margin-top: 20px;
        }

        .form-row {
            display: flex;
            flex-direction: column;
            gap: 15px;
        }

        .form-group {
            width: 100%;
        }

        label {
            font-weight: 600;
            margin-bottom: 6px;
            color: var(--secondary-color);
            display: block;
            font-size: 14px;
        }

        .form-control {
            border: 1px solid var(--border-color);
            border-radius: 6px;
            padding: 10px;
            transition: all 0.3s ease;
            width: 100%;
            box-sizing: border-box;
            font-size: 14px;
        }

        .form-control:focus {
            border-color: var(--primary-color);
            box-shadow: 0 0 0 0.2rem rgba(160, 194, 66, 0.25);
        }

        .print-value {
            display: none;
            font-size: 14px;
            padding: 8px;
            background: var(--light-bg);
            border-radius: 5px;
            color: var(--text-color);
            font-weight: 600;
            margin-top: 6px;
            border: 1px solid var(--border-color);
        }

        /* Table Responsiveness */
        .forref {
            width: 100%;
            overflow-x: auto;
            -webkit-overflow-scrolling: touch;
        }

        .product-table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 15px;
            border-radius: 6px;
            overflow: hidden;
            min-width: 600px;
        }

        .product-table th {
            background: #F7FAFC !important;
            color: var(--secondary-color);
            font-weight: 600;
            padding: 12px 8px;
            text-align: left;
            border: none;
            font-size: 13px;
            white-space: nowrap;
        }

        .product-table td {
            padding: 10px 8px;
            border-bottom: 1px solid var(--border-color);
            vertical-align: middle;
            font-size: 13px;
        }

        .product-table tr:hover {
            background-color: var(--light-bg);
        }

        /* Button Styles for Mobile */
        .btn {
            border-radius: 6px;
            font-weight: 600;
            padding: 10px 15px;
            transition: all 0.3s ease;
            border: none;
            font-size: 14px;
            width: 100%;
            margin-bottom: 8px;
            box-sizing: border-box;
            text-align: center;
            display: block;
        }

        .btn-primary {
            background: var(--primary-color);
            color: white;
        }

        .btn-primary:hover {
            background: var(--primary-dark);
            transform: translateY(-1px);
        }

        .btn-success {
            background: #28a745;
            color: white;
        }

        .btn-success:hover {
            background: #218838;
            transform: translateY(-1px);
        }

        .btn-info {
            background: #17a2b8;
            color: white;
        }

        .btn-info:hover {
            background: #138496;
            transform: translateY(-1px);
        }

        .btn-warning {
            background: #ffc107;
            color: #212529;
        }

        .btn-warning:hover {
            background: #e0a800;
            transform: translateY(-1px);
        }

        .btn-danger {
            background: #dc3545;
            color: white;
        }

        .btn-danger:hover {
            background: #c82333;
            transform: translateY(-1px);
        }

        .btn-sm {
            padding: 6px 10px;
            font-size: 12px;
            width: auto;
        }

        .action-buttons {
            display: flex;
            flex-direction: column;
            gap: 8px;
            margin: 15px 0;
        }

        /* Summary Table */
        .summary-table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 15px;
            background: var(--light-bg);
            border-radius: 6px;
            overflow: hidden;
            font-size: 14px;
        }

        .summary-table th,
        .summary-table td {
            padding: 10px 12px;
            border-bottom: 1px solid var(--border-color);
        }

        .summary-table th {
            background: var(--primary-light);
            color: var(--secondary-color);
            font-weight: 600;
            text-align: left;
        }

        .summary-table tr:last-child {
            background: var(--primary-color);
            color: white;
            font-weight: 700;
        }

        .summary-table tr:last-child td {
            border-bottom: none;
        }

        .tax-row,
        .discount-entry {
            background: var(--light-bg);
            padding: 8px 12px;
            border-radius: 5px;
            margin-bottom: 8px;
            border-left: 3px solid var(--primary-color);
            font-size: 13px;
        }

        /* Activity Section */
        .activity-section {
            background: white;
            border-radius: 8px;
            padding: 15px;
            margin-top: 20px;
            border: 1px solid #E0E0E0;
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.03);
            width: 100%;
            box-sizing: border-box;
        }

        .section-header {
            color: #1f2937;
            font-weight: 600;
            font-size: 16px;
            margin-bottom: 15px;
            padding-bottom: 8px;
        }

        .activity-table-container {
            max-height: 300px;
            overflow-y: auto;
            border: 1px solid #E0E0E0;
            border-radius: 6px;
            width: 100%;
            overflow-x: auto;
        }

        .activity-table {
            width: 100%;
            border-collapse: collapse;
            background: white;
            font-size: 13px;
            min-width: 500px;
        }

        .activity-table th {
            background: #F7FAFC;
            color: #2C3E50;
            font-weight: 600;
            padding: 10px 8px;
            text-align: left;
            border-bottom: 1px solid #E0E0E0;
            position: sticky;
            top: 0;
            z-index: 10;
            font-size: 13px;
        }

        .activity-table td {
            padding: 8px;
            border-bottom: 1px solid #f0f0f0;
            color: #555;
            font-size: 13px;
        }

        .activity-table tr:hover {
            background-color: #f8f9fa;
        }

        .activity-table tr:last-child td {
            border-bottom: none;
        }

        /* Badge Styles */
        .badge {
            display: inline-block;
            padding: 3px 6px;
            border-radius: 3px;
            font-size: 10px;
            font-weight: 600;
            text-transform: uppercase;
            color: white;
        }

        /* Modal Styles */
        .modal-header {
            background: linear-gradient(135deg, var(--primary-light), #f0f7e4);
            border-bottom: 1px solid var(--primary-color);
            padding: 12px 15px;
        }

        .modal-title {
            color: var(--secondary-color);
            font-weight: 600;
            font-size: 16px;
        }

        .modal-content {
            border-radius: 8px;
            border: none;
            box-shadow: 0 5px 20px rgba(0, 0, 0, 0.1);
        }

        /* Print Styles */
        @media print {

            input,
            select,
            textarea,
            .btn,
            .select2,
            label,
            form button,
            .no-print {
                display: none !important;
            }

            .print-value {
                display: block !important;
            }

            .estimate-wrapper,
            .address-box,
            table,
            th,
            td {
                border: 1px solid #000 !important;
                background-color: #fff !important;
            }

            th {
                background-color: #eee !important;
                -webkit-print-color-adjust: exact;
            }

            .main-content {
                padding: 0;
                margin: 0;
            }

            @page {
                margin: 15mm;
            }

            .estimate-title {
                color: #000 !important;
            }
        }

        /* Tablet Styles */
        @media (min-width: 768px) {
            .estimate-wrapper {
                padding: 25px;
                border-radius: 10px;
            }

            .estimate-title {
                font-size: 18px;
            }

            .estimate-meta {
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

            .form-row {
                flex-direction: row;
            }

            .form-group {
                flex: 1;
                min-width: 200px;
            }

            .action-buttons {
                flex-direction: row;
            }

            .btn {
                width: auto;
                margin-bottom: 0;
            }

            .activity-section {
                padding: 20px;
            }
        }

        /* Desktop Styles */
        @media (min-width: 992px) {
            .estimate-wrapper {
                padding: 30px;
            }

            .estimate-title {
                font-size: 18px;
            }

            .address-box {
                padding: 25px;
            }

            .activity-section {
                max-width: 1330.5px;
                margin-inline: auto;
                padding: 25px;
            }

            .section-header {
                font-size: 18px;
            }
        }

        /* Small Mobile Optimization */
        @media (max-width: 480px) {
            .estimate-wrapper {
                padding: 12px;
                margin-top: 10px;
            }

            .estimate-title {
                font-size: 18px;
            }

            .address-box {
                padding: 12px;
            }

            .address-box h4 {
                font-size: 15px;
            }

            .form-control {
                padding: 8px;
                font-size: 13px;
            }

            .btn {
                padding: 8px 12px;
                font-size: 13px;
            }

            .product-table th,
            .product-table td {
                padding: 8px 6px;
                font-size: 12px;
            }

            .summary-table th,
            .summary-table td {
                padding: 8px 10px;
                font-size: 13px;
            }

            .activity-section {
                padding: 12px;
            }

            .activity-table th,
            .activity-table td {
                padding: 6px 4px;
                font-size: 12px;
            }
        }

        /* Fix for very small screens */
        @media (max-width: 360px) {
            .estimate-wrapper {
                padding: 10px;
            }

            .estimate-title {
                font-size: 18px;
            }

            .address-box {
                padding: 10px;
            }

            .btn {
                padding: 8px 10px;
                font-size: 12px;
            }

            .product-table {
                min-width: 500px;
            }
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
            color: #ffffff !important;
        }

        .text-muted {
            color: var(--text-light);
        }

        .cust-bd {
            border: 1px solid #A0C242;
        }

        .cust-main-table {
            margin-left: unset !important;
            width: 100% !important;
        }

        .theme-bg {
            background-color: #A0C242 !important;
        }

        .theme-text {
            color: #A0C242 !important;
        }

        .theme-border {
            border-color: #A0C242 !important;
        }

        .theme-table thead {
            background-color: #A0C242;
            color: white;
        }

        .theme-table {
            border: 1px solid #A0C242;
        }

        .table-bordered {
            border: 1px solid #A0C242;
        }

        .table-bordered th,
        .table-bordered td {
            border: 1px solid #dee2e6;
        }

        .theme-badge {
            background-color: #A0C242;
            color: white;
        }

        /* Modal specific styles */
        .modal-header {
            background-color: #A0C242;
            color: white;
            border-bottom: none;
        }

        .modal-title {
            font-weight: 600;
        }

        .modal-header .btn-close {
            color: #000;
        }

        .modal-header .btn-close:hover {
            opacity: 1;
        }

        .table-hover tbody tr:hover {
            background-color: var(--primary-light);
        }

        .deleted-alert {
            background: linear-gradient(135deg, rgb(253, 239, 227), rgb(251, 210, 187));
            border: 1px solid #dc3545;
            border-radius: 6px;
            padding: 12px;
            margin-bottom: 20px;
            font-size: 13px;
        }
    </style>
<meta name="csrf-token" content="{{ csrf_token() }}">
<section class="main-content">
<div class="row">
    <div class="col-md-10 offset-md-1">
        @include('portal.flash-message')

        <div class="estimate-wrapper">
            {{-- Header --}}
            <div class="estimate-header">
                <div class="estimate-title">
                    <i class="fas fa-file-invoice-dollar me-2"></i>Estimate
                </div>
                @if ($record->organization_deleted_at)
                    <div class="deleted-alert">
                        <i class="fas fa-info-circle me-2"></i>
                        <strong>Deleted:</strong> Organization has been deleted.
                    </div>
                @endif

                <div class="estimate-meta">
                    <span class="estimate-number">#{{ ucfirst($record->slug) }}</span>
                    @switch($record->status)
                        @case('draft')
                            <span class="status draft">
                                <i class="fas fa-edit me-1"></i>Draft
                            </span>
                        @break

                        @case('sent')
                            <span class="status sent">
                                <i class="fas fa-paper-plane me-1"></i>Sent
                            </span>
                        @break

                        @case('approved')
                            <span class="status approved">
                                <i class="fas fa-check-circle me-1"></i>Approved
                            </span>
                        @break

                        @case('rejected')
                            <span class="status rejected">
                                <i class="fas fa-times-circle me-1"></i>Rejected
                            </span>
                        @break

                        @case('revised')
                            <span class="status revised">
                                <i class="fas fa-redo me-1"></i>Revised
                            </span>
                        @break
                    @endswitch
                </div>
            </div>
            {{-- Address Section --}}
            <div class="address-section">
                <div class="address-box">
                    <h4><i class="fas fa-building me-2"></i>From</h4>
                    <p>
                        <strong>{{ $record->company->name }}</strong><br>
                        <strong>Mobile No:</strong> {{ $record->company->mobile_no }}
                        <br>
                        <strong>Email:</strong> {{ $record->company->email }}
                    </p>
                </div>
                <div class="address-box">
                    <h4><i class="fas fa-user me-2"></i>Invoice To</h4>
                    <p>
                        <strong>{{ $record->organization_name }}</strong><br>
                        {{ $record->organization_address_one }}
                        <br>
                        <strong>Email:</strong> {{ $record->organization_email }}
                        <br>
                        <strong>Phone:</strong> {{ $record->organization_phone }}
                    </p>
                </div>
            </div>

                    {{-- Estimate Start --}}
                    <div class="form-section">
                                                    <div class="form-row">
                                @if ($record->contract_id == null)
                                    <div class="form-group">
                                        <label for="client_id">Client</label>
                                        <select name="client_id" id="client_id" class="form-control select2">
                                            <option value="">-- Select Client --</option>
                                            @foreach ($clients as $client)
                                                <option value="{{ $client->client_id }}"
                                                    {{ $record->client_id == $client->client_id ? 'selected' : '' }}>
                                                    {{ $client->first_name }} {{ $client->last_name }}
                                                </option>
                                            @endforeach
                                        </select>
                                        <div class="print-value">
                                            <strong>Client:</strong>
                                            {{ optional($clients->firstWhere('id', $record->client_id))->first_name ?? '' }}
                                            {{ optional($clients->firstWhere('id', $record->client_id))->last_name ?? '' }}
                                        </div>
                                    </div>
                                @else
                                    <input type="hidden" name="client_id" value="{{ $record->client_id }}">
                                @endif

                                <div class="form-group">
                                    <label for="estimate_date">Estimate Date</label>
                                    <input required type="date" name="estimate_date" class="form-control"
                                        value="{{ $record->issue_date }}">
                                    <div class="print-value">
                                        <strong>Estimate Date:</strong>
                                        {{ \Carbon\Carbon::parse($record->issue_date)->format('F j, Y') }}
                                    </div>
                                </div>
                                @if ($record->contract_id == null)
                                    <div class="form-group">
                                        <label for="event_date">Event Date</label>
                                        <input required type="date" name="event_date" class="form-control"
                                            value="{{ $record->event_date }}">
                                        <div class="print-value">
                                            <strong>Event Date:</strong>
                                            {{ \Carbon\Carbon::parse($record->event_date)->format('F j, Y') }}
                                        </div>
                                    </div>
                                @endif
                                <div class="form-group">
                                    <label for="expiration_date">Expiry Date</label>
                                    <input required type="date" name="expiration_date" class="form-control"
                                        value="{{ $record->valid_until }}" min="{{ date('Y-m-d', strtotime('+1 day')) }}">
                                    <div class="print-value">
                                        <strong>Expiry Date:</strong>
                                        {{ \Carbon\Carbon::parse($record->valid_until)->format('F j, Y') }}
                                    </div>
                                </div>
                            </div>

                                                        {{-- Product Table --}}
                            <div class="form-row mt-4">
                                <div class="col-12">
                                    <h5 class="mb-3" style="color: #1f2937;font-size: 18px;">
                                        Product Details
                                    </h5>
                                    <div class="forref">
                                        <table class="table product-table" id="productTable">
                                            <thead>
                                                <tr>
                                                    <th>Product Name</th>
                                                    <th>Quantity</th>
                                                    <th>Product Price</th>
                                                    <th>Total</th>
                                                    <th class="no-print">Action</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                @if($estimate && $estimate->items->count())
                                                    @foreach($estimate->items as $item)
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
                                                            <td class="no-print">
                                                                <button class="btn btn-sm btn-primary edit-item"
                                                                        data-url="{{ route('estimate.products.update') }}"
                                                                        data-estimateid="{{ $estimate->id }}"
                                                                        data-csrf="{{ csrf_token() }}"
                                                                        data-id="{{ $item->id }}"
                                                                        data-name="{{ $item->name }}"
                                                                        data-quantity="{{ $item->quantity }}"
                                                                        data-unit="{{ $item->unit }}"
                                                                        data-price="{{ $item->price }}">
                                                                    <i class="fas fa-edit"></i>
                                                                </button>
                                                                <button class="btn btn-sm btn-danger remove-item"
                                                                        data-url="{{ route('estimate.products.delete') }}"
                                                                        data-id="{{ $item->id }}"
                                                                        data-estimateid="{{ $estimate->id }}"
                                                                        data-csrf="{{ csrf_token() }}">
                                                                    <i class="fas fa-trash"></i>
                                                                </button>
                                                            </td>
                                                        </tr>
                                                    @endforeach
                                                @else
                                                    <tr class="no-items">
                                                        <td colspan="5" class="text-center">No products added yet.</td>
                                                    </tr>
                                                @endif
                                            </tbody>
                                            <tfoot>
                                                <tr>
                                                    <th colspan="3" class="text-end">Subtotal:</th>
                                                    <th id="subtotal">$0.00</th>
                                                    <th></th>
                                                </tr>

                                                @if($estimate && $estimate->taxes->count())
                                                <tr>
                                                    <th colspan="3" class="text-end">Tax:
                                                        <div class="d-flex flex-wrap gap-2 justify-content-end">
                                                            @foreach($estimate->taxes as $tax)
                                                                <div class="border rounded px-2 py-1 d-flex align-items-center gap-1"
                                                                    data-tax-id="{{ $tax->id }}">

                                                                    <small class="fw-semibold">
                                                                        {{ $tax->name }} ({{ $tax->percent }}%)
                                                                    </small>

                                                                    <button class="btn btn-sm btn-link text-primary edit-tax"
                                                                            data-tax-id="{{ $tax->id }}"
                                                                            data-url="{{ route('estimate.tax.get') }}"
                                                                            data-update-url="dsfsdf"
                                                                            data-csrf="{{ csrf_token() }}"
                                                                            data-estimateid="{{ $estimate->id }}"
                                                                            data-toggle="modal"
                                                                            data-target="#editTaxModal">
                                                                        <i class="fas fa-edit"></i>
                                                                    </button>

                                                                    <button class="btn btn-sm btn-link text-danger p-0 delete-tax"
                                                                            data-url="{{ route('estimate.tax.delete', $tax->id) }}"
                                                                            data-csrf="{{ csrf_token() }}">
                                                                        <i class="fas fa-trash"></i>
                                                                    </button>
                                                                </div>
                                                            @endforeach
                                                        </div>
                                                    </th>
                                                    <th id="tax_amount">${{ number_format($estimate->taxes->sum('amount'), 2) }}</th>
                                                    <th></th>
                                                </tr>
                                                @endif
                                               @if($estimate && $estimate->discounts->count())
                                                <tr class="fw-bold discount-row">
                                                    @foreach($estimate->discounts as $discount)
                                                        <th colspan="3" class="text-end">
                                                            Discount {{ $discount->name }}
                                                             <button class="btn btn-sm btn-link text-danger p-0 delete-discount"
                                                                    data-url="{{ route('estimate.product.discount.delete', $discount->id) }}"
                                                                    data-csrf="{{ csrf_token() }}">
                                                                <i class="fas fa-trash"></i>
                                                            </button>
                                                        </th>
                                                        <th class="discount_percent">
                                                            {{ $discount->value }} %
                                                        </th>
                                                        <th></th>
                                                    @endforeach
                                                </tr>
                                                @endif
                                                <tr class="fw-bold">
                                                    <th colspan="3" class="text-end">Total:</th>
                                                    <th id="total">$0.00</th>
                                                    <th></th>
                                                </tr>
                                            </tfoot>

                                        </table>



                                    </div>
                                    <div class="action-buttons">
                                        <!-- <button type="button" class="btn btn-success btn-sm no-print" onclick="addRow()">
                                            <i class="fas fa-plus me-1"></i>Add Field
                                        </button> -->
                                        <button type="button" class="btn btn-primary btn-sm no-print"
                                            data-toggle="modal" data-target="#productModal">
                                            <i class="fas fa-cube me-1"></i>Add Product
                                        </button>
                                        <button class="btn btn-info btn-sm no-print"
                                                data-toggle="modal"
                                                data-target="#taxModal"
                                                data-url="{{ route('estimate.products.get') }}"
                                                data-csrf="{{ csrf_token() }}"
                                                data-estimateid="{{ $estimate->id }}">
                                            <i class="fas fa-percentage me-1"></i>Add Tax
                                        </button>
                                        @if($estimate && $estimate->discounts->count())
                                            @foreach($estimate->discounts as $discounts)
                                                <button type="button" class="btn btn-warning btn-sm no-print"
                                                    data-toggle="modal" data-target="#editdiscountModal"
                                                    data-url="{{ route('estimate.product.discount.get') }}"
                                                    data-csrf="{{ csrf_token() }}"
                                                    data-estimateid="{{ $estimate->id }}"
                                                    data-discountid="{{ $discounts->id }}"
                                                >
                                                    <i class="fas fa-tag me-1"></i>Edit Discount
                                                </button>
                                            @endforeach
                                        @else
                                        <button type="button" class="btn btn-warning btn-sm no-print"
                                            data-toggle="modal" data-target="#discountModal">
                                            <i class="fas fa-tag me-1"></i>Add Discount
                                        </button>
                                        @endif
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-12">
                                    <h5 class="mb-3" style="color: #1f2937;font-size: 18px;">
                                        Payment Schdule
                                    </h5>
                                    <form id="paymentScheduleForm" method="POST" action="{{ route('estimate.installments.save', $estimate->id) }}">
                                        @csrf
                                        <input type="hidden" name="total_amount" id="total_amount" value="{{ $estimate->total_amount }}">
                                        @php
                                        $installments = $estimate->installments ?? collect();
                                        @endphp

                                        <div id="dynamicInputsContainer">
                                            @foreach($installments as $inst)
                                                <div class="row mb-2 installment-row">
                                                    <div class="col-md-5">
                                                        <input type="number" 
                                                            name="installments[{{$loop->index}}][amount]" 
                                                            class="form-control inst-amount" 
                                                            value="{{ $inst->amount }}" 
                                                            step="0.01" min="0" required>
                                                    </div>
                                                    <div class="col-md-5">
                                                        <input type="date" 
                                                            name="installments[{{$loop->index}}][date]" 
                                                            class="form-control inst-date" 
                                                            value="{{ $inst->installment_date }}" 
                                                            min="{{ date('Y-m-d') }}" required>
                                                    </div>
                                                    <div class="col-md-2">
                                                        <button type="button" class="btn btn-danger w-100 btn-remove">×</button>
                                                    </div>
                                                </div>
                                            @endforeach
                                        </div>

                                        <hr>
                                        <div class="d-flex justify-content-between align-items-center mb-2">
                                            <h6 class="mb-0">Installment Schedule</h6>
                                            <div id="installmentError" class="text-danger mt-2" style="display:none;">
                                                Please add product before adding installment.
                                            </div>
                                            <button type="button" class="btn btn-sm btn-success" id="addRowBtn">+</button>
                                        </div>

                                        <div class="d-flex justify-content-between">
                                            <strong>Remaining Total:</strong>
                                            <span id="remainingTotal">$1,000.00</span>
                                            <input type="hidden" name="remaining_total" id="remaining_total" value="{{ $estimate->total_amount }}">
                                        </div>

                                        <button type="submit" class="btn btn-warning btn-sm no-print">Save Payment Schedule</button>
                                    </form>

                                    <div id="formMessage" class="mt-2 text-success" style="display:none;"></div>

                                </div>
                            </div>

                            <div class="form-row mt-4">
                                <div class="col-12">
                                    <h5 class="mb-3" style="color: #1f2937;font-size: 18px;">
                                        Note
                                    </h5>
                                    <div class="forref">
                                        <textarea id="estimate_note" name="note" class="form-control editor" rows="4">{{ $estimate->note }}</textarea>
                                        <div class="print-value">
                                            <strong>Note:</strong>
                                            {!! $estimate->note !!}
                                        </div>
                                        <button type="button"
                                                class="btn btn-warning btn-sm no-print save-note"
                                                data-url="{{ route('estimate.note.save') }}"
                                                data-csrf="{{ csrf_token() }}"
                                                data-estimateid="{{ $estimate->id }}">
                                            <i class="fas fa-save me-1"></i> Save Note
                                        </button>

                                        <div id="formMessage" class="mt-2" style="display:none;"></div>
                                    </div>
                                </div>
                            </div>

                            <div class="form-row mt-4">
                                <div class="col-12">
                                    <h5 class="mb-3" style="color: #1f2937;font-size: 18px;">
                                        Terms & Conditions
                                    </h5>
                                    <div class="forref">
                                        <textarea id="terms_and_condition" name="terms_and_condition" class="form-control editor" rows="4" placeholder="Enter terms and conditions">
                                            {!! $estimate->terms ?? ($default_terms_and_condition->content ?? '') !!}</textarea>
                                        
                                        <button type="button"
                                                class="btn btn-warning btn-sm no-print save-note"
                                                data-url="{{ route('estimate.note.save') }}"
                                                data-csrf="{{ csrf_token() }}"
                                                data-estimateid="{{ $estimate->id }}">
                                            <i class="fas fa-save me-1"></i> Save Term and Condition
                                        </button>
                                        <div class="print-value mt-3">
                                            <strong>Terms & Conditions (Preview):</strong>
                                            <div class="preview-content">
                                                @if (!empty($estimate->terms_and_condition))
                                                    {!! $estimate->terms_and_condition !!}
                                                @else
                                                    {!! $default_terms_and_condition->content ?? '' !!}
                                                @endif
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                                                        {{-- Action Buttons --}}
                            <div class="form-row mt-4">
                                <div class="col-12">
                                    <div id="installmentValidationError"
                                        class="text-danger mt-2"
                                        style="display:none;">
                                        Please schedule a payment first.
                                    </div>

                                    <div class="action-buttons">
                                        @if ($estimate->status != 'approved')
                                            <!-- <button type="submit" class="btn btn-primary">
                                                <i class="fas fa-save me-1"></i>Save
                                            </button> -->
                                        @endif

                                        @if ($estimate->status == 'approved')
                                            <input type="hidden" name="adjust" value="1">
                                            <button type="submit" class="btn btn-primary">
                                                <i class="fas fa-edit me-1"></i>Adjust
                                            </button>
                                        @endif

                                        @if ($estimate->status != 'approved')
                                            <input type="hidden" name="mail_send" value="1">
                                            <button type="submit" class="btn btn-success">
                                                <i class="fas fa-paper-plane me-1"></i>Send to Client
                                            </button>
                                            <!-- <button type="button" class="btn btn-success" onclick="submitSentForm()">
                                                    <i class="fas fa-paper-plane me-1"></i>Send
                                                </button> -->
                                        @endif
                                        <!-- <button type="button" class="btn btn-outline-secondary no-print cust-bd"
                                            onclick="window.print()">
                                            <i class="fas fa-print me-1"></i>Print
                                        </button> -->
                                    </div>
                                </div>
                            </div>

                            {{-- Hidden form for Send action --}}
                        @if ($record->status != 'approved')
                            <form id="sentForm" method="POST" action="{{ route('estimate.save') }}"
                                style="display: none;">
                                @csrf
                                <input type="hidden" name="slug" value="{{ $record->slug }}">
                            </form>
                        @endif
                    </div>
                            

                    </div>
                   
                </div>

              
            </div>
        </div>

        {{-- Add Product Modals --}}
        <div class="modal fade" id="productModal" tabindex="-1">
            <div class="modal-dialog modal-lg">
                <div class="modal-content">

                    <div class="modal-header">
                        <h5 class="modal-title">
                            <i class="fas fa-cube me-2"></i>Select Products
                        </h5>
                        <button type="button" class="close" data-dismiss="modal">
                            <span>&times;</span>
                        </button>
                    </div>

                    <div class="modal-body">
                        <div id="modalAlert" class="alert d-none" role="alert"></div>
                        <table class="table table-hover">
                            <thead>
                                <tr>
                                    <th>Select</th>
                                    <th>Product Name</th>
                                    <th>Product Qty</th>
                                    <th>Product Price</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($products as $product)
                                    <tr>
                                        <td>
                                            <input type="checkbox"
                                                class="product-checkbox"
                                                data-id="{{ $product->id }}"
                                                data-name="{{ $product->name }}"
                                                data-price="{{ $product->price }}">
                                        </td>
                                        <td>{{ $product->name }}</td>
                                        <td>
                                            <input type="number"
                                                class="form-control form-control-sm product-qty"
                                                data-id="{{ $product->id }}"
                                                min="1"
                                                value="1">
                                        </td>
                                        <td>${{ number_format($product->price, 2) }}</td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>

                    <div class="modal-footer">
                        <button class="btn btn-secondary btn-sm" data-dismiss="modal">Cancel</button>
                        <button id="addProductsBtn" class="btn btn-primary btn-sm"
                            data-url="{{ route('estimate.products.add') }}"
                            data-estimateid="{{ $estimate->id }}" 
                            data-csrf="{{ csrf_token() }}">
                        <i class="fas fa-plus me-1"></i> Add Selected
                        </button>
                    </div>

                </div>
            </div>
        </div>

        {{-- Edit Product Modals --}}

        <div class="modal fade" id="editProductModal" tabindex="-1" role="dialog" aria-hidden="true">
            <div class="modal-dialog" role="document">
                <form id="editProductForm">
                    @csrf
                    <input type="hidden" name="item_id">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title">Edit Product</h5>
                            <button type="button" class="close" data-dismiss="modal">&times;</button>
                        </div>
                        <div class="modal-body">
                            <div id="modalAlert" class="alert d-none" role="alert"></div>
                            <div class="form-group">
                                <label>Product Name</label>
                                <input type="text" name="name" class="form-control" readonly>
                            </div>
                            <div class="form-group">
                                <label>Quantity</label>
                                <input type="number" name="quantity" class="form-control">
                            </div>
                            <div class="form-group">
                                <label>Unit</label>
                                <input type="text" name="unit" class="form-control">
                            </div>
                            <div class="form-group">
                                <label>Price</label>
                                <input type="number" name="price" class="form-control" step="0.01">
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
                            <button type="submit" class="btn btn-primary">Save Changes</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>

        {{-- Add Tax Modals --}}
        <div class="modal fade" id="taxModal" tabindex="-1" role="dialog" aria-labelledby="taxModalLabel"
                    aria-hidden="true">
                    <div class="modal-dialog" role="document">
                        <div class="modal-content">
                            <form id="taxForm" onsubmit="event.preventDefault(); addTax();">
                                <div class="modal-header">
                                    <h5 class="modal-title" id="taxModalLabel">
                                        <i class="fas fa-percentage me-2"></i>Add Tax
                                    </h5>
                                    <button type="button" class="close" data-dismiss="modal"
                                        aria-label="Close">&times;</button>
                                </div>
                                <div class="modal-body">
                                    <div id="modalAlert" class="alert d-none" role="alert"></div>
                                    <div class="form-group">
                                        <label for="taxName">Tax Name</label>
                                        <input type="text" id="taxName" class="form-control" placeholder="e.g. VAT"
                                            required>
                                    </div>
                                    <div class="form-group">
                                        <label for="taxPercent">Tax Percent (%)</label>
                                        <input type="number" id="taxPercent" class="form-control" placeholder="e.g. 10"
                                            min="0" step="0.01" required>
                                    </div>
                                    <table class="table product-table" id="taxTable">
                                    <thead>
                                        <tr>
                                            <th>Select Product</th>
                                            <th>Product Name</th>
                                            <th>Quantity</th>
                                            <th>Product Price</th>
                                            <th>Total</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <!-- PRODUCTS HERE -->
                                    </tbody>
                                </table>

                                </div>
                                <div class="modal-footer">
                                    <button id="addTaxBtn" class="btn btn-primary btn-sm"
                                            data-url="{{ route('estimate.tax.add') }}"
                                            data-estimateid="{{ $estimate->id }}"
                                            data-csrf="{{ csrf_token() }}">
                                        <i class="fas fa-plus me-1"></i> Add Tax
                                    </button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>

        {{-- Edit Tax Modals --}}
        <div class="modal fade" id="editTaxModal" tabindex="-1" role="dialog" aria-labelledby="taxModalLabel"
                    aria-hidden="true">
                    <div class="modal-dialog" role="document">
                        <div class="modal-content">
                            <form id="taxForm" onsubmit="event.preventDefault(); updateTax();">
                                <div class="modal-header">
                                    <h5 class="modal-title" id="taxModalLabel">
                                        <i class="fas fa-percentage me-2"></i>Add Tax
                                    </h5>
                                    <button type="button" class="close" data-dismiss="modal"
                                        aria-label="Close">&times;</button>
                                </div>
                                <div class="modal-body">
                                    <div id="modalAlert" class="alert d-none" role="alert"></div>
                                    <div class="form-group">
                                        <label for="taxName">Tax Name</label>
                                        <input type="text" id="edittaxName" class="form-control" placeholder="e.g. VAT"
                                            required>
                                    </div>
                                    <div class="form-group">
                                        <label for="taxPercent">Tax Percent (%)</label>
                                        <input type="number" id="edittaxPercent" class="form-control" placeholder="e.g. 10"
                                            min="0" step="0.01" required>
                                    </div>
                                    <table class="table product-table" id="editTaxTable">
                                    <thead>
                                        <tr>
                                            <th>Select Product</th>
                                            <th>Product Name</th>
                                            <th>Quantity</th>
                                            <th>Product Price</th>
                                            <th>Total</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <!-- PRODUCTS HERE -->
                                    </tbody>
                                </table>

                                </div>
                                <div class="modal-footer">
                                    <button id="updateTaxBtn" class="btn btn-primary btn-sm"
                                            data-url="{{ route('estimate.tax.update') }}"
                                            data-estimateid="{{ $estimate->id }}"
                                            data-csrf="{{ csrf_token() }}">
                                        <i class="fas fa-plus me-1"></i> Update Tax
                                    </button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
                {{-- Add Disscount Modals --}}
                <div class="modal fade" id="discountModal" tabindex="-1" role="dialog"
                    aria-labelledby="discountModalLabel" aria-hidden="true">
                    <div class="modal-dialog" role="document">
                        <div class="modal-content">
                            <form id="discountForm" onsubmit="event.preventDefault(); addProductDiscount();">
                                <div class="modal-header">
                                    <h5 class="modal-title" id="discountModalLabel">
                                        <i class="fas fa-tag me-2"></i>Add Discount
                                    </h5>
                                    <button type="button" class="close" data-dismiss="modal"
                                        aria-label="Close">&times;</button>
                                </div>
                                <div class="modal-body">
                                    <div class="form-group">
                                        <label for="discountName">Discount Name</label>
                                        <input type="text" id="discountName" class="form-control"
                                            placeholder="e.g. Summer Sale">
                                    </div>
                                    <div class="form-group">
                                        <label for="discountValue">Discount Amount %</label>
                                        <div class="input-group">
                                            <input type="number" id="discountAmount" class="form-control"
                                                placeholder="e.g. 10">
                                        </div>
                                    </div>
                                </div>
                                <div class="modal-footer">
                                    <button type="button" class="btn btn-secondary btn-sm"
                                        data-dismiss="modal">Cancel</button>
                                    <button id="addDiscount" class="btn btn-primary btn-sm"
                                            data-url="{{ route('estimate.product.discount.add') }}"
                                            data-estimateid="{{ $estimate->id }}"
                                            data-csrf="{{ csrf_token() }}">
                                        <i class="fas fa-plus me-1"></i> Apply Discount
                                    </button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
                {{-- Edit Disscount Modals --}}
                <div class="modal fade" id="editdiscountModal" tabindex="-1" role="dialog"
                    aria-labelledby="discountModalLabel" aria-hidden="true">
                    <div class="modal-dialog" role="document">
                        <div class="modal-content">
                            <form id="discountForm" onsubmit="event.preventDefault(); addProductDiscount();">
                                <div class="modal-header">
                                    <h5 class="modal-title" id="discountModalLabel">
                                        <i class="fas fa-tag me-2"></i>Edit Discount
                                    </h5>
                                    <button type="button" class="close" data-dismiss="modal"
                                        aria-label="Close">&times;</button>
                                </div>
                                <div class="modal-body">
                                    <div class="form-group">
                                        <label for="discountName">Discount Name</label>
                                        <input type="text" id="editdiscountName" class="form-control"
                                            placeholder="e.g. Summer Sale">
                                    </div>
                                    <div class="form-group">
                                        <label for="discountValue">Discount Amount %</label>
                                        <div class="input-group">
                                            <input type="number" id="editdiscountAmount" class="form-control"
                                                placeholder="e.g. 10">
                                        </div>
                                    </div>
                                </div>
                                <div class="modal-footer">
                                    <button type="button" class="btn btn-secondary btn-sm"
                                        data-dismiss="modal">Cancel</button>
                                    <button type="button"
                                        id="updateDiscount"
                                        class="btn btn-primary btn-sm"
                                        data-url="{{ route('estimate.product.discount.update') }}"
                                        data-estimateid="{{ $estimate->id }}"
                                        data-csrf="{{ csrf_token() }}">
                                        <i class="fas fa-save me-1"></i> Update Discount
                                    </button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
    </section>



<link href="https://cdn.jsdelivr.net/npm/summernote@0.8.20/dist/summernote-bs4.min.css" rel="stylesheet">
@push('scripts')
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/js/bootstrap.bundle.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/summernote@0.8.20/dist/summernote-bs4.min.js"></script>
<script>
$(document).ready(function () {
    
    $('#estimate_note').summernote({
        height: 180,
        placeholder: 'Write note here...',
        toolbar: [
            ['style', ['bold', 'italic', 'underline']],
            ['para', ['ul', 'ol']],
            ['insert', ['link']],
            ['view', ['codeview']]
        ]
    });

    $('#terms_and_condition').summernote({
        height: 180,
        placeholder: 'Write note here...',
        toolbar: [
            ['style', ['bold', 'italic', 'underline']],
            ['para', ['ul', 'ol']],
            ['insert', ['link']],
            ['view', ['codeview']]
        ]
    });
});

</script>
 @endpush
@endsection

