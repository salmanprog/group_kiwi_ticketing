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

                    {{-- Form Start --}}
                    <div class="form-section">
                        <form method="POST" action="{{ route('estimate.update', ['estimate' => $record->slug]) }}" id="update-estimate-form">
                            @csrf
                            @method('PUT')

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
                                                    <th>Product</th>
                                                    <th>Quantity</th>
                                                    <th>Unit Price</th>
                                                    <th>Tax (%)</th>
                                                    <th>Gratuity (%)</th>
                                                    <th>Product Price (Tax + Gratuity)</th>
                                                    <th>Total</th>
                                                    <th class="no-print">Action</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <!-- Rows will be dynamically added here -->
                                            </tbody>
                                        </table>
                                    </div>
                                    <table class="table summary-table cust-main-table">
                                        <tr>
                                            <th>Subtotal</th>
                                            <td id="subtotalCell">$0.00</td>
                                        </tr>
                                        <tr>
                                            <th>Taxes</th>
                                            <td id="taxCell">$0.00</td>
                                        </tr>
                                        <tr id="discountRow" style="display: none;">
                                            <th>Discount</th>
                                            <td id="discountCell" class="text-danger font-weight-bold">-$0.00</td>
                                        </tr>
                                        <tr>
                                            <th><strong>Total</strong></th>
                                            <td id="totalCell"><strong>$0.00</strong></td>
                                        </tr>
                                    </table>

                                    <!-- Hidden inputs for totals -->
                                    <input type="hidden" name="subtotal" id="subtotalInput" value="0">
                                    <input type="hidden" name="tax_total" id="taxTotalInput" value="0">
                                    <input type="hidden" name="discount_total" id="discountTotalInput" value="0">
                                    <input type="hidden" name="total" id="totalInput" value="0">

                                    <div class="action-buttons">
                                        <!-- <button type="button" class="btn btn-success btn-sm no-print" onclick="addRow()">
                                            <i class="fas fa-plus me-1"></i>Add Field
                                        </button> -->
                                        <button type="button" class="btn btn-primary btn-sm no-print"
                                            data-toggle="modal" data-target="#productModal">
                                            <i class="fas fa-cube me-1"></i>Add Product
                                        </button>
                                        <button type="button" class="btn btn-info btn-sm no-print" data-toggle="modal"
                                            data-target="#taxModal">
                                            <i class="fas fa-percentage me-1"></i>Add Tax
                                        </button>
                                        <button type="button" class="btn btn-warning btn-sm no-print"
                                            data-toggle="modal" data-target="#discountModal">
                                            <i class="fas fa-tag me-1"></i>Add Discount
                                        </button>
                                    </div>

                                    {{-- Tax list container --}}
                                    <div id="taxList" class="mt-3">
                                        <!-- Dynamically added taxes with hidden inputs will appear here -->
                                    </div>

                                    {{-- Discount list container --}}
                                    <div id="discountDisplay" class="mt-2">
                                        <!-- Dynamically added discounts with hidden inputs will appear here -->
                                    </div>
                                </div>
                            </div>
                            
                              <div class="row">
                                <div class="col-md-12">
                                    @if ($record->status != 'approved')
                                    <h5 class="mb-3" style="color: #1f2937;font-size: 18px;">
                                        Payment Schdule
                                    </h5>
                                    <div class="form-check mb-3" style="display:none">
                                        <input class="form-check-input" type="checkbox" id="installmentCheck">
                                        <label class="form-check-label" for="installmentCheck">Is Installment?</label>
                                    </div>
                                    @endif

                                <div id="installmentSection" class="border p-3 rounded bg-light">
                                   
                                    <div id="dynamicInputsContainer"></div>
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
                                        <input type="hidden" name="remaining_total" id="remainingTotalInput">
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="modal fade" id="installmentModal" tabindex="-1" data-bs-backdrop="static">
                            <div class="modal-dialog">
                                <div class="modal-content">
                                    <div class="modal-header"><h5 class="modal-title">Number of Payments</h5></div>
                                    <div class="modal-body">
                                        <input type="number" id="numInstallments" class="form-control" placeholder="Enter number of installments (e.g. 3)">
                                    </div>
                                    <div class="modal-footer">
                                        <button type="button" class="btn btn-secondary" id="cancelModal" onclick="cancelModalNumberOfPayments()">
                                            Cancel
                                        </button>                                       
                                        
                                        <button type="button" class="btn btn-primary" id="generateFields">Generate</button>
                                    </div>
                                </div>
                            </div>
                        </div>

                          <div class="form-row mt-4">
                                <div class="col-12">
                                    <h5 class="mb-3" style="color: #1f2937;font-size: 18px;">
                                        Note
                                    </h5>
                                    <div class="forref">
                                        <textarea name="note" class="form-control editor" rows="4">{{ $record->note }}</textarea>
                                        <div class="print-value">
                                            <strong>Note:</strong>
                                            {!! $record->note !!}
                                        </div>
                                    </div>
                                </div>
                            </div>

                         <div class="form-row mt-4">
                                <div class="col-12">
                                    <h5 class="mb-3" style="color: #1f2937;font-size: 18px;">
                                        Terms & Conditions
                                    </h5>
                                    <div class="forref">
                                        <textarea name="terms_and_condition" class="form-control editor" rows="4" placeholder="Enter terms and conditions">
                                            {!! $record->terms_and_condition ?? ($default_terms_and_condition->content ?? '') !!}</textarea>
                                        
                                        <div class="print-value mt-3">
                                            <strong>Terms & Conditions (Preview):</strong>
                                            <div class="preview-content">
                                                @if (!empty($record->terms_and_condition))
                                                    {!! $record->terms_and_condition !!}
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
                                        @if ($record->status != 'approved')
                                            <button type="submit" class="btn btn-primary">
                                                <i class="fas fa-save me-1"></i>Save
                                            </button>
                                        @endif

                                        @if ($record->status == 'approved')
                                            <input type="hidden" name="adjust" value="1">
                                            <button type="submit" class="btn btn-primary">
                                                <i class="fas fa-edit me-1"></i>Adjust
                                            </button>
                                        @endif

                                        @if ($record->status != 'approved')
                                            <input type="hidden" name="mail_send" value="1">
                                            <button type="submit" class="btn btn-success">
                                                <i class="fas fa-paper-plane me-1"></i>Send
                                            </button>
                                            <!-- <button type="button" class="btn btn-success" onclick="submitSentForm()">
                                                    <i class="fas fa-paper-plane me-1"></i>Send
                                                </button> -->
                                        @endif
                                        <button type="button" class="btn btn-outline-secondary no-print cust-bd"
                                            onclick="window.print()">
                                            <i class="fas fa-print me-1"></i>Print
                                        </button>
                                    </div>
                                </div>
                            </div>
                            <!-- <div class="form-row mt-4">
                                @if ($record->status != 'approved')
                                    <div class="form-check">
                                        <input class="form-check-input" type="checkbox" id="installmentCheck">
                                        <label class="form-check-label" for="installmentCheck">
                                            Is Installment?
                                        </label>
                                    </div>
                                @endif
                            </div> -->

                     


                        </form>

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

                {{-- Modals --}}
                <div class="modal fade" id="productModal" tabindex="-1" role="dialog"
                    aria-labelledby="productModalLabel" aria-hidden="true">
                    <div class="modal-dialog modal-lg" role="document">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h5 class="modal-title">
                                    <i class="fas fa-cube me-2"></i>Select Products
                                </h5>
                                <button type="button" class="close" data-dismiss="modal"><span>&times;</span></button>
                            </div>

                            <div class="modal-body">
                                <table class="table table-hover">
                                    <thead>
                                        <tr>
                                            <th>Select</th>
                                            <th>Product Name</th>
                                            <th>Product Price</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($products as $product)
                                            <tr>
                                                <td><input type="checkbox" class="product-checkbox"
                                                        data-name="{{ $product->name }}"
                                                        data-price="{{ $product->price }}">
                                                </td>
                                                <td>{{ $product->name }}</td>
                                                <td>${{ number_format($product->price, 2) }}</td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>

                            <div class="modal-footer">
                                <button class="btn btn-secondary btn-sm" data-dismiss="modal">Cancel</button>
                                <button class="btn btn-primary btn-sm" onclick="addSelectedProducts()">
                                    <i class="fas fa-plus me-1"></i>Add Selected
                                </button>
                            </div>
                        </div>
                    </div>
                </div>

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
                                </div>
                                <div class="modal-footer">
                                    <button type="button" class="btn btn-secondary btn-sm"
                                        data-dismiss="modal">Cancel</button>
                                    <button type="submit" class="btn btn-primary btn-sm">Add Tax</button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>

                <div class="modal fade" id="discountModal" tabindex="-1" role="dialog"
                    aria-labelledby="discountModalLabel" aria-hidden="true">
                    <div class="modal-dialog" role="document">
                        <div class="modal-content">
                            <form id="discountForm" onsubmit="event.preventDefault(); addDiscount();">
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
                                        <label for="discountValue">Discount Amount or %</label>
                                        <div class="input-group">
                                            <input type="number" id="discountValue" class="form-control"
                                                placeholder="e.g. 10">
                                            <div class="input-group-append">
                                                <select id="discountType" class="form-control">
                                                    <option value="percent">%</option>
                                                    <option value="fixed">$</option>
                                                </select>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="modal-footer">
                                    <button type="button" class="btn btn-secondary btn-sm"
                                        data-dismiss="modal">Cancel</button>
                                    <button type="submit" class="btn btn-primary btn-sm">Apply Discount</button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="activity-section">
            <div class="section-header">
                <i class="fas fa-history me-2"></i>Recent Activity
            </div>

            <div class="activity-table-container">
                <table class="activity-table">
                    <thead>
                        <tr>
                            <th>Date & Time</th>
                            <th>User</th>
                            <th>Description</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($logs as $log)
                            <tr>
                                <td>{{ $log->created_at }}</td>
                                <td>{{ $log->user_name ?? 'N/A' }}</td>
                                <td>{{ Str::limit($log->description, 60) }}</td>
                                <td>
                                    <button class="btn btn-sm btn-outline-primary view-details" data-bs-toggle="modal"
                                        data-bs-target="#activityModal" data-description="{{ $log->description }}"
                                        data-alldata='{{ $log->new_data }}'>
                                        View
                                    </button>


                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="4" class="text-center text-muted">No activity logs found.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <!-- Modal -->
            <div class="modal fade" id="activityModal" tabindex="-1" aria-labelledby="activityModalLabel"
                aria-hidden="true">
                <div class="modal-dialog modal-xl modal-dialog-scrollable">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title" id="activityModalLabel">Activity Details</h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal"
                                aria-label="Close"></button>
                        </div>

                        <div class="modal-body">
                            <h6><strong>Description:</strong></h6>
                            <p id="modalDescription"></p>
                            <hr>

                            <div id="estimateData"></div> {{-- Dynamic content will appear here --}}
                        </div>
                    </div>
                </div>
            </div>

        </div>

    </section>

    <script>
        document.querySelectorAll('.view-details').forEach(button => {
            button.addEventListener('click', function() {
                const description = this.dataset.description || 'No description available';
                const allData = this.dataset.alldata || '{}';
                document.getElementById('modalDescription').innerText = description;

                const container = document.getElementById('estimateData');
                container.innerHTML = ''; // Clear previous data

                try {
                    let data = JSON.parse(allData);
                    if (typeof data === 'string') {
                        data = JSON.parse(data); // handle double encoding
                    }

                    console.log(data);
                    const estimate = data.estimate || {};
                    const items = data.items || [];
                    const taxes = data.taxes || [];
                    const discounts = data.discounts || [];

                    // Estimate Details Section
                    let html = `
                        <div class="mb-4">
                            <h5 class="theme-text mb-3"><i class="fas fa-file-invoice"></i> Estimate Details</h5>
                            <div class="table-responsive">
                                <table class="table table-bordered table-sm theme-table">
                                    <thead>
                                        <tr>
                                            <th width="30%">Field</th>
                                            <th width="70%">Value</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <tr><td><strong>ID</strong></td><td>${estimate.id || '-'}</td></tr>
                                        <tr><td><strong>Estimate Number</strong></td><td>${estimate.estimate_number || '-'}</td></tr>
                                        <tr><td><strong>Status</strong></td><td><span class="badge theme-badge">${estimate.status || '-'}</span></td></tr>
                                        <tr><td><strong>Total Amount</strong></td><td><strong>${estimate.total || '0.00'}</strong></td></tr>
                                        <tr><td><strong>Issue Date</strong></td><td>${estimate.issue_date || '-'}</td></tr>
                                        <tr><td><strong>Valid Until</strong></td><td>${estimate.valid_until || '-'}</td></tr>
                                        <tr><td><strong>Created At</strong></td><td>${estimate.created_at || '-'}</td></tr>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    `;

                    // Items Section
                    if (items.length > 0) {
                        html += `
                            <div class="mb-4">
                                <h5 class="theme-text mb-3"><i class="fas fa-list"></i> Items (${items.length})</h5>
                                <div class="table-responsive">
                                    <table class="table table-bordered table-sm theme-table">
                                        <thead>
                                            <tr>
                                                <th>Name</th>
                                                <th>Quantity</th>
                                                <th>Unit</th>
                                                <th>Price</th>
                                                <th>Total</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            ${items.map(item => `
                                                            <tr>
                                                                <td>${item.name || '-'}</td>
                                                                <td>${item.quantity || '0'}</td>
                                                                <td>${item.unit || '-'}</td>
                                                                <td>${item.price || '0.00'}</td>
                                                                <td>${item.tax || '0.00'}</td>
                                                                <td>${item.gratuity || '0.00'}</td>
                                                                <td><strong>${item.total_price || '0.00'}</strong></td>
                                                            </tr>
                                                        `).join('')}
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        `;
                    }

                    // Taxes Section
                    if (taxes.length > 0) {
                        html += `
                            <div class="mb-4">
                                <h5 class="theme-text mb-3"><i class="fas fa-percentage"></i> Taxes (${taxes.length})</h5>
                                <div class="table-responsive">
                                    <table class="table table-bordered table-sm theme-table">
                                        <thead>
                                            <tr>
                                                <th>Tax Name</th>
                                                <th>Percentage</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            ${taxes.map(tax => `
                                                            <tr>
                                                                <td>${tax.name || '-'}</td>
                                                                <td>${tax.percent || '0'}%</td>
                                                            </tr>
                                                        `).join('')}
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        `;
                    }

                    // Discounts Section
                    if (discounts.length > 0) {
                        html += `
                            <div class="mb-4">
                                <h5 class="theme-text mb-3"><i class="fas fa-tag"></i> Discounts (${discounts.length})</h5>
                                <div class="table-responsive">
                                    <table class="table table-bordered table-sm theme-table">
                                        <thead>
                                            <tr>
                                                <th>Discount Name</th>
                                                <th>Value</th>
                                                <th>Type</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            ${discounts.map(discount => `
                                                            <tr>
                                                                <td>${discount.name || '-'}</td>
                                                                <td>${discount.value || '0'}</td>
                                                                <td><span class="badge bg-info">${discount.type || '-'}</span></td>
                                                            </tr>
                                                        `).join('')}
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        `;
                    }

                    // Fallback if no data
                    if (!items.length && !taxes.length && !discounts.length) {
                        html += `
                            <div class="text-center py-4">
                                <i class="fas fa-inbox fa-3x text-muted mb-3"></i>
                                <p class="text-muted">No additional data found for this estimate.</p>
                            </div>
                        `;
                    }

                    container.innerHTML = html;

                } catch (e) {
                    console.error('Error parsing JSON:', e);
                    container.innerHTML = `
                        <div class="alert alert-danger">
                            <i class="fas fa-exclamation-triangle"></i> 
                            Error loading data: Invalid JSON format
                        </div>
                    `;
                }
            });
        });
        // document.querySelectorAll('.view-details').forEach(button => {
        //     button.addEventListener('click', function() {
        //         const description = this.dataset.description || 'No description';
        //         const allData = this.dataset.alldata || '{}';
        //         document.getElementById('modalDescription').innerText = description;

        //         const container = document.getElementById('estimateData');
        //         container.innerHTML = ''; // Clear previous data

        //         try {
        //             let data = JSON.parse(allData);
        //             if (typeof data === 'string') {
        //                 data = JSON.parse(data); // handle double encoding
        //             }
        //             console.log(data)
        //             const estimate = data.estimate || {};
        //             const items = data.items || [];
        //             const taxes = data.taxes || [];
        //             const discounts = data.discounts || [];

        //             let html = `
    //         <div class="mb-4">
    //             <h5 class="text-primary">Estimate Details</h5>
    //             <table class="table table-bordered table-sm">
    //                 <tbody>
    //                     <tr><th>ID</th><td>${estimate.id ?? '-'}</td></tr>
    //                     <tr><th>Estimate #</th><td>${estimate.estimate_number ?? '-'}</td></tr>
    //                     <tr><th>Status</th><td>${estimate.status ?? '-'}</td></tr>
    //                     <tr><th>Total</th><td>${estimate.total ?? '-'}</td></tr>
    //                     <tr><th>Issue Date</th><td>${estimate.issue_date ?? '-'}</td></tr>
    //                     <tr><th>Valid Until</th><td>${estimate.valid_until ?? '-'}</td></tr>
    //                     <tr><th>Created At</th><td>${estimate.created_at ?? '-'}</td></tr>
    //                 </tbody>
    //             </table>
    //         </div>
    //     `;

        //             if (items.length > 0) {
        //                 html += `
    //             <div class="mb-4">
    //                 <h5 class="text-success">Items</h5>
    //                 <table class="table table-bordered table-sm">
    //                     <thead>
    //                         <tr>
    //                             <th>Name</th>
    //                             <th>Qty</th>
    //                             <th>Unit</th>
    //                             <th>Price</th>
    //                             <th>Total</th>
    //                         </tr>
    //                     </thead>
    //                     <tbody>
    //                         ${items.map(item => `
        //                                 <tr>
        //                                     <td>${item.name}</td>
        //                                     <td>${item.quantity}</td>
        //                                     <td>${item.unit}</td>
        //                                     <td>${item.price}</td>
        //                                     <td>${item.total_price}</td>
        //                                 </tr>`).join('')}
    //                     </tbody>
    //                 </table>
    //             </div>
    //         `;
        //             }

        //             if (taxes.length > 0) {
        //                 html += `
    //             <div class="mb-4">
    //                 <h5 class="text-warning">Taxes</h5>
    //                 <table class="table table-bordered table-sm">
    //                     <thead>
    //                         <tr>
    //                             <th>Name</th>
    //                             <th>Percent</th>
    //                         </tr>
    //                     </thead>
    //                     <tbody>
    //                         ${taxes.map(tax => `
        //                                 <tr>
        //                                     <td>${tax.name}</td>
        //                                     <td>${tax.percent}%</td>
        //                                 </tr>`).join('')}
    //                     </tbody>
    //                 </table>
    //             </div>
    //         `;
        //             }

        //             if (discounts.length > 0) {
        //                 html += `
    //             <div class="mb-4">
    //                 <h5 class="text-danger">Discounts</h5>
    //                 <table class="table table-bordered table-sm">
    //                     <thead>
    //                         <tr>
    //                             <th>Name</th>
    //                             <th>Value</th>
    //                             <th>Type</th>
    //                         </tr>
    //                     </thead>
    //                     <tbody>
    //                         ${discounts.map(discount => `
        //                                 <tr>
        //                                     <td>${discount.name}</td>
        //                                     <td>${discount.value}</td>
        //                                     <td>${discount.type}</td>
        //                                 </tr>`).join('')}
    //                     </tbody>
    //                 </table>
    //             </div>
    //         `;
        //             }

        //             // Fallback if no data
        //             if (!items.length && !taxes.length && !discounts.length) {
        //                 html += `<p class="text-muted">No related data found.</p>`;
        //             }

        //             container.innerHTML = html;

        //         } catch (e) {
        //             container.innerHTML = `<p class="text-danger">Invalid JSON format</p>`;
        //         }
        //     });
        // });
    </script>


    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>


    <script>
        const existingEstimateItems = @json($record->items ?? []);
        const existingEstimateTaxes = @json($record->taxes ?? []);
        const existingEstimateDiscounts = @json($record->discounts ?? []);
        const existingEstimateInstallments = @json($record->installments ?? []);

        let productIndex = 1;
        let taxes = [];
        let discounts = [];

        // Load taxes from server-side (Laravel)
        if (Array.isArray(existingEstimateTaxes)) {
            taxes = existingEstimateTaxes.map(tax => ({
                name: tax.name,
                percent: parseFloat(tax.percent) || 0
            }));
        }

        if (Array.isArray(existingEstimateDiscounts)) {
            discounts = existingEstimateDiscounts.map(discount => ({
                name: discount.name,
                value: parseFloat(discount.value) || 0,
                type: discount.type
            }));
        }

        function addRow() {
            const table = document.querySelector("#productTable tbody");
            const row = document.createElement("tr");

            row.innerHTML = `
            <td><input type="text" name="products[${productIndex}][name]" class="form-control"></td>
            <td><input type="number" name="products[${productIndex}][quantity]" class="form-control" oninput="updateTotal(this)" step="0.01" min="0"></td>
            <td><input type="number" name="products[${productIndex}][price]" class="form-control" oninput="updateTotal(this)" step="0.01" min="0"></td>
            <td><input type="number" name="products[${productIndex}][tax]" class="form-control" value="0" step="0.01" min="0"></td>
            <td><input type="number" name="products[${productIndex}][gratuity]" class="form-control" value="0" step="0.01" min="0"></td>
            <td><input type="number" name="products[${productIndex}][product_total_price]" class="form-control" value="0" step="0.01" min="0"></td>
            <td class="total-cell">$0.00</td>
            <td class="no-print"><button type="button" class="btn btn-danger btn-sm" onclick="removeRow(this)">Delete</button></td>
        `;
            table.appendChild(row);
            productIndex++;
        }

        function removeRow(button) {
            const row = button.closest("tr");
            row.remove();
            calculateTotals();
        }

     function updateTotal(input) {
            const row = input.closest("tr");

            // 1. Get values from the row
            const qty = parseFloat(row.querySelector('input[name*="[quantity]"]').value) || 0;
            const basePrice = parseFloat(row.querySelector('input[name*="[price]"]').value) || 0;
            
            // Treating these inputs as percentage values (e.g., 10 for 10%)
            const taxPercent = parseFloat(row.querySelector('input[name*="[tax]"]').value) || 0;
            const gratuityPercent = parseFloat(row.querySelector('input[name*="[gratuity]"]').value) || 0;

            // 2. Calculate percentages based on Base Price
            const taxAmount = basePrice * (taxPercent / 100);
            const gratuityAmount = basePrice * (gratuityPercent / 100);

            // 3. Formula: (Base Price + Tax + Gratuity) * Quantity
            const unitPriceWithFees = basePrice + taxAmount + gratuityAmount;
            const rowTotal = unitPriceWithFees * qty;

            // 4. Update the Hidden/Readonly Total Input
            row.querySelector('input[name*="[product_total_price]"]').value = rowTotal.toFixed(2);

            // 5. Update the Visual Display Cell
            row.querySelector(".total-cell").innerText = `$${rowTotal.toFixed(2)}`;

            // 6. Update the Grand Totals
            calculateTotals();
        }

     function addSelectedProducts() {
            const table = document.querySelector("#productTable tbody");
            const checkboxes = document.querySelectorAll(".product-checkbox:checked");

            checkboxes.forEach((checkbox) => {
                const name = checkbox.dataset.name;
                const price = parseFloat(checkbox.dataset.price).toFixed(2);
                
                const row = document.createElement("tr");
                row.innerHTML = `
                    <td><input type="text" name="products[${productIndex}][name]" class="form-control" value="${name}" readonly></td>
                    <td><input type="number" name="products[${productIndex}][quantity]" class="form-control" value="1" oninput="updateTotal(this)" step="1" min="0"></td>
                    <td><input type="number" name="products[${productIndex}][price]" class="form-control" value="${price}" oninput="updateTotal(this)" step="0.01" min="0"></td>
                    <td><input type="number" name="products[${productIndex}][tax]" class="form-control" value="0.00" oninput="updateTotal(this)" step="0.01" min="0"></td>
                    <td><input type="number" name="products[${productIndex}][gratuity]" class="form-control" value="0.00" oninput="updateTotal(this)" step="0.01" min="0"></td>
                    <td><input type="number" name="products[${productIndex}][product_total_price]" class="form-control" value="${price}" readonly step="0.01" min="0"></td>
                    <td class="total-cell">$${price}</td>
                    <td class="no-print"><button type="button" class="btn btn-danger btn-sm" onclick="removeRow(this)">Delete</button></td>
                `;
                table.appendChild(row);
                productIndex++;
                checkbox.checked = false;
            });

            // Close modal and cleanup
          $('#productModal').modal('hide');
    
    $('[data-dismiss="modal"]').first().click();

            calculateTotals(); 
        }

        function addTax() {
            const name = document.getElementById('taxName').value.trim();
            const percent = parseFloat(document.getElementById('taxPercent').value);

            if (!name || isNaN(percent) || percent < 0) {
                alert('Please enter a valid tax name and percent.');
                return;
            }

            taxes.push({
                name,
                percent
            });
            document.getElementById('taxName').value = '';
            document.getElementById('taxPercent').value = '';
            $('#taxModal').modal('hide');

            renderTaxes();
            calculateTotals();
        }

        function renderTaxes() {
            const container = document.getElementById('taxList');
            container.innerHTML = '';
            taxes.forEach((tax, index) => {
                const div = document.createElement('div');
                div.classList.add('tax-row', 'mb-2');
                div.innerHTML = `
                <strong>${tax.name}:</strong> ${tax.percent}% 
                <button type="button" class="btn btn-danger btn-sm ml-2" onclick="removeTax(${index})">Remove</button>
                <input type="hidden" name="taxes[${index}][name]" value="${tax.name}">
                <input type="hidden" name="taxes[${index}][percent]" value="${tax.percent}">
            `;
                container.appendChild(div);
            });
        }

        function removeTax(index) {
            taxes.splice(index, 1);
            renderTaxes();
            calculateTotals();
        }

        function addDiscount() {
            const name = document.getElementById('discountName').value.trim();
            const value = parseFloat(document.getElementById('discountValue').value);
            const type = document.getElementById('discountType').value;

            if (!name || isNaN(value) || value < 0) {
                alert('Please enter a valid discount name and value.');
                return;
            }

            discounts.push({
                name,
                value,
                type
            });
            document.getElementById('discountName').value = '';
            document.getElementById('discountValue').value = '';
            document.getElementById('discountType').value = 'percent';
            $('#discountModal').modal('hide');

            renderDiscounts();
            calculateTotals();
        }

        function renderDiscounts() {
            const container = document.getElementById('discountDisplay');
            container.innerHTML = '';
            discounts.forEach((discount, index) => {
                const label = discount.type === 'percent' ? `${discount.value}%` : `$${discount.value.toFixed(2)}`;
                const div = document.createElement('div');
                div.classList.add('discount-entry', 'mb-2');
                div.innerHTML = `
                <strong>Discount:</strong> ${discount.name} (${label})
                <button type="button" class="btn btn-danger btn-sm ml-2" onclick="removeDiscount(${index})">Remove</button>
                <input type="hidden" name="discounts[${index}][name]" value="${discount.name}">
                <input type="hidden" name="discounts[${index}][value]" value="${discount.value}">
                <input type="hidden" name="discounts[${index}][type]" value="${discount.type}">
            `;
                container.appendChild(div);
            });
        }

        function removeDiscount(index) {
            discounts.splice(index, 1);
            renderDiscounts();
            calculateTotals();
        }

        function calculateTotals() {
            const rows = document.querySelectorAll("#productTable tbody tr");
            let subtotal = 0;

            rows.forEach(row => {
                const qty = parseFloat(row.querySelector('input[name*="[quantity]"]').value) || 0;
                const price = parseFloat(row.querySelector('input[name*="[product_total_price]"]').value) || 0;
                subtotal += qty * price;
            });

            let totalTaxAmount = 0;
            taxes.forEach(tax => {
                totalTaxAmount += subtotal * (tax.percent / 100);
            });

            let totalDiscountAmount = 0;
            discounts.forEach(discount => {
                let amount = 0;
                if (discount.type === 'percent') {
                    amount = subtotal * (discount.value / 100);
                } else {
                    amount = discount.value;
                }
                if (amount > subtotal) amount = subtotal;
                totalDiscountAmount += amount;
            });

            if (totalDiscountAmount > subtotal) totalDiscountAmount = subtotal;

            const total = subtotal + totalTaxAmount - totalDiscountAmount;

            document.getElementById("subtotalCell").innerText = `$${subtotal.toFixed(2)}`;

            const taxCell = document.getElementById("taxCell");
            if (taxes.length > 0) {
                const taxDetails = taxes.map(tax => {
                    const amount = subtotal * (tax.percent / 100);
                    return `${tax.name}: $${amount.toFixed(2)}`;
                }).join('<br>');
                taxCell.innerHTML = taxDetails;
            } else {
                taxCell.innerText = '$0.00';
            }

            const discountRow = document.getElementById("discountRow");
            const discountCell = document.getElementById("discountCell");
            if (discounts.length > 0 && totalDiscountAmount > 0) {
                discountRow.style.display = '';
                discountCell.innerHTML =
                    `-$${totalDiscountAmount.toFixed(2)} <small class="text-muted">(${discounts.map(d => d.name).join(', ')})</small>`;
            } else {
                discountRow.style.display = 'none';
                discountCell.innerHTML = '';
            }

            document.getElementById("totalCell").innerHTML = `<strong>$${total.toFixed(2)}</strong>`;
            document.getElementById("remainingTotal").innerText = `$${total.toFixed(2)}`;
            document.getElementById("subtotalInput").value = subtotal.toFixed(2);
            document.getElementById("taxTotalInput").value = totalTaxAmount.toFixed(2);
            document.getElementById("discountTotalInput").value = totalDiscountAmount.toFixed(2);
            document.getElementById("totalInput").value = total.toFixed(2);
            document.getElementById("remainingTotalInput").value = total.toFixed(2);
            const container = document.getElementById('dynamicInputsContainer');
            container.innerHTML = '';
        }

        document.querySelector("#productTable tbody").addEventListener('input', function(e) {
            if (e.target.matches('input')) {
                updateTotal(e.target);
            }
        });

        function loadExistingItems() {
            const table = document.querySelector("#productTable tbody");

            existingEstimateItems.forEach(item => {
                const qty = parseFloat(item.quantity) || 1;
                const price = parseFloat(item.price) || 0;
                const productPrice = parseFloat(item.product_price) || 0;
                const tax = parseFloat(item.tax) || 0;
                const gratuity = parseFloat(item.gratuity) || 0;
                const total = qty * productPrice;

                const row = document.createElement("tr");
                row.innerHTML = `   
                  <td><input type="text" name="products[${productIndex}][name]" class="form-control" value="${item.name}" readonly></td>
                    <td><input type="number" name="products[${productIndex}][quantity]" class="form-control" value="${qty}" oninput="updateTotal(this)" step="0.01" min="0"></td>
                    <td><input type="number" name="products[${productIndex}][price]" class="form-control" value="${price.toFixed(2)}" oninput="updateTotal(this)" step="0.01" min="0"></td>
                    <td><input type="number" name="products[${productIndex}][tax]" class="form-control" value="${tax.toFixed(2)}" oninput="updateTotal(this)" step="0.01" min="0"></td>
                    <td><input type="number" name="products[${productIndex}][gratuity]" class="form-control" value="${gratuity.toFixed(2)}" oninput="updateTotal(this)" step="0.01" min="0"></td>
                    <td><input type="number" name="products[${productIndex}][product_total_price]" class="form-control" value="${productPrice.toFixed(2)}" readonly step="0.01" min="0"></td>
                    <td class="total-cell">$${total.toFixed(2)}</td>
                    <td class="no-print"><button type="button" class="btn btn-danger btn-sm" onclick="removeRow(this)">Delete</button></td>
            `;
                table.appendChild(row);
                productIndex++;
            });
        }

        function loadExistingInstallments() {
            // Check if there is actually data to load
            if (existingEstimateInstallments.length > 0) {
                // 1. Ensure the checkbox is checked and UI is visible
                const checkbox = document.getElementById('installmentCheck'); // Replace with your actual ID
                const section = document.getElementById('installmentSection');   // Replace with your actual ID
                const container = document.getElementById('dynamicInputsContainer'); // The div where rows go
                
                if (checkbox) checkbox.checked = true;
                if (section) section.classList.remove('d-none');

                // 2. Clear container to prevent duplicates
                container.innerHTML = '';

                // 3. Loop through existing installments
                existingEstimateInstallments.forEach(item => {
                    const amount = parseFloat(item.amount) || 0;
                    const date = item.installment_date || "";

                    // We reuse your createRowHtml logic
                    // Note: installmentIndex++ happens inside createRowHtml
                    const rowHtml = createRowHtml(amount.toFixed(2), date);
                    container.insertAdjacentHTML('beforeend', rowHtml);
                });

                // 4. Recalculate the remaining balance UI
                calculateBalance();
            }
        }

        function submitSentForm() {
            document.getElementById('sentForm').submit();
        }

        window.onload = function() {
            loadExistingItems();
            renderTaxes();
            renderDiscounts();
            calculateTotals();
            loadExistingInstallments();
        };



        //installment logic 


   const checkbox = document.getElementById('installmentCheck');
