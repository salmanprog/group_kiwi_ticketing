{{-- @extends('portal.master')

@section('content')

<section class="main-content" style="background:#f8faf9; padding:40px; min-height:100vh;">

    <div class="container">
        <div class="card" style="border:none; border-radius:12px; box-shadow:0 4px 20px rgba(0,0,0,0.08);">

            <!-- Header -->
            <div class="card-header" style="background:#ffffff; padding:20px 30px; border-bottom:1px solid #e5e7eb;">
                <h3 style="margin:0; font-weight:600;">Hold Ticket Details</h3>
            </div>

            <!-- Body -->
            <div class="card-body" style="padding:30px;">

                <!-- Top Section -->
                <div class="row mb-4">
                    <div class="col-md-4">
                        <label style="font-weight:600;">Estimate</label>
                        <input type="text" class="form-control" value="{{ ($Estimates) ? $Estimates->slug : '' }}" readonly>
                    </div>
                    <div class="col-md-4">
                        <label style="font-weight:600;">Hold Date</label>
                        <input type="date" class="form-control" value="{{ ($record->hold_date) ? $record->hold_date : '' }}" readonly>
                    </div>
                    <div class="col-md-4">
                        <label style="font-weight:600;">Expiry Date</label>
                        <input type="date" class="form-control" value="{{ ($record->expiry_date) ? $record->expiry_date : '' }}" readonly>
                    </div>
                </div>

                <!-- Selected Products Table -->
                <div style="margin-top:40px;">
                    <h5 style="font-weight:600; margin-bottom:20px;">Product Details</h5>

                    <div class="table-responsive">
                        <table class="table table-bordered">
                            <thead style="background:#f3f4f6;">
                                <tr>
                                    <th>Product Name</th>
                                    <th width="120">Quantity</th>
                                </tr>
                            </thead>
                            <tbody>
                                @if($record->user_hold_ticket_items->isEmpty())
                                    <tr>
                                        <td colspan="2" class="text-center">No record found</td>
                                    </tr>
                                @else
                                    @foreach($record->user_hold_ticket_items as $p)
                                        <tr>
                                            <td>{{ $p->name }}</td>
                                            <td>{{ $p->quantity }}</td>
                                        </tr>
                                    @endforeach
                                @endif
                            </tbody>
                        </table>
                    </div>

                </div>

            </div>
        </div>
    </div>

</section>

@endsection --}}


@extends('portal.master')

