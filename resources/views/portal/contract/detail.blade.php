@extends('portal.master')

@section('content')
    @push('stylesheets')
        <link href="{{ asset('admin/assets/scss/invoice-tables.css') }}" rel="stylesheet" type="text/css">
    @endpush

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
        .contract-wrapper {
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

        .contract-header {
            border-bottom: 2px solid var(--primary-color);
            padding-bottom: 15px;
            margin-bottom: 20px;
        }

        .contract-title {
            font-size: 18px;
            font-weight: 700;
            color: #1f2937;
            margin-bottom: 8px;
            line-height: 1.2;
        }

        .contract-meta {
            display: flex;
            flex-direction: column;
            gap: 10px;
            font-size: 14px;
        }

        .contract-number {
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

        .status.pending {
            background-color: #ffc107;
            color: #212529;
        }

        .status.accepted {
            background-color: #28a745;
            color: #fff;
        }

        .status.rejected {
            background-color: #dc3545;
            color: #fff;
        }

        .status.draft {
            background-color: var(--primary-color);
            color: #fff;
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

        .card {
            border: 1px solid var(--border-color);
            border-radius: 8px;
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.03);
            margin-bottom: 20px;
            overflow: hidden;
        }

        .card-header {
            background: #ffffff !important;
            border-bottom: 1px solid #e5e7eb !important;
            padding: 20px 30px !important;
            color: #1f2937 !important;
        }

        /* Table Responsiveness */
        .table-responsive {
            width: 100%;
            overflow-x: auto;
            -webkit-overflow-scrolling: touch;
        }

        .table {
            border-collapse: collapse;
            width: 100%;
            min-width: 600px;
        }

        .table th {
            background: #F8F9FA;
            color: var(--secondary-color);
            font-weight: 600;
            padding: 12px 8px;
            text-align: left;
            border: 1px solid var(--border-color);
            font-size: 13px;
            white-space: nowrap;
        }

        .table td {
            padding: 10px 8px;
            border: 1px solid var(--border-color);
            vertical-align: middle;
            font-size: 13px;
        }

        .table-hover tbody tr:hover {
            background-color: var(--light-bg);
        }

        .table-striped tbody tr:nth-of-type(odd) {
            background-color: rgba(160, 194, 66, 0.05);
        }

        /* Badge Styles */
        .badge {
            padding: 4px 8px;
            border-radius: 4px;
            font-weight: 600;
            font-size: 11px;
            display: inline-block;
        }

        .bg-success {
            background-color: #28a745 !important;
        }

        .bg-warning {
            background-color: #ffc107 !important;
            color: #212529 !important;
        }

        .bg-danger {
            background-color: #dc3545 !important;
        }

        /* Button Styles for Mobile */
        .btn {
            border-radius: 6px;
            font-weight: 600;
            padding: 10px 15px;
            transition: all 0.3s ease;
            border-radius: 8px;
            font-size: 14px;
            width: 100%;
            margin-bottom: 8px;
            box-sizing: border-box;
            text-align: center;
            display: block;
        }

        .btn-primary {
            background: #9FC23F !important;
            border: 1px solid #fff !important;
            border-radius: 8px !important;
            padding: 10px 20px !important;
            color: white;
            text-decoration: none;
            font-weight: 600 !important;
            font-size: 14px !important;
            transition: all 0.3s ease;
            display: inline-flex;
            align-items: center;
            gap: 8px;
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

        .btn-danger {
            background: #dc3545;
            color: white;
        }

        .btn-danger:hover {
            background: #c82333;
            transform: translateY(-1px);
        }

        .btn-outline-primary {
            border: 2px solid var(--primary-color);
            color: var(--primary-color);
            background: transparent;
            width: auto;
        }

        .btn-outline-primary:hover {
            background: var(--primary-color);
            color: white;
        }

        .btn-sm {
            padding: 6px 10px;
            font-size: 12px;
            width: auto;
        }

        .btn-lg {
            padding: 12px 20px;
            font-size: 16px;
        }

        /* Form Styles */
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

        .form-label {
            font-weight: 600;
            margin-bottom: 6px;
            color: var(--secondary-color);
            display: block;
            font-size: 14px;
        }

        /* Alert Styles */
        .alert {
            border-radius: 6px;
            padding: 12px 15px;
            margin-bottom: 15px;
            font-size: 13px;
        }

        .alert-success {
            background-color: #d4edda;
            border-color: #c3e6cb;
            color: #155724;
        }

        .alert-danger {
            background-color: #f8d7da;
            border-color: #f5c6cb;
            color: #721c24;
        }

        /* Utility Classes */
        .text-success {
            color: #28a745 !important;
            font-weight: 600;
        }

        .text-danger {
            color: #dc3545 !important;
            font-weight: 600;
        }

        .text-muted {
            color: var(--text-light);
        }

        .fw-bold {
            font-weight: 600;
        }

        .fw-semibold {
            font-weight: 500;
        }

        .theme-bg {
            background-color: var(--primary-color) !important;
        }

        .theme-text {
            color: #1f2937 !important;
        }

        /* Action Sections */
        .actions {
            margin-top: 20px;
            text-align: center;
        }

        /* Nested Tables */
        .table-sm {
            font-size: 12px;
        }

        .table-sm th,
        .table-sm td {
            padding: 6px 8px;
        }

        /* Small Mobile Optimization */
        @media (max-width: 480px) {
            .contract-wrapper {
                padding: 12px;
                margin-top: 10px;
            }

            .contract-title {
                font-size: 18px;
            }

            .address-box {
                padding: 12px;
            }

            .address-box h4 {
                font-size: 15px;
            }

            .card-header {
                padding: 10px 12px;
                font-size: 14px;
            }

            .btn {
                padding: 8px 12px;
                font-size: 13px;
            }

            .btn-lg {
                padding: 10px 16px;
                font-size: 14px;
            }

            .table th,
            .table td {
                padding: 8px 6px;
                font-size: 12px;
            }

            .badge {
                font-size: 10px;
                padding: 3px 6px;
            }

            .alert {
                padding: 10px 12px;
                font-size: 12px;
            }

            .form-control {
                padding: 8px;
                font-size: 13px;
            }
        }

        /* Fix for very small screens */
        @media (max-width: 360px) {
            .contract-wrapper {
                padding: 10px;
            }

            .contract-title {
                font-size: 18px;
            }

            .address-box {
                padding: 10px;
            }

            .btn {
                padding: 8px 10px;
                font-size: 12px;
            }

            .table {
                min-width: 500px;
            }
        }

        /* Tablet Styles */
        @media (min-width: 768px) {
            .contract-wrapper {
                padding: 25px;
                border-radius: 10px;
            }

            .contract-title {
                font-size: 18px;
            }

            .contract-meta {
                flex-direction: row;
                align-items: center;
                gap: 15px;
            }

            .address-section {
                flex-direction: row;
            }

            .address-box {
                flex: 1;
                min-width: 200px;
                padding: 20px;
            }

            .card-header {
                padding: 15px 20px;
                font-size: 16px;
            }

            .btn {
                width: auto;
                margin-bottom: 0;
            }

            /* Client Actions */
            .d-flex.justify-content-center.gap-3 {
                flex-direction: row;
            }

            .d-flex.justify-content-center.gap-3 .btn {
                width: auto;
            }
        }

        /* Desktop Styles */
        @media (min-width: 992px) {
            .contract-wrapper {
                padding: 30px;
            }

            .contract-title {
                font-size: 18px;
            }

            .address-box {
                padding: 25px;
            }

            .table th {
                padding: 15px;
                font-size: 14px;
            }

            .table td {
                padding: 12px 15px;
                font-size: 14px;
            }
        }

        /* Client Actions Specific Styles */
        .d-flex.justify-content-center.gap-3 {
            display: flex !important;
            flex-direction: column;
            gap: 10px;
            align-items: center;
        }

        @media (min-width: 576px) {
            .d-flex.justify-content-center.gap-3 {
                flex-direction: row;
            }
        }

        /* Nested Table Container */
        .nested-table-container {
            background: var(--light-bg);
            border-radius: 6px;
            padding: 10px;
            margin: 10px 0;
        }

        /* Text Center for Mobile */
        .text-center-mobile {
            text-align: center;
        }

        @media (min-width: 768px) {
            .text-center-mobile {
                text-align: left;
            }
        }

        /* Improve touch targets */
        .btn,
        .table td {
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

        /* Ensure proper text wrapping */
        .address-box p,
        .card-body p {
            word-wrap: break-word;
            overflow-wrap: break-word;
        }

        /* Icon sizing */
        .fas {
            font-size: 0.9em;
        }

        /* Small text adjustments */
        small {
            font-size: 12px;
        }

        @media (min-width: 768px) {
            small {
                font-size: 13px;
            }
        }
    </style>

    <section class="main-content">
        <div class="container">
            <div class="contract-wrapper">

                <!-- Contract s -->
                <div class="contract-header">
                    <div class="contract-title">
                        <i class="fas fa-file-contract me-2"></i>Contract
                    </div>

                    @if (session('success'))
                        <div class="alert alert-success alert-dismissible fade show">
                            <i class="fas fa-check-circle me-2"></i>{{ session('success') }}
                            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                        </div>
                    @endif

                    @if (session('error'))
                        <div class="alert alert-danger alert-dismissible fade show">
                            <i class="fas fa-exclamation-triangle me-2"></i>{{ session('error') }}
                            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                        </div>
                    @endif

                    <div class="contract-meta">
                        <span class="contract-number">#{{ ucfirst($record->slug) }}</span>

                        <span class="status {{ $record->status }}">
                            <i class="fas fa-circle me-1" style="font-size: 8px;"></i>
                            {{ strtoupper($record->status) }}
                        </span>

                        <div class="d-flex gap-3 flex-wrap">
                            <small>
                                <i class="fas fa-calendar-alt me-1"></i>
                                <strong>Start Date:</strong>
                                {{ \Carbon\Carbon::parse($record->start_date)->format('F j, Y') }}
                            </small>
                            <small>
                                <i class="fas fa-calendar-day me-1"></i>
                                <strong>Event Date:</strong>
                                {{ \Carbon\Carbon::parse($record->event_date)->format('F j, Y') }}
                            </small>
                        </div>

                        @if ($record->is_accept === 'accepted')
                           <small class="text-success">
                                <i class="fas fa-check-circle me-1"></i>
                                Client accepted this contract 
                                @if (!empty($record->accept_time_at))
                                    {{ $record->accept_time_at }}
                                @endif
                            </small>

                        @elseif($record->is_accept === 'rejected')
                            <small class="text-danger">
                                <i class="fas fa-times-circle me-1"></i>Client rejected this contract
                            </small>
                        @endif
                    </div>
                </div>

                <!-- Parties Section -->
                <div class="address-section">
                    <div class="address-box">
                        <h4><i class="fas fa-building me-2"></i>From</h4>
                        <p>
                            <strong>{{ $record->company->name }}</strong><br>
                            {{ $record->company->address }}<br>
                            <i class="fas fa-envelope me-1"></i> {{ $record->company->email }}<br>
                            <i class="fas fa-phone me-1"></i> {{ $record->company->mobile_no }}
                        </p>
                    </div>

                        <!-- <div class="address-box">
                            <h4><i class="fas fa-users me-2"></i>Organization</h4>
                            <p>
                                <strong>{{ $record->organization->id ?? '-' }}</strong><br>
                                <i class="fas fa-envelope me-1"></i> {{ optional($record->organization)->email ?? '-' }}<br>
                                <i class="fas fa-phone me-1"></i> {{ optional($record->organization)->mobile_no ?? '-' }}
                            </p>
                        </div> -->

                    <div class="address-box">
                        <h4><i class="fas fa-user me-2"></i>Invioce To</h4>
                        <p>
                            <strong>{{ $record->client->name }}</strong><br>
                            <i class="fas fa-envelope me-1"></i> {{ $record->client->email ?? '-' }}<br>
                            <i class="fas fa-phone me-1"></i> {{ $record->client->mobile_no ?? '-' }}
                        </p>
                    </div>
                </div>

                <!-- Linked Estimates -->
                <div class="card">
                    <div class="card-header">
                        {{-- <i class="fas fa-file-invoice-dollar me-2"></i> --}}
                        Estimates Linked to this Contract
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table table-hover">
                                <thead>
                                    <tr>
                                        <th>Estimate #</th>
                                        <th>Issue Date</th>
                                        <th>Status</th>
                                        <th>Total</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @if ($record->estimates && $record->estimates->count())
                                        @foreach ($record->estimates as $estimate)
                                            <tr>
                                                <td>
                                                    <a href="{{ route('estimate.show', $estimate->slug) }}"
                                                        class="btn btn-xs btn-info">
                                                        <i class="fas fa-external-link-alt me-1 ss"></i>
                                                        {{ strtoupper($estimate->slug) }}
                                                    </a>
                                                </td>
                                                <td>{{ \Carbon\Carbon::parse($estimate->issue_date)->format('F j, Y') }}
                                                </td>
                                                <td>
                                                    <span class="badge status-{{ $estimate->status }}">
                                                        {{ strtoupper($estimate->status) }}
                                                    </span>
                                                    @if ($estimate->is_adjusted)
                                                        <small class="text-muted"><em>(Adjusted)s</em></small>
                                                    @endif
                                                </td>
                                                @php
                                                    $subtotal = $estimate->items->sum('total_price') ?: (float) ($estimate->total ?? 0);
                                                    $taxPercent = $record->taxes->sum('percent');
                                                    $taxAmount = $subtotal * ($taxPercent / 100);
                                                    $total = $subtotal + $taxAmount;
                                                @endphp

                                                <td class="fw-semibold">
                                                    ${{ number_format($estimate->total, 2) }}
                                                </td>
                                            </tr>
                                        @endforeach
                                    @else
                                        <tr>
                                            <td colspan="4" class="text-center text-muted py-4">
                                                <i class="fas fa-inbox fa-2x mb-2"></i><br>
                                                <em>No estimates linked to this contract.</em>
                                            </td>
                                        </tr>
                                    @endif
                                </tbody>
                            </table>
                        </div>

                        @if (Auth::user()->user_type == 'company')
                           <div class="mt-3 text-end">
                                <button type="button" 
                                        class="btn btn-primary" 
                                        data-id="{{ $record->id }}"
                                        data-url="{{ route('contract.modify.details', $record->id) }}"
                                        data-bs-toggle="modal" 
                                        data-bs-target="#modifyContractModal">
                                    <i class="fas fa-plus me-1"></i>Modify Contract
                                </button>
                            </div>
                        @endif
                    </div>
                </div>
                                <div class="card">

                  <div class="card-header">
                        <div class="card-title">
                            <h5>Notes</h5>
                            <small id="notes-status" class="text-success"></small>
                        </div>

                            <div class="form-section mt-4">
                                @if ($activityLog->count())
                                    <ul class="list-group" id="activityLogList">
                                        @foreach ($activityLog as $log)
                                            <li class="list-group-item">
                                                <div class="d-flex justify-content-between">
                                                    <div>
                                                        <strong>{{ ucfirst($log->createdBy->name ?? 'Activity') }}</strong>
                                                        <div class="text-muted small">
                                                            {{ $log->notesTextarea ?? '' }}
                                                        </div>
                                                    </div> 
                                                    <small class="text-muted">
                                                        {{ $log->created_at->timezone('America/Los_Angeles')->diffForHumans() }}
                                                    </small>
                                                </div>
                                            </li>
                                        @endforeach
                                    </ul>
                                @else
                                    <ul class="list-group" id="activityLogList">
                                        <li class="list-group-item">No activity found.</li>
                                    </ul>
                                @endif
                            </div>
                            <div class="col-md-12">
                                <label class="form-label">Add Notes</label>
                                <input type="hidden" name="client_id" id="client_id" value="{{ $record->client_id }}">
                                <input type="hidden" name="contract_id" id="contract_id"
                                    value="{{ $record->id }}">
                                <textarea id="notesTextarea" class="form-control" rows="4" readonly placeholder="Click here to add notes..."></textarea>
                                <button id="saveNotesBtn" class="btn btn-primary mt-2 d-none">
                                    Save Notes
                                </button>
                            </div>
                    </div>
                </div>

                    <!-- Linked Estimates -->
                <div class="card">
                    <div class="card-header">
                        {{-- <i class="fas fa-file-invoice-dollar me-2"></i> --}}
                        Contract Products
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <!-- <table class="table table-hover">
                                <thead>
                                    <tr>
                                        <th>Item Name</th>
                                        <th>Price</th>
                                        <th>Quantity</th>
                                        <th>Total Price</th>
                                        <th>Accepted By client</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @if ($record->items && $record->items->count())
                                        @foreach ($record->items as $item)
                                            <tr>
                                                <td>{{ $item->name }}  @if($item->is_modified == 1) <span class="badge bg-warning">M</span> @endif</td>
                                                <td class="fw-semibold">${{ number_format((float)($item->price ?? 0), 2) }}</td>
                                                <td>{{ $item->quantity ?? 0 }}</td>
                                                <td class="fw-semibold">${{ number_format((float)($item->total_price ?? 0), 2) }}</td>
                                                <td>
                                                    @if ($item->is_accepted_by_client == 1)
                                                        <span class="badge bg-success">Yes</span>
                                                    @else
                                                        <span class="badge bg-danger">No</span>
                                                    @endif
                                                </td>
                                            </tr>
                                        @endforeach
                                    @else
                                        <tr>
                                            <td colspan="9" class="text-center text-muted py-4">
                                                <i class="fas fa-inbox fa-2x mb-2"></i><br>
                                                <em>No products found.</em>
                                            </td>
                                        </tr>
                                    @endif
                                </tbody>
                            </table> -->
                                <table class="table product-table" id="productTable">
                                    <thead>
                                        <tr>
                                            <th>Product Name</th>
                                            <th>Description</th>
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

                                        @if($estimate && $estimate->items->count())
                                            @foreach($estimate->items as $item)
                                                @php
                                                    $subtotal += $item->total_price;

                                                    // Sum per-item taxes and round each
                                                    foreach($item->itemTaxes as $tax) {
                                                        $taxTotal += round($item->total_price * ($tax->percentage / 100), 2);
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
                                                    <td>{{ ($item->description) ?? 'N/A' }}</td>
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
                                        <tr>
                                            <th colspan="3" class="">Subtotal:</th>
                                            <th id="subtotal">${{ number_format($subtotal, 2) }}</th>
                                        </tr>

                                        @if($estimate && $estimate->taxes->count())
                                            <tr>
                                                <th colspan="3" class="">Tax:
                                                    <div class="d-flex flex-wrap gap-2 justify-content-end">
                                                        @foreach($estimate->taxes as $tax)
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

                                        @if($estimate && $estimate->discounts->count())
                                            <tr class="fw-bold discount-row">
                                                @foreach($estimate->discounts as $discount)
                                                    @php
                                                        $discountTotal += round($subtotal * ($discount->value / 100), 2);
                                                    @endphp
                                                    <th colspan="3" class="">
                                                        Discount {{ $discount->name }}
                                                    </th>
                                                    <th class="discount_percent">
                                                        {{ $discount->value }} %
                                                    </th>
                                                @endforeach
                                            </tr>
                                        @endif

                                        <tr class="fw-bold">
                                            <th colspan="3" class="">Total:</th>
                                            @php
                                                $total = $subtotal + $taxTotal - $discountTotal;
                                            @endphp
                                            <th id="total">${{ number_format($total, 2) }}</th>
                                        </tr>
                                    </tfoot>
                                </table>

                        </div>
                    </div>
                </div>

                <!-- Invoices Section -->
                <div class="card">
                    <div class="card-header">
                        {{-- <i class="fas fa-receipt me-2"></i> --}}
                        Invoices Generated
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table table-bordered table-hover">
                                <thead>
                                    <tr>
                                        <th>Invoice #</th>
                                        <th>Estimate Ref</th>
                                        <th>Issue Date</th>
                                        <th>Status</th>
                                        <th>Total</th>
                                        @if (Auth::user()->user_type != 'client')
                                            <th>Action</th>
                                        @endif
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse ($record->invoices as $invoice)
                                    
                                        <tr>
                                            <td>
                                                <a href="{{ route('invoice.show', $invoice->slug) }}"
                                                    class="btn btn-xs btn-info">
                                                    <i class="fas fa-external-link-alt me-1"></i>
                                                    {{ strtoupper($invoice->slug) }}
                                                </a>
                                            </td>
                                            <td>
                                                <a href="{{ route('estimate.show', $invoice->estimate_slug) }}"
                                                    class="text-muted text-decoration-none">
                                                    {{ strtoupper($invoice->estimate_slug) }}
                                                </a>
                                            </td>
                                            <td>{{ \Carbon\Carbon::parse($invoice->issue_date)->format('F j, Y') }}</td>
                                            <td>
                                                @switch($invoice->status)
                                                    @case('paid')
                                                        <span class="badge bg-success">
                                                            <i class="fas fa-check-circle me-1"></i>Paid
                                                        </span>
                                                    @break

                                                    @case('unpaid')
                                                    @case('partial')
                                                        <span class="badge bg-warning">
                                                            <i class="fas fa-clock me-1"></i>{{ ucfirst($invoice->status) }}
                                                        </span>
                                                    @break

                                                    @case('cancelled')
                                                        <span class="badge bg-danger">
                                                            <i class="fas fa-times-circle me-1"></i>Cancelled
                                                        </span>
                                                    @break
                                                @endswitch
                                            </td>
                                            <td class="fw-semibold">${{ number_format($invoice->total, 2) }}</td>
                                            @if (Auth::user()->user_type != 'client')
                                                <td>
                                                    @if (in_array($invoice->status, ['paid', 'partial']))
                                                        <a href="{{ route('contract.add-credit-note', $invoice->slug) }}"
                                                            class="btn btn-outline-primary btn-sm">
                                                            <i class="fas fa-file-invoice me-1"></i>Credit Note
                                                        </a>
                                                    @else
                                                        <span class="text-muted">--</span>
                                                    @endif
                                                    @if($invoice->status == "unpaid" && $invoice->is_installment !=1)
                                                    <button type="button" 
                                                            class="btn btn-primary" 
                                                            data-bs-toggle="modal" 
                                                            data-bs-target="#paymentModal" 
                                                            data-id="{{ $invoice->id }}" >
                                                        Pay Now
                                                    </button>
                                                    @endif
                                                    <div class="modal fade" id="paymentModal" tabindex="-1" aria-labelledby="paymentModalLabel" aria-hidden="true">
                                                                <div class="modal-dialog">
                                                                    <form action="{{ route('update-invoice-status') }}" method="POST">
                                                                        @csrf
                                                                        <input type="hidden" name="invoice_id" id="invoice_id" value="{{ $invoice->id }}">
                                                                        <input type="hidden" name="total" id="total" value="{{ $invoice->total }}">
                                                                        <div class="modal-content">
                                                                            <div class="modal-header">
                                                                                <h5 class="modal-title" id="paymentModalLabel">Update Payment Status</h5>
                                                                                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                                                            </div>
                                                                            <div class="modal-body">
                                                                                <input type="hidden" name="id" id="modal_invoice_id">

                                                                                <div class="mb-3">
                                                                                    <label class="form-label">Payment Type</label>
                                                                                    <select name="payment_type" class="form-select" required>
                                                                                        <option value="cash">Cash</option>
                                                                                        <option value="cheque">Check</option>
                                                                                    </select>
                                                                                </div>

                                                                                <div class="mb-3">
                                                                                    <label class="form-label">Notes</label>
                                                                                    <textarea name="notes" class="form-control" rows="3" placeholder="Add payment details, transaction ID, etc."></textarea>
                                                                                </div>
                                                                            </div>
                                                                            <div class="modal-footer">
                                                                                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                                                                                <button type="submit" class="btn btn-success">Submit Payment</button>
                                                                            </div>
                                                                        </div>
                                                                    </form>
                                                                </div>
                                                            </div>
                                                    
                                                </td>
                                            @endif
                                        </tr>

                                        <!-- Installment Plan -->
                                        @if ($invoice->installmentPlan)
                                            <tr>
                                                <td colspan="{{ Auth::user()->user_type != 'client' ? '6' : '5' }}">
                                                    <div class="mt-3">
                                                        <h6 class="fw-bold theme-text mb-3">
                                                            <i class="fas fa-calendar-check me-2"></i>Installment Schedule
                                                        </h6>
                                                        <div class="table-responsive">
                                                            <table class="table table-sm table-striped">
                                                                <thead>
                                                                    <tr>
                                                                        <th>#</th>
                                                                        <th>Due Date</th>
                                                                        <th>Amount</th>
                                                                        <th>Status</th>
                                                                        <th>Paid On</th>
                                                                        <th>Paid type</th>
                                                                    </tr>
                                                                </thead>
                                                                <tbody>
                                                                    @foreach ($invoice->installmentPlan->payments as $installment)
                                                                        <tr>
                                                                            <td>{{ $installment->installment_number }}</td>
                                                                            <td>{{ \Carbon\Carbon::parse($installment->due_date)->format('F j, Y') }}
                                                                            </td>
                                                                            <td>${{ number_format($installment->amount, 2) }}
                                                                            </td>
                                                                            <td>
                                                                                @if ($installment->is_paid)
                                                                                    <span
                                                                                        class="badge bg-success">Paid</span>
                                                                                @elseif ($installment->status === 'cancelled')
                                                                                    <span
                                                                                        class="badge bg-danger">Cancelled</span>
                                                                                @else
                                                                                    <span
                                                                                        class="badge bg-warning">Unpaid</span>
                                                                                @endif
                                                                            </td>
                                                                            <td>{{ $installment->paid_at ? \Carbon\Carbon::parse($installment->paid_at)->format('F j, Y') : '-' }}
                                                                            </td>
                                                                            <td>
                                                                                    @if($installment->paid_at == null)
                                                                                        <button type="button" 
                                                                                                class="btn btn-primary" 
                                                                                                data-bs-toggle="modal" 
                                                                                                data-bs-target="#paymentInstallmentModal" 
                                                                                                data-id="{{ $installment->id }}" >
                                                                                            Pay Now installment
                                                                                        </button>
                                                                                        <div class="modal fade" id="paymentInstallmentModal" tabindex="-1" aria-labelledby="paymentInstallmentModalLabel" aria-hidden="true">
                                                                                                    <div class="modal-dialog">
                                                                                                        <form action="{{ route('update-installment-status') }}" method="POST">
                                                                                                            @csrf
                                                                                                               <input type="hidden" name="plane_id" id="plane_id" value="{{$invoice->installmentPlan->id }}">
                                                                                                            <input type="hidden" name="invoice_id" id="invoice_id" value="{{ $invoice->id }}">
                                                                                                            <input type="hidden" name="installment_id" id="installment_id" value="{{ $installment->id }}">
                                                                                                            <input type="hidden" name="total" id="total" value="{{ $installment->amount }}">
                                                                                                            <div class="modal-content">
                                                                                                                <div class="modal-header">
                                                                                                                    <h5 class="modal-title" id="paymentModalLabel">Update Payment Status</h5>
                                                                                                                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                                                                                                </div>
                                                                                                                <div class="modal-body">
                                                                                                                    <input type="hidden" name="id" id="modal_invoice_id">

                                                                                                                    <div class="mb-3">
                                                                                                                        <label class="form-label">Payment Type</label>
                                                                                                                        <select name="payment_type" class="form-select" required>
                                                                                                                            <option value="cash">Cash</option>
                                                                                                                            <option value="cheque">Check</option>
                                                                                                                        </select>
                                                                                                                    </div>

                                                                                                                    <div class="mb-3">
                                                                                                                        <label class="form-label">Notes</label>
                                                                                                                        <textarea name="notes" class="form-control" rows="3" placeholder="Add payment details, transaction ID, etc."></textarea>
                                                                                                                    </div>
                                                                                                                </div>
                                                                                                                <div class="modal-footer">
                                                                                                                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                                                                                                                    <button type="submit" class="btn btn-success">Submit Payment</button>
                                                                                                                </div>
                                                                                                            </div>
                                                                                                        </form>
                                                                                                    </div>
                                                                                                </div>
                                                                                         @else
                                                                                         Paid
                                                                                         @endif

                                                                                    </td>
                                        </td>
                                                                        </tr> 
                                                                    @endforeach
                                                                </tbody>
                                                            </table>
                                                        </div>
                                                    </div>
                                                </td>
                                            </tr>
                                        @endif

                                        <!-- Credit Notes -->
                                        @if ($invoice->creditNotes && $invoice->creditNotes->count())
                                            <tr>
                                                <td colspan="{{ Auth::user()->user_type != 'client' ? '6' : '5' }}">
                                                    <div class="mt-3">
                                                        <h6 class="fw-bold theme-text">
                                                            <i class="fas fa-sticky-note me-2"></i>Credit Notes
                                                        </h6>
                                                        <table class="table table-sm table-hover">
                                                            <thead>
                                                                <tr>
                                                                    <th>#</th>
                                                                    <th>Invoice</th>
                                                                    <th>Amount</th>
                                                                    <th>Reason</th>
                                                                    <th>Status</th>
                                                                    <th>Created At</th>
                                                                </tr>
                                                            </thead>
                                                            <tbody>
                                                                @foreach ($invoice->creditNotes as $note)
                                                                    <tr>
                                                                        <td>{{ $note->id }}</td>
                                                                        <td>
                                                                            <a href="{{ route('invoice.show', $invoice->slug) }}"
                                                                                class="text-decoration-none">
                                                                                {{ strtoupper($invoice->slug) }}
                                                                            </a>
                                                                        </td>
                                                                        <td class="text-danger fw-semibold">
                                                                            -${{ number_format($note->amount, 2) }}</td>
                                                                        <td>{{ $note->reason ?? '-' }}</td>
                                                                        <td>
                                                                            @if ($note->status === 'open')
                                                                                <span class="badge bg-warning">Open</span>
                                                                            @else
                                                                                <span
                                                                                    class="badge bg-success">Settled</span>
                                                                            @endif
                                                                        </td>
                                                                        <td>{{ $note->created_at->format('F j, Y') }}</td>
                                                                    </tr>
                                                                @endforeach
                                                            </tbody>
                                                        </table>
                                                    </div>
                                                </td>
                                            </tr>
                                        @endif
                                        @empty
                                            <tr>
                                                <td colspan="{{ Auth::user()->user_type != 'client' ? '6' : '5' }}"
                                                    class="text-center text-muted py-4">
                                                    <i class="fas fa-receipt fa-2x mb-2"></i><br>
                                                    <em>No invoices generated yet.</em>
                                                </td>
                                            </tr>
                                        @endforelse
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                                        @if(Auth::user()->user_type != 'client')
                  <div class="activity-section shadow-sm rounded bg-white p-4 rnt-pd">
    <div class="section-header d-flex align-items-center mb-3">
        {{-- <i class="fas fa-history me-2 text-primary"></i> --}}
        <h5 class="mb-0 act-txt">Recent Activity</h5>
    </div>

    <div class="table-responsive">
        <table class="table table-hover align-middle">
            <thead class="table-light">
                <tr>
                    <th style="width: 20%">Date & Time</th>
                    <!-- <th style="width: 15%">User</th> -->
                    <th style="width: 50%">Description</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($logs as $log)
                    <tr>
                        <td class="text-nowrap">{{ $log->created_at->format('M d, Y H:i') }}</td>
                        <!-- <td>
                            <span class="badge bg-light text-dark border">
                                {{ $log->user_name ?? 'System' }}
                            </span>
                        </td> -->
                        <td class="text-truncate" style="max-width: 300px;">
                            {{ $log->description }}
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="4" class="text-center py-4 text-muted">
                            No activity logs found.
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

                    @endif
                    <!-- Terms & Notes -->
                      @if ($estimate->note)
                        <div class="notes-section note-box">
                            <h5 class="note-title">
                                <i class="fas fa-file-contract me-2"></i>
                                Note
                            </h5>
                            <div class="note-content">
                                {!! $estimate->note !!}
                            </div>
                        </div>
                    @endif
                    {{-- Notes and Terms --}}
                    @if ($estimate->terms)
                        <div class="notes-section">
                            @if ($estimate->terms)
                                <h5><i class="fas fa-sticky-note me-2"></i>Terms And Condtion</h5>
                                <p>{!! $estimate->terms !!}</p>
                            @endif
                        </div>
                    @endif

                    <!-- <div class="card">
                        <div class="card-header">
                            {{-- <i class="fas fa-clipboard-list me-2"></i> --}}
                            Terms & Notes
                        </div>
                        <div class="card-body">
                            <h5 class="fw-bold theme-text mb-3">Terms</h5>
                            <p class="mb-4">{!! $record->terms_and_condition ?: 'No terms specified.' !!}</p>

                            <h5 class="fw-bold theme-text mb-3">Notes</h5>
                            <p>{!! $record->notes ?: 'No notes available.' !!}</p>
                        </div>
                    </div> -->



                    <!-- Company Edit Section -->
                    @if (Auth::user()->user_type == 'company')
                        <!-- <div class="card">
                            <div class="card-header">
                                {{-- <i class="fas fa-edit me-2"></i> --}}
                                Edit Contract Details
                            </div>
                            <div class="card-body">
                                <form action="{{ route('contract.update-contract', $record->slug) }}" method="POST">
                                    @csrf
                                    <div class="row">
                                        <div class="col-md-12 mb-3">
                                            <label class="form-label">Event Date</label>
                                            <input type="date" class="form-control" value="{{ $record->event_date }}"
                                                name="event_date" required>
                                        </div>
                                    </div>

                                    <div class="mb-3">
                                        <label class="form-label">Terms</label>
                                        <textarea name="terms" rows="4" class="form-control" placeholder="Enter contract terms...">{{ $record->terms_and_condition }}</textarea>
                                    </div>

                                    <div class="mb-3">
                                        <label class="form-label">Notes</label>
                                        <textarea name="notes" rows="3" class="form-control" placeholder="Enter any additional notes...">{{ $record->notes }}</textarea>
                                    </div>

                                    <button type="submit" class="btn btn-primary">
                                        <i class="fas fa-save me-1"></i>Save Changes
                                    </button>
                                </form>
                            </div>
                        </div> -->
                    @endif

                    <!-- Client Actions -->
                    @if (Auth::user()->user_type == 'client')
                        <div class="card">
                            <div class="card-header">
                                <i class="fas fa-handshake me-2"></i>Contract Agreement
                            </div>
                            <div class="card-body text-center-mobile">
                                @if ($record->is_accept == 'pending')
                                    <p class="mb-4">Please review the contract and accept or reject it.</p>
                                    <div class="d-flex justify-content-center gap-3 flex-wrap">
                                        <form action="{{ route('contract.accept', $record->slug) }}" method="POST"
                                            class="w-100 w-sm-auto">
                                            @csrf
                                            <button type="submit" class="btn btn-success btn-lg w-100">
                                                <i class="fas fa-check-circle me-2"></i>Accept Contract
                                            </button>
                                        </form>
                                        <form action="{{ route('contract.reject', $record->slug) }}" method="POST"
                                            class="w-100 w-sm-auto">
                                            @csrf
                                            <button type="submit" class="btn btn-danger btn-lg w-100">
                                                <i class="fas fa-times-circle me-2"></i>Reject Contract
                                            </button>
                                        </form>
                                    </div>
                                @else
                                    <div
                                        class="alert {{ $record->is_accept == 'accepted' ? 'alert-success' : 'alert-danger' }} mb-0">
                                        <i
                                            class="fas {{ $record->is_accept == 'accepted' ? 'fa-check-circle' : 'fa-times-circle' }} me-2"></i>
                                        Contract has been {{ $record->is_accept == 'accepted' ? 'accepted' : 'rejected' }}.
                                    </div>
                                @endif
                            </div>
                        </div>
                    @endif

                </div>
            </div>

  <div class="modal fade" id="modifyContractModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <form id="contractForm" action="{{ route('contract.modify.product') }}" method="POST">
            @csrf
            <input type="hidden" name="contract_id" value="{{ $record->id }}">
            
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Modify Contract</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div id="contractMessages"></div>
                <div class="modal-body">
                    
                    <div class="row align-items-end mb-4 border-bottom pb-3">
                        <div class="col-md-3">
                            <label class="form-label">Product</label>
                            <select id="product" name="product" class="form-select">
                                <option value="" data-price="0">Choose...</option>
                                @foreach($products as $product)
                                    <option value="{{ $product->id }}" 
                                            data-price="{{ $product->price }}"
                                            data-name="{{ $product->name }}"
                                            >
                                        {{ $product->name }}
                                    </option>
                                @endforeach
                            </select>
                            <input type="hidden" id="product_name" name="product_name" value="">
                        </div>
                        <div class="col-md-2">
                            <label class="form-label">Qty</label>
                            <input type="number" id="product_qty" name="product_qty" class="form-control" value="1" min="1">
                        </div>
                        <div class="col-md-2">
                            <label class="form-label">Price</label>
                            <input type="text" id="product_price" name="product_price" class="form-control bg-light" readonly value="0.00">
                        </div>
                        <div class="col-md-3">
                            <button type="submit" class="btn btn-info w-100">
                                    <i class="fas fa-plus"></i> Add to List
                                </button>
                            <button class="btn btn-info btn-sm no-print"
                                    type="button" 
                                    data-bs-toggle="modal"
                                    data-bs-target="#taxModal"
                                    data-id="{{ $record->id }}"
                                    data-url="{{ route('contract.modify.details', $record->id) }}"
                                    data-csrf="{{ csrf_token() }}">
                                <i class="fas fa-percentage me-1"></i>Add Tax
                            </button>
                        </div>
                        <div id="contractLoader" style="display:none;position:absolute;top:0;left:0;width:100%;height:100%;background:rgba(255,255,255,0.7);z-index:1000;text-align:center;padding-top:50px;">
                            <div class="spinner-border text-primary" role="status">
                                <span class="visually-hidden">Loading...</span>
                            </div>
                        </div>
                        <div class="table-responsive">
                            <table class="table product-table" id="md_productTable">
                                <thead>
                                    <tr>
                                        <th>Product Name</th>
                                        <th>Quantity</th>
                                        <th>Product Price</th>
                                        <th>Total</th>
                                        <th class="no-print">Action</th>
                                    </tr>
                                </thead>
                                <tbody></tbody>
                                <tfoot>
                                    <tr>
                                        <th colspan="4" class="text-end">Subtotal:</th>
                                        <th id="md_subtotal">$0.00</th>
                                    </tr>
                                    <tr id="tax_row">
                                        <th colspan="4" class="text-end">Tax:</th>
                                        <th id="md_tax_amount">$0.00</th>
                                    </tr>
                                    <!-- <tr id="discount_row">
                                        <th colspan="4" class="text-end">Discount:</th>
                                        <th id="discount_amount">$0.00</th>
                                    </tr> -->
                                    <tr>
                                        <th colspan="4" class="text-end">Total:</th>
                                        <th id="md_total">$0.00</th>
                                    </tr>
                                </tfoot>
                            </table>

                        </div>
                        </form>
                        <div class="table-responsive mt-4">
                            <h5>Payment Schedule</h5>
                            <form id="modifypaymentScheduleForm" method="POST" action="{{ route('estimate.installments.modify.save', $estimate->id) }}">
                                        <div class="sec-css">
                                        @csrf
                                        <input type="hidden" name="total_amount" id="total_amount" value="0">

                                        <div id="dynamicInputsContainer">
                                            
                                        </div>

                                        <hr>
                                        <div class="d-flex justify-content-between align-items-center mb-2">
                                            <h6 class="mb-0">Installment Schedule</h6>
                                            <div id="md_installmentError" class="text-danger mt-2" style="display:none;">
                                                Please add product before adding installment.
                                            </div>
                                            <button type="button" class="btn btn-sm btn-success" id="addRowBtn">+ Add Installment</button>
                                        </div>

                                        <div class="d-flex justify-content-between">
                                            <strong>Remaining Total:</strong>
                                            <span id="remainingTotal">$1,000.00</span>
                                            <input type="hidden" name="remaining_total" id="remaining_total" value="0">
                                        </div>
                                    </div>
                                        <button type="submit" id="savemodifyPaymentScheduleBtn" class="btn btn-warning btn-sm no-print">
                                            <span class="btn-schedule-text">Save Payment Schedule</span>
                                            <span class="btn-schedule-loading" style="display:none;">
                                                <span class="schedule-spinner"></span> Saving…
                                            </span>
                                        </button>
                                    </form>
                    </div>
                    <form id="clientConfirmationForm" method="POST" action="{{ route('contract.modify.save') }}">
                            <input type="hidden" name="cont_id" id="cont_id" value="{{$record->id}}">
                            <div class="mt-4 pt-3 border-top">
                                <label class="form-label font-weight-bold">Client Confirmation Status</label>
                                <select name="confirmed_with_client" class="form-select" required>
                                    <option value="0">No, haven't asked yet</option>
                                    <option value="1">Yes, I don't need to ask / Approved</option>
                                </select>
                                <small class="text-muted text-info">Please select "Yes" if you have verbal or written approval for these changes.</small>
                            </div>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                                <button type="submit" class="btn btn-success px-4">Save All Changes</button>
                            </div>
                    </form>

                    </div>        
                </div>
            </div>
        
    </div>
</div>
<div class="modal fade" id="taxModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <form id="contractTaxForm" action="{{ route('contract.apply.tax') }}" method="POST">
            @csrf
            <input type="hidden" name="contract_id" value="{{ $record->id }}">
            
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Modify Contract Tax</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div id="contractMessages"></div>
                <div class="modal-body">
                    
                    <div class="row align-items-end mb-4 border-bottom pb-3">
                        <div class="col-md-2">
                            <label class="form-label">Tax Name</label>
                            <input type="text" id="md_tax_name" name="md_tax_name" class="form-control" placeholder="e.g Tax">
                        </div>
                        <div class="col-md-2">
                            <label class="form-label">Tax Percent (%)</label>
                            <input type="text" id="md_tax_percent" name="md_tax_percent" class="form-control bg-light" placeholder="0.00">
                        </div>
                        <div class="col-md-3">
                            <button type="submit" class="btn btn-info w-100">
                                    <i class="fas fa-plus"></i> Apply Tax
                                </button>
                        </div>
                        <div id="contractLoader" style="display:none;position:absolute;top:0;left:0;width:100%;height:100%;background:rgba(255,255,255,0.7);z-index:1000;text-align:center;padding-top:50px;">
                            <div class="spinner-border text-primary" role="status">
                                <span class="visually-hidden">Loading...</span>
                            </div>
                        </div>
                        <div class="table-responsive">
                            <table class="table product-table" id="md_producttaxTable">
                                <thead>
                                    <tr>
                                        <th>Select Product</th>
                                        <th>Product Name</th>
                                        <th>Quantity</th>
                                        <th>Product Price</th>
                                        <th>Total</th>
                                    </tr>
                                </thead>
                                <tbody></tbody>
                                <tfoot>
                                    <tr>
                                        <th colspan="4" class="text-end">Subtotal:</th>
                                        <th id="md_tx_subtotal">$0.00</th>
                                    </tr>
                                    <tr id="tax_row">
                                        <th colspan="4" class="text-end">Tax:</th>
                                        <th id="md_tax_amount">$0.00</th>
                                    </tr>
                                    <!-- <tr id="discount_row">
                                        <th colspan="4" class="text-end">Discount:</th>
                                        <th id="discount_amount">$0.00</th>
                                    </tr> -->
                                    <tr>
                                        <th colspan="4" class="text-end">Total:</th>
                                        <th id="md_tx_total">$0.00</th>
                                    </tr>
                                </tfoot>
                            </table>

                        </div>

                    </div>        
                </div>
            </div>
        </form>
    </div>
</div>

<div class="modal fade" id="editTaxModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <form id="contracteditTaxForm" action="{{ route('contract.apply.tax') }}" method="POST">
            @csrf
            <input type="hidden" name="contract_id" value="{{ $record->id }}">
            <input type="hidden" name="tax_id" id="edit_tax_id">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Modify Contract Tax</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div id="contractMessages"></div>
                <div class="modal-body">
                    
                    <div class="row align-items-end mb-4 border-bottom pb-3">
                        <div class="col-md-2">
                            <label class="form-label">Tax Name</label>
                            <input type="text" id="md_edit_tax_name" name="md_edit_tax_name" class="form-control" placeholder="e.g Tax">
                        </div>
                        <div class="col-md-2">
                            <label class="form-label">Tax Percent (%)</label>
                            <input type="text" id="md_edit_tax_percent" name="md_edit_tax_percent" class="form-control bg-light" placeholder="0.00">
                        </div>
                        <div class="col-md-3">
                            <button type="submit" class="btn btn-info w-100">
                                    <i class="fas fa-plus"></i> Update Tax
                                </button>
                        </div>
                        <div id="contractLoader" style="display:none;position:absolute;top:0;left:0;width:100%;height:100%;background:rgba(255,255,255,0.7);z-index:1000;text-align:center;padding-top:50px;">
                            <div class="spinner-border text-primary" role="status">
                                <span class="visually-hidden">Loading...</span>
                            </div>
                        </div>
                        <div class="table-responsive">
                            <table class="table product-table" id="md_edit_producttaxTable">
                                <thead>
                                    <tr>
                                        <th>Select Product</th>
                                        <th>Product Name</th>
                                        <th>Quantity</th>
                                        <th>Product Price</th>
                                        <th>Total</th>
                                    </tr>
                                </thead>
                                <tbody></tbody>
                                <tfoot>
                                    <tr>
                                        <th colspan="4" class="text-end">Subtotal:</th>
                                        <th id="md_edit_tx_subtotal">$0.00</th>
                                    </tr>
                                    <tr id="tax_row">
                                        <th colspan="4" class="text-end">Tax:</th>
                                        <th id="md_edit_tax_amount">$0.00</th>
                                    </tr>
                                    <!-- <tr id="discount_row">
                                        <th colspan="4" class="text-end">Discount:</th>
                                        <th id="discount_amount">$0.00</th>
                                    </tr> -->
                                    <tr>
                                        <th colspan="4" class="text-end">Total:</th>
                                        <th id="md_edit_tx_total">$0.00</th>
                                    </tr>
                                </tfoot>
                            </table>

                        </div>

                    </div>        
                </div>
            </div>
        </form>
    </div>
</div>

        </section>

        <!-- Bootstrap & Font Awesome -->
        <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>


   
   

<script>
        document.addEventListener('DOMContentLoaded', function() {
            const textarea = document.getElementById('notesTextarea');
            const saveBtn = document.getElementById('saveNotesBtn');
            const status = document.getElementById('notes-status');

            const clientId = document.getElementById('client_id').value;
            const contractId = document.getElementById('contract_id').value;

            let originalText = textarea.value;

            // Enable editing on click
            textarea.addEventListener('click', function() {
                textarea.removeAttribute('readonly');
                saveBtn.classList.remove('d-none');
                originalText = textarea.value;
            });

            // Save notes
            saveBtn.addEventListener('click', function() {
                saveBtn.disabled = true;
                status.textContent = 'Saving...';

                fetch("{{ route('contact.notes.save') }}", {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'Accept': 'application/json',
                            'X-CSRF-TOKEN': "{{ csrf_token() }}"
                        },
                        credentials: 'same-origin', // ✅ important to send session
                        body: JSON.stringify({
                            notes: textarea.value,
                            client_id: clientId,
                            contract_id: contractId
                        })
                    })
                    .then(res => res.json())
                    .then(data => {
                        if (!data.success) {
                            status.textContent = 'Error saving';
                            return;
                        }

                        // Clear textarea
                        textarea.value = '';
                        textarea.setAttribute('readonly', true);
                        saveBtn.classList.add('d-none');
                        status.textContent = 'Saved ✔';

                        // Update activity log dynamically
                        const list = document.getElementById('activityLogList');
                        list.innerHTML = '';

                        data.activityLogs.forEach(log => {
                            const li = document.createElement('li');
                            li.className = 'list-group-item';

                            li.innerHTML = `
                    <div class="d-flex justify-content-between">
                        <div>
                            <strong>${log.createdBy?.name ?? 'System'}</strong>
                            <div class="text-muted small">
                                ${log.notesTextarea}
                            </div>
                        </div>
                        <small class="text-muted">
                            ${log.created_at}
                        </small>
                    </div>
                `;

                            list.appendChild(li);
                        });

                        setTimeout(() => status.textContent = '', 2000);
                    })
                    .catch(err => {
                        console.error(err);
                        textarea.value = originalText;
                        status.textContent = 'Error saving';
                    })
                    .finally(() => {
                        saveBtn.disabled = false;
                    });
            });
        });
    </script>
    @endsection