const modalElement = document.getElementById('installmentModal');
const modal = new bootstrap.Modal(modalElement);
const container = document.getElementById('dynamicInputsContainer');
const section = document.getElementById('installmentSection');

// Template for a new row

// Add a simple counter or use a timestamp for the key
let installmentIndex = 0; 

function createRowHtml(amount = "",date = "") {
    installmentIndex++; // Increment to give each row a unique "ID"
    
    return `
        <div class="row mb-2 installment-row">
            <div class="col-md-5">
                <input type="number" 
                       name="installments[${installmentIndex}][amount]" 
                       class="form-control inst-amount" 
                       placeholder="Amount" 
                       value="${amount}" 
                       oninput="calculateBalance()" 
                       step="0.01" min="0" required>
            </div>
            <div class="col-md-5">
                <input type="date" 
                       name="installments[${installmentIndex}][date]" 
                       value="${date}"
                       class="form-control inst-date" 
                       min="<?= date('Y-m-d') ?>"
                       required>
            </div>
            <div class="col-md-2">
                <button type="button" class="btn btn-danger w-100 btn-remove">×</button>
            </div>
        </div>`;
}

// 1. Checkbox Toggle
checkbox.addEventListener('change', function() {
    if (this.checked) {
        modal.show();
    } else {
        section.classList.add('d-none');
        container.innerHTML = '';
        calculateBalance();
    }
});