@section('content')
    @push('stylesheets')
        <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
    @endpush

    <style>
        /* --- Matching UI Styling from Create Page --- */
        .main-content {
            background: #f8faf9;
            min-height: 100vh;
            padding: 30px;
            padding-top: 90px;
        }

        .custfor-flex-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .card {
            border: none;
            border-radius: 12px;
            box-shadow: 0 4px 20px rgba(160, 194, 66, 0.1);
            margin-bottom: 30px;
            background: #fff;
            overflow: hidden;
        }

        .card-header {
            background: #ffffff;
            border-bottom: 1px solid #e5e7eb;
            padding: 20px 30px;
            color: #1f2937;
        }

        .header-content h3 {
            margin: 0;
            font-weight: 600;
            font-size: 18px;
        }

        .header-actions .btn-outline {
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

        .btn-outline:hover {
            background: rgba(255, 255, 255, 0.3);
            color: white;
            transform: translateY(-1px);
        }

        .card-body {
            padding: 30px;
        }

        .form-section {
            background: #ffffff;
            border: 1px solid #eaeaea;
            border-radius: 8px;
            padding: 25px;
            margin-bottom: 20px;
            transition: transform 0.2s ease;
        }

        .form-section:hover {
            transform: translateY(-1px);
        }

        .section-header {
            display: flex;
            align-items: center;
            gap: 12px;
            margin-bottom: 25px;
            padding-bottom: 15px;
            border-bottom: 1px solid #e0e6e3;
        }

        .section-header h5 {
            margin: 0;
            color: #2c3e50;
            font-weight: 600;
            font-size: 1.1rem;
            flex: 1;
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

        .form-group {
            margin-bottom: 25px;
        }

        .form-label {
            display: flex;
            align-items: center;
            gap: 10px;
            font-weight: 600;
            color: #2c3e50;
            margin-bottom: 8px;
            font-size: 0.95rem;
        }

        .form-control {
            border: 1px solid #dce4e0;
            border-radius: 6px;
            padding: 12px 15px;
            font-size: 0.95rem;
            transition: all 0.3s ease;
            background: #fff;
        }

        .form-control:focus {
            border-color: #A0C242 !important;
            box-shadow: 0 0 0 3px rgba(160, 194, 66, 0.1);
            outline: none;
        }

        .form-control[readonly] {
            background-color: #f9fafb;
            cursor: not-allowed;
        }

        .table {
            margin-bottom: 0;
        }

        .table thead th {
            background: #f3f4f6;
            border-bottom: 2px solid #e5e7eb;
            color: #374151;
            font-weight: 600;
            font-size: 0.9rem;
            padding: 12px;
        }

        .table tbody td {
            padding: 12px;
            vertical-align: middle;
        }

        .table tbody tr:hover {
            background-color: #f9fafb;
        }

        .btn-primary {
            background: linear-gradient(135deg, #A0C242 0%, #8AB933 100%);
            color: white;
            box-shadow: 0 2px 8px rgba(160, 194, 66, 0.3);
            border: none;
            padding: 10px 25px;
            border-radius: 6px;
            font-weight: 600;
            font-size: 14px;
            transition: all 0.2s ease;
            cursor: pointer;
        }

        .btn-primary:hover {
            transform: translateY(-1px);
            box-shadow: 0 4px 12px rgba(160, 194, 66, 0.4);
        }

        /* Responsive */
        @media (max-width: 768px) {
            .main-content {
                padding: 15px;
                padding-top: 90px;
            }

            .custfor-flex-header {
                flex-direction: column;
                gap: 15px;
                text-align: center;
            }

            .header-content {
                justify-content: center;
            }

            .card-body {
                padding: 20px;
            }

            .form-section {
                padding: 20px;
            }

            .section-header {
                flex-direction: column;
                text-align: center;
                gap: 10px;
            }
        }
    </style>

    <section class="main-content">
        <div class="row justify-content-center">
            <div class="col-lg-10">
                @include('portal.flash-message')
                <div class="card">
                    <div class="card-header custfor-flex-header">
                        <div class="header-content">
                            <h3>Hold Ticket Details</h3>
                        </div>
                        <div class="header-actions">
                            <a href="{{ route('hold-tickets.index') }}" class="btn btn-outline">
                                Back to List
                            </a>
                        </div>
                    </div>
                    <div class="card-body">
                        <!-- Ticket Information Section -->
                        <div class="form-section">
                            <div class="section-header">
                                <h5>Ticket Information</h5>
                                <span class="section-badge">Read Only</span>
                            </div>

                            <div class="row">
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label class="form-label">
                                            Estimate
                                            <span class="required">*</span>
                                        </label>
                                        <input type="text" class="form-control" 
                                            value="{{ ($Estimates) ? $Estimates->slug : '' }}" readonly>
                                    </div>
                                </div>

                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label class="form-label">
                                            Hold Date
                                            <span class="required">*</span>
                                        </label>
                                        <input type="date" class="form-control" 
                                            value="{{ ($record->hold_date) ? $record->hold_date : '' }}" readonly>
                                    </div>
                                </div>

                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label class="form-label">
                                            Expiry Date
                                            <span class="required">*</span>
                                        </label>
                                        <input type="date" class="form-control" 
                                            value="{{ ($record->expiry_date) ? $record->expiry_date : '' }}" readonly>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Product Details Section -->
                        <div class="form-section">
                            <div class="section-header">
                                <h5>Product Details</h5>
                                <span class="section-badge">Product List</span>
                            </div>

                            <div class="table-responsive">
                                <table class="table table-bordered">
                                    <thead>
                                        <tr>
                                            <th>Product Name</th>
                                            <th width="120">Quantity</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @if($record->user_hold_ticket_items->isEmpty())
                                            <tr class="no-record">
                                                <td colspan="2" class="text-center">No record found</td>
                                            </tr>
                                        @else
                                            @foreach($record->user_hold_ticket_items as $p)
                                                <tr>
                                                    <td>{{ $p->name }}</td>
                                                    <td>{{ $p->quantity }}</td>
                                                </tr>
                                            @endforeach
                                        @endif
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

@endsection