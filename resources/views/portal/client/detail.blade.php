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
            margin-bottom: 25px;
            color: #1f2937;
            font-weight: 600;
            font-size: 1.1rem;
            flex: 1;
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
            font-weight: 500;
            color: #1f2937;
            margin-bottom: 8px;
            font-size: 0.95rem;
            display: block;
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

        span.badge.status-sent {
            color: red;
        }

        .form-section.for-cust-css {
            padding-top: 30px;
        }

        .btn.btn-primary.mt-2 {
            float: right;
            background: #9FC23F !important;
            border: 1px solid #fff !important;
            border-radius: 5px;
            padding: 6px 15px;
            color: white;
            text-decoration: none;
            font-weight: 600;
            transition: all 0.3s ease;
            display: inline-flex;
            align-items: center;
            gap: 8px;
            font-size: 14px;
        }

        .form-section {
            background: #ffffff;
            border: 1px solid #e5e7eb;
            border-radius: 8px;
            padding: 25px;
            margin-bottom: 20px;
        }

        .section-badge {
            background: #f3f4f6;
            color: #374151;
            padding: 4px 10px;
            border-radius: 4px;
            font-size: 0.8rem;
            font-weight: 600;
            border: 1px solid #e5e7eb;
        }

        .section-badge a {
            text-decoration: none;
        }

        .for-tbls-css tbody tr td .bg-success {
            background-color: #7bcb4d !important;
            border-color: #7bcb4d !important;
            color: #ffffff !important;
        }

        .for-tbls-css thead tr th {
            font-weight: 700;
            color: #575962;
            font-size: 14px;
            line-height: 24px;
        }

        .for-tbls-css tbody tr td {
            font-weight: 400;
            color: #575962;
            font-size: 14px;
            line-height: 24px;
        }

        .view-value {
            font-weight: 400;
            color: #575962;
            font-size: 14px;
            line-height: 24px;
        }

        .for-tbls-css tbody tr td a {
            text-decoration: none;
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
                        <i class="fas fa-file-contract me-2"></i>Contact Details
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
                </div>



                <!-- Parties Section -->
                <div class="address-section">
                    <div class="address-box">
                        <h4><i class="fas fa-users me-2"></i>Account Info</h4>
                        <p>
                            <strong>{{ $organizations->name }}</strong><br>
                            <i class="fas fa-envelope me-1"></i> {{ $organizations->email ?? '-' }}<br>
                            <i class="fas fa-phone me-1"></i> {{ $organizations->mobile_no ?? '-' }}
                        </p>
                    </div>

                    <div class="address-box">
                        <h4><i class="fas fa-user me-2"></i>Contact Info</h4>
                        <p>
                            <strong>{{ $record->first_name }} {{ $record->last_name }}</strong><br>
                            <i class="fas fa-envelope me-1"></i> {{ $record->email ?? '-' }}<br>
                            <i class="fas fa-phone me-1"></i> {{ $record->mobile_no ?? '-' }}
                        </p>
                    </div>
                </div>

                <!-- Address Details Section -->
                <div class="address-section">
                    <div class="address-box">
                        <h4><i class="fas fa-users me-2"></i>Address Info</h4>
                        <div class="row">
                            <div class="col-md-6">
                                <label class="form-label">City</label>
                                <div class="view-value">{{ $record->organization->city ?? '—' }}</div>
                            </div>

                            <div class="col-md-6">
                                <label class="form-label">State</label>
                                <div class="view-value">{{ $record->organization->state ?? '—' }}</div>
                            </div>

                            <div class="col-md-6">
                                <label class="form-label">Country</label>
                                <div class="view-value">{{ $record->organization->country ?? '—' }}</div>
                            </div>

                            <div class="col-md-6">
                                <label class="form-label">Zip</label>
                                <div class="view-value">{{ $record->organization->zip ?? '—' }}</div>
                            </div>

                            <div class="col-md-12">
                                <label class="form-label">Address Line 1</label>
                                <div class="view-value">{{ $record->organization->address_one ?? '—' }}</div>
                            </div>

                            <div class="col-md-12">
                                <label class="form-label">Address Line 2</label>
                                <div class="view-value">{{ $record->organization->address_two ?? '—' }}</div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Contact Details Section -->
                <div class="address-section">
                    <div class="address-box">
                        <h4><i class="fas fa-users me-2"></i>Contact Info</h4>
                        <div class="row">
                            <div class="col-md-6">
                                <label class="form-label">First Name</label>
                                <div class="view-value">{{ $record->first_name ?? '—' }}</div>
                            </div>

                            <div class="col-md-6">
                                <label class="form-label">Last Name</label>
                                <div class="view-value">{{ $record->last_name ?? '—' }}</div>
                            </div>

                            <div class="col-md-6">
                                <label class="form-label">Email</label>
                                <div class="view-value">{{ $record->email ?? '—' }}</div>
                            </div>

                            <div class="col-md-6">
                                <label class="form-label">Phone</label>
                                <div class="view-value">{{ $record->mobile_no ?? '—' }}</div>
                            </div>

                            <div class="col-md-6">
                                <label class="form-label">Fax</label>
                                <div class="view-value">{{ $record->fax ?? '—' }}</div>
                            </div>

                            <div class="col-md-6">
                                <label class="form-label">Rep</label>
                                <div class="view-value">{{ $record->rep ?? '—' }}</div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Contract Details Section -->
                <div class="address-section">
                    <div class="address-box">
                        <h4><i class="fas fa-users me-2"></i>Contract Info</h4>
                        <div class="row">
                            <div class="col-md-6">
                                <label class="form-label">Ticket Rate</label>
                                <div class="view-value">{{ $record->ticket_rate ?? '—' }}</div>
                            </div>

                            <div class="col-md-6">
                                <label class="form-label">Catering Menu</label>
                                <div class="view-value">{{ $record->catering_menu ?? '—' }}</div>
                            </div>

                            <div class="col-md-6">
                                <label class="form-label">Catering Price</label>
                                <div class="view-value">{{ $record->catering_price ?? '—' }}</div>
                            </div>

                            <div class="col-md-6">
                                <label class="form-label">Deposit Amount</label>
                                <div class="view-value">{{ $record->deposite_amount ?? '—' }}</div>
                            </div>

                            <div class="col-md-6">
                                <label class="form-label">Hours</label>
                                <div class="view-value">{{ $record->hours ?? '—' }}</div>
                            </div>

                            <div class="col-md-6">
                                <label class="form-label">Alt Contacts</label>
                                <div class="view-value">{{ $record->alt_contact ?? '—' }}</div>
                            </div>

                            <div class="col-md-12">
                                <label class="form-label">Notes / History</label>
                                <div class="view-value">{{ $record->note_history ?? '—' }}</div>
                            </div>

                            <div class="col-md-12">
                                <label class="form-label">Contract Status</label>
                                <div class="view-value">{{ ucfirst(str_replace('_', ' ', $record->contract_status)) }}
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Notes DETAILS -->
                <div class="form-section">
                    <div class="section-header">
                        <h5>Notes</h5>
                        <small id="notes-status" class="text-success"></small>
                    </div>

                    <div class="row">
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
                                                    {{ $log->created_at->format('d M Y, h:i A') }}
                                                </small>
                                            </div>
                                        </li>
                                    @endforeach
                                </ul>
                            @else
                                <p class="text-muted">No activity found.</p>
                            @endif
                        </div>
                        <div class="col-md-12">
                            <label class="form-label">Add Notes</label>
                            <input type="hidden" name="client_id" id="client_id" value="{{ $record->client_id }}">
                            <input type="hidden" name="organization_id" id="organization_id"
                                value="{{ $record->organization_id }}">
                            <textarea id="notesTextarea" class="form-control" rows="4" readonly placeholder="Click here to add notes...">{{ $organizations->notes ?? '' }}</textarea>

                            <button id="saveNotesBtn" class="btn btn-primary mt-2 d-none">
                                Save Notes
                            </button>
                        </div>
                    </div>
                </div>

                <!-- Linked Estimates -->
                <div class="form-section for-cust-css">
                    <div class="section-header tbl-heading-css">
                        <h5>Estimates Information</h5>
                        <span class="section-badge"><a href="{{ route('estimate.create') }}" target="_blank">Add New
                                Estimate</a></span>
                    </div>

                    <div class="table-responsive for-tbls-css">
                        <table class="table table-bordered table-striped">
                            <thead>
                                <tr>
                                    <th>#</th>
                                    <th>Estimate No</th>
                                    <th>Status</th>
                                    <th>Estimate Date</th>
                                    <th>Refrence</th>
                                    <th>Subtotal</th>
                                    <th>Amount</th>
                                    <th>Valid Until</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($organizations->estimate as $key => $estimate)
                                    <tr>
                                        <td>{{ $key + 1 }}</td>
                                        <td><a href="{{ route('estimate.show', ['estimate' => $estimate->slug]) }}"
                                                target="_blank">
                                                {{ $estimate->estimate_number ?? '-' }}
                                            </a></td>
                                        <td>{{ $estimate->status ?? '-' }}</td>
                                        <td>{{ $estimate->issue_date ?? '-' }}</td>
                                        <td>-</td>
                                        <td>
                                            {{ isset($estimate->subtotal) ? number_format($estimate->subtotal, 2) : '-' }}
                                        </td>
                                        <td>
                                            {{ isset($estimate->total) ? number_format($estimate->total, 2) : '-' }}
                                        </td>
                                        <td>{{ $estimate->valid_until ?? '-' }}</td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="8" class="text-center text-muted">
                                            No estimates found
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>

                <!-- Contract INFORMATION -->
                <div class="form-section for-cust-css">
                    <div class="section-header tbl-heading-css">
                        <h5>Contract Information</h5>
                        <span class="section-badge">
                            <a href="{{ route('estimate.create') }}" target="_blank">Add New Contract</a>
                        </span>
                    </div>

                    <div class="table-responsive for-tbls-css">
                        <table class="table table-bordered table-striped">
                            <thead>
                                <tr>
                                    <th>#</th>
                                    <th>Contract No</th>
                                    <th>Name</th>
                                    <th>Status</th>
                                    <th>Total</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($organizations->contract as $key => $contract)
                                    <tr>
                                        <td>{{ $key + 1 }}</td>

                                        <td>
                                            <a href="{{ route('contract.show', ['contract' => $contract->slug]) }}"
                                                target="_blank">
                                                {{ $contract->contract_number ?? '-' }}
                                            </a>
                                        </td>
                                        <td>{{ $contract->client->name ?? '-' }}</td>
                                        <td>
                                            @if ($contract->is_accept)
                                                <span class="badge bg-success">Accepted</span>
                                            @else
                                                <span class="badge bg-warning">Pending</span>
                                            @endif
                                        </td>

                                        <td>{{ number_format($contract->total ?? 0, 2) }}</td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="5" class="text-center text-muted">
                                            No contract found
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
                <!-- Invoice INFORMATION -->
                <div class="form-section for-cust-css">
                    <div class="section-header tbl-heading-css">
                        <h5>Invoice Information</h5>
                        <span class="section-badge">
                            <a href="{{ route('estimate.create') }}" target="_blank">Add New Invoice</a>
                        </span>
                    </div>

                    <div class="table-responsive for-tbls-css">
                        <table class="table table-bordered table-striped">
                            <thead>
                                <tr>
                                    <th>#</th>
                                    <th>Invoice No</th>
                                    <th>Status</th>
                                    <th>Invoice Date</th>
                                    <th>Due Date</th>
                                    <th>Reference</th>
                                    <th>Subtotal</th>
                                    <th>Amount</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($invoices as $key => $invoice)
                                    <tr>
                                        <td>{{ $key + 1 }}</td>
                                        <td>
                                            <a href="{{ route('invoice.show', $invoice->slug) }}" target="_blank">
                                                {{ $invoice->invoice_number ?? '-' }}
                                            </a>
                                        </td>
                                        <td>{{ $invoice->status ?? '-' }}</td>
                                        <td>{{ $invoice->issue_date ?? '-' }}</td>
                                        <td>{{ $invoice->due_date ?? '-' }}</td>
                                        <td>{{ $invoice->reference ?? '-' }}</td>
                                        <td>{{ number_format($invoice->subtotal ?? 0, 2) }}</td>
                                        <td>{{ number_format($invoice->total ?? 0, 2) }}</td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="8" class="text-center text-muted">
                                            No invoices found
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
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
            const orgId = document.getElementById('organization_id').value;

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

                fetch("{{ route('organization.notes.save') }}", {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': "{{ csrf_token() }}"
                        },
                        credentials: 'same-origin', // ✅ important to send session
                        body: JSON.stringify({
                            notes: textarea.value,
                            client_id: clientId,
                            organization_id: orgId
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