// 2. Initial Generation from Modal
document.getElementById('generateFields').addEventListener('click', function() {
    const count = parseInt(document.getElementById('numInstallments').value);
    if (!count || count <= 0) return alert("Please enter a valid number");

    let totalAmount = parseFloat(document.getElementById('totalInput').value) || 0;
    container.innerHTML = '';
    section.classList.remove('d-none');

    // Logic: Split total by number of installments for the user
    let splitAmount = (totalAmount / count).toFixed(2);

    for (let i = 0; i < count; i++) {
        // Last row gets the remainder to avoid rounding errors
        if (i === count - 1) {
            let currentSum = splitAmount * (count - 1);
            let lastPart = totalAmount - currentSum;
            container.insertAdjacentHTML('beforeend', createRowHtml(lastPart.toFixed(2),""));
        } else {
            container.insertAdjacentHTML('beforeend', createRowHtml(splitAmount));
        }
    }
    modal.hide();
    calculateBalance();
});

// 3. Add Single Row (Auto-fills with remaining balance)
document.getElementById('addRowBtn').addEventListener('click', function () {

    let totalAmount = parseFloat(document.getElementById('totalInput').value) || 0;
    let errorBox = document.getElementById('installmentError');

    // 🚫 Validation
    if (totalAmount === 0) {
        errorBox.style.display = 'block';
        return;
    } else {
        errorBox.style.display = 'none';
    }

    let paidSoFar = 0;
    document.querySelectorAll('.inst-amount').forEach(el => {
        paidSoFar += parseFloat(el.value) || 0;
    });

    let remaining = totalAmount - paidSoFar;
    let fillValue = remaining > 0 ? remaining.toFixed(2) : "";

    container.insertAdjacentHTML('beforeend', createRowHtml(fillValue));
    calculateBalance();
});


