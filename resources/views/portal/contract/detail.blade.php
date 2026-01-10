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
            background: #F8F9FA;
            border-bottom: 1px solid var(--primary-color);
            color: var(--secondary-color);
            font-weight: 600;
            padding: 12px 15px;
            border-radius: 0 !important;
            font-size: 15px;
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
            border: none;
            font-size: 14px;
            width: 100%;
            margin-bottom: 8px;
            box-sizing: border-box;
            text-align: center;
            display: block;
        }

        .btn-primary {
            background: var(--primary-color) !important;
            border: 1px solid var(--primary-color) !important;
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

                <!-- Contract Header -->
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
                                <i class="fas fa-check-circle me-1"></i>Client accepted this contract
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
                        <h4><i class="fas fa-building me-2"></i>Company</h4>
                        <p>
                            <strong>{{ $record->company->name }}</strong><br>
                            {{ $record->company->address }}<br>
                            <i class="fas fa-envelope me-1"></i> {{ $record->company->email }}<br>
                            <i class="fas fa-phone me-1"></i> {{ $record->company->mobile_no }}
                        </p>
                    </div>

                    <div class="address-box">
                        <h4><i class="fas fa-users me-2"></i>Organization</h4>
                        <p>
                            <strong>{{ $record->organization->name }}</strong><br>
                            <i class="fas fa-envelope me-1"></i> {{ $record->organization->email ?? '-' }}<br>
                            <i class="fas fa-phone me-1"></i> {{ $record->organization->mobile_no ?? '-' }}
                        </p>
                    </div>

                    <div class="address-box">
                        <h4><i class="fas fa-user me-2"></i>Client</h4>
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
                        <i class="fas fa-file-invoice-dollar me-2"></i>Estimates Linked to this Contract
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
                                                        class="text-decoration-none theme-text fw-bold">
                                                        <i class="fas fa-external-link-alt me-1"></i>
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
                                                        <small class="text-muted"><em>(Adjusted)</em></small>
                                                    @endif
                                                </td>
                                                <td class="fw-semibold">${{ number_format($estimate->total, 2) }}</td>
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
                                <a href="{{ route('estimate.create', ['contract' => encrypt($record->slug)]) }}"
                                    class="btn btn-primary">
                                    <i class="fas fa-plus me-1"></i>Add New Estimate
                                </a>
                            </div>
                        @endif
                    </div>
                </div>

                <!-- Invoices Section -->
                <div class="card">
                    <div class="card-header">
                        <i class="fas fa-receipt me-2"></i>Invoices Generated
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
                                                    class="fw-bold text-decoration-none theme-text">
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

                    <!-- Terms & Notes -->
                    <div class="card">
                        <div class="card-header">
                            <i class="fas fa-clipboard-list me-2"></i>Terms & Notes
                        </div>
                        <div class="card-body">
                            <h5 class="fw-bold theme-text mb-3">Terms</h5>
                            <p class="mb-4">{{ $record->terms ?: 'No terms specified.' }}</p>

                            <h5 class="fw-bold theme-text mb-3">Notes</h5>
                            <p>{{ $record->notes ?: 'No notes available.' }}</p>
                        </div>
                    </div>

                    <!-- Company Edit Section -->
                    @if (Auth::user()->user_type == 'company')
                        <div class="card">
                            <div class="card-header">
                                <i class="fas fa-edit me-2"></i>Edit Contract Details
                            </div>
                            <div class="card-body">
                                <form action="{{ route('contract.update-contract', $record->slug) }}" method="POST">
                                    @csrf
                                    <div class="row">
                                        <div class="col-md-12 mb-3">
                                            <label class="form-label fw-bold">Event Date</label>
                                            <input type="date" class="form-control" value="{{ $record->event_date }}"
                                                name="event_date" required>
                                        </div>
                                    </div>

                                    <div class="mb-3">
                                        <label class="form-label fw-bold">Terms</label>
                                        <textarea name="terms" rows="4" class="form-control" placeholder="Enter contract terms...">{{ $record->terms }}</textarea>
                                    </div>

                                    <div class="mb-3">
                                        <label class="form-label fw-bold">Notes</label>
                                        <textarea name="notes" rows="3" class="form-control" placeholder="Enter any additional notes...">{{ $record->notes }}</textarea>
                                    </div>

                                    <button type="submit" class="btn btn-primary">
                                        <i class="fas fa-save me-1"></i>Save Changes
                                    </button>
                                </form>
                            </div>
                        </div>
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
        </section>

        <!-- Bootstrap & Font Awesome -->
        <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    @endsection
