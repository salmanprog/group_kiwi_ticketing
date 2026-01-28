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


</section>


    
@endsection