// 4. Remove Row
container.addEventListener('click', function(e) {
    if (e.target.classList.contains('btn-remove')) {
        e.target.closest('.installment-row').remove();
        calculateBalance();
    }
});

// 5. Calculation Logic (Prevents Negative & Checks Completion)
function calculateBalance() {
    let totalAmount = parseFloat(document.getElementById('totalInput').value) || 0;
    let runningTotal = 0;
    const amounts = document.querySelectorAll('.inst-amount');

    amounts.forEach(input => {
        let val = parseFloat(input.value) || 0;

        // Condition: If this input makes the total go over, cap it
        if (runningTotal + val > totalAmount) {
            val = totalAmount - runningTotal;
            input.value = val > 0 ? val.toFixed(2) : 0;
        }
        runningTotal += val;
    });

    let remaining = totalAmount - runningTotal;
    const display = document.getElementById('remainingTotal');
    
    display.innerText = `$${remaining.toFixed(2)}`;
    
    // UI Feedback
    if (remaining > 0) {
        display.className = "fw-bold text-warning"; // Still needs more
    } else {
        display.className = "fw-bold text-success"; // Perfect balance
    }
}

// 6. Final Form Validation
document.getElementById('paymentForm').addEventListener('submit', function(e) {
    let totalAmount = parseFloat(document.getElementById('totalInput').value) || 0;
    let currentTotal = 0;
    document.querySelectorAll('.inst-amount').forEach(el => currentTotal += parseFloat(el.value) || 0);

    // Using a small margin for decimal floating point math
    if (Math.abs(totalAmount - currentTotal) > 0.01) {
        e.preventDefault();
        alert(`Validation Error: The total of all installments ($${currentTotal.toFixed(2)}) must equal the total amount ($${totalAmount.toFixed(2)}).`);
    }
});

// Cancel Modal
document.getElementById('cancelModal').addEventListener('click', () => {
    checkbox.checked = false;
    modal.hide();
});

    </script>

    <script>
        
// 1. Target your specific form ID
const estimateForm = document.getElementById('update-estimate-form');

estimateForm.addEventListener('submit', function (e) {

    const isInstallment = document.getElementById('installmentCheck').checked;
    const errorBox = document.getElementById('installmentValidationError');

    // Always reset error UI
    errorBox.style.display = 'none';

    if (isInstallment) {
        const totalAmount = parseFloat(document.getElementById('totalInput').value) || 0;
        let paidSoFar = 0;

        const amounts = document.querySelectorAll('.inst-amount');
        amounts.forEach(input => {
            paidSoFar += parseFloat(input.value) || 0;
        });
        if (amounts.length === 0) {
            e.preventDefault();
            e.stopPropagation();

            errorBox.innerText = 'Please schedule a payment first.';
            errorBox.style.display = 'block';

            document.getElementById('installmentSection')
                .scrollIntoView({ behavior: 'smooth' });
            return;
        }
        const difference = Math.abs(totalAmount - paidSoFar);

        if (difference > 0.01) {
            e.preventDefault();
            e.stopPropagation();

            errorBox.innerText = 'Installment total must match the estimate total.';
            errorBox.style.display = 'block';

            const remainingDisplay = document.getElementById('remainingTotal');
            if (remainingDisplay) remainingDisplay.style.color = 'red';

            document.getElementById('installmentSection')
                .scrollIntoView({ behavior: 'smooth' });
        }
    }
});


estimateForm.addEventListener('keydown', function(e) {
    if (e.key === 'Enter') {
        // If the user is NOT in a textarea, prevent the default submit action
        if (e.target.tagName !== 'TEXTAREA') {
            e.preventDefault();
            return false;
        }
    }
});

function cancelModalNumberOfPayments() {
    // 1. Uncheck the "Installment" checkbox
    const checkbox = document.getElementById('installmentCheck');
    if (checkbox) {
        checkbox.checked = false;
    }

    // 2. Clear the input inside the modal
    const input = document.getElementById('numInstallments');
    if (input) {
        input.value = '';
    }

    // 3. Hide the Modal and Clean up the Backdrop
    // We use the "Force" method since the standard .modal('hide') was failing
    $('#installmentModal').modal('hide');
    
    // Force removal of the dark overlay and restore scrolling
    $('body').removeClass('modal-open');
    $('.modal-backdrop').remove();
    $('body').css('padding-right', '0'); 
}
    </script>



<script src="https://cdn.ckeditor.com/ckeditor5/39.0.1/classic/ckeditor.js"></script>

<script>
    document.addEventListener("DOMContentLoaded", function() {
        // Target all textareas with the 'editor' class
        const allEditors = document.querySelectorAll('.editor');
        
        allEditors.forEach(textarea => {
            ClassicEditor
                .create(textarea, {
                    toolbar: {
                        items: [
                            'heading', '|',
                            'bold', 'italic', 'strikethrough', 'link', '|',
                            'bulletedList', 'numberedList', '|',
                            'undo', 'redo'
                        ]
                    }
                })
                .catch(error => {
                    console.error('CKEditor initialization failed:', error);
                });
        });
    });
</script>

<style>
    /* Adjusts the height of the editor box */
    .ck-editor__editable_inline {
        min-height: 200px;
        background-color: white !important;
    }

    /* Ensures the editor matches Bootstrap's border style */
    .ck.ck-editor__main>.ck-editor__editable {
        border-color: #dee2e6 !important;
        box-shadow: none !important;
    }

    /* Style for the preview area */
    .preview-content {
        padding: 10px;
        border: 1px dashed #ccc;
        background: #f9f9f9;
        margin-top: 10px;
    }
</style>



    
@endsection
