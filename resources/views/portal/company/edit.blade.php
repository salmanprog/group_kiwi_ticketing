{{-- @extends('portal.master')
@section('content')
    <section class="main-content">
        <div class="row">
            <div class="col-sm-12">
                @include('portal.flash-message')
                <div class="card">
                    <div class="card-header card-default">
                        Update Company Status
                    </div>
                    <div class="container mt-4">
                        <form method="post"
                            action="{{ route('company-management.update', ['company_management' => $record->slug]) }}"
                            enctype="multipart/form-data">
                            {{ csrf_field() }}
                            <input type="hidden" name="_method" value="PUT">
                            <div class="card mb-4">
                                <div class="card-header">
                                    <h5>Company Details</h5>
                                </div>
                                <div class="card-body">
                                    <div class="row">
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label>Company Name</label>
                                                <input required type="text" name="company_name" class="form-control"
                                                    value="{{ $record->name }}" readonly>
                                            </div>
                                        </div>
                                    </div>
                                      <div class="row">
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label>Status</label>
                                                <select name="status" class="form-control">
                                                    <option value="1" {{ $record->status == 1 ? 'selected' : '' }}>Active</option>
                                                    <option value="0" {{ $record->status == 0 ? 'selected' : '' }}>Disabled</option>
                                                </select>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="form-group mt-3 col-md-12 text-right">
                                            <button class="btn btn-success">Submit</button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </form>
                    </div>

                </div>
            </div>
        </div>
        @include('portal.footer')
    </section>
@endsection --}}




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
            font-size: 14px !important;
            line-height: 1.4;
            background-color: #f5f7fa;
            color: var(--text-color);
        }

        .main-content {
            background: #f8faf9;
            min-height: 100vh;
            padding: 30px;
            padding-top: 90px;
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

        .card-header h3 {
            margin: 0;
            font-weight: 600;
            font-size: 18px;
        }

        .card-body {
            padding: 30px;
        }

        .form-group {
            margin-bottom: 25px;
        }

        label {
            font-weight: 600;
            color: var(--secondary-color);
            margin-bottom: 8px;
            font-size: 15px;
            display: block;
        }

        .form-control {
            border: 1px solid #dce4e0;
            border-radius: 6px;
            padding: 12px 15px;
            font-size: 14px;
            transition: all 0.3s ease;
            background: #fff;
        }

        .form-control:focus {
            border-color: #A0C242 !important;
            box-shadow: 0 0 0 3px rgba(160, 194, 66, 0.1);
            outline: none;
        }

        .form-select {
            cursor: pointer;
        }

        .btn {
            padding: 10px 25px;
            border-radius: 6px;
            font-weight: 600;
            font-size: 14px;
            transition: all 0.2s ease;
            border: 1px solid transparent;
            cursor: pointer;
            text-decoration: none;
        }

        .btn-primary {
            background: linear-gradient(135deg, #A0C242 0%, #8AB933 100%);
            color: white;
            box-shadow: 0 2px 8px rgba(160, 194, 66, 0.3);
        }

        .btn-primary:hover {
            transform: translateY(-1px);
            box-shadow: 0 4px 12px rgba(160, 194, 66, 0.4);
        }

        .btn-outline-secondary {
            background: #ffffff;
            border-color: #d1d5db;
            color: #374151;
        }

        .btn-outline-secondary:hover {
            background: #f9fafb;
            border-color: #9ca3af;
        }

        .company-info {
            background: #ffffff;
            border: 1px solid #eaeaea;
            border-radius: 8px;
            padding: 25px;
            margin-bottom: 25px;
        }

        .company-info h5 {
            color: #2c3e50;
            margin-bottom: 15px;
            font-weight: 600;
            font-size: 1.1rem;
        }

        .company-details {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
            gap: 15px;
            margin-top: 15px;
        }

        .company-detail-item {
            display: flex;
            flex-direction: column;
        }

        .company-detail-label {
            font-size: 12px;
            color: #7f8c8d;
            font-weight: 500;
            margin-bottom: 4px;
        }

        .company-detail-value {
            font-weight: 600;
            color: #2c3e50;
            font-size: 14px;
        }

        .status-badge {
            display: inline-block;
            padding: 4px 12px;
            border-radius: 20px;
            font-size: 12px;
            font-weight: 600;
        }

        .status-badge.active {
            background: #f3f4f6;
            color: #374151;
            border: 1px solid #e5e7eb;
        }

        .status-badge.disabled {
            background: #f3f4f6;
            color: #374151;
            border: 1px solid #e5e7eb;
        }

        .form-text {
            color: #7f8c8d;
            font-size: 13px;
            margin-top: 5px;
            display: block;
        }

        .button-group {
            display: flex;
            gap: 15px;
            justify-content: flex-start;
            padding-top: 20px;
            margin-top: 20px;
            border-top: 1px solid #eaeaea;
        }

        @media (max-width: 768px) {
            .main-content {
                padding: 15px;
                padding-top: 90px;
            }

            .card-body {
                padding: 20px;
            }

            .btn {
                width: 100%;
                margin-bottom: 10px;
            }

            .company-details {
                grid-template-columns: 1fr;
            }

            .button-group {
                flex-direction: column;
            }
        }
    </style>

    <section class="main-content">
        <div class="row justify-content-center">
            <div class="col-lg-8">
                @include('portal.flash-message')

                <div class="card">
                    <div class="card-header">
                        <h3>Update Company Status</h3>
                    </div>
                    <div class="card-body">
                        <!-- Company Information -->
                        <div class="company-info">
                            <h5>Company Information</h5>
                            <div class="company-details">
                                <div class="company-detail-item">
                                    <span class="company-detail-label">Company Name</span>
                                    <span class="company-detail-value">{{ $record->name }}</span>
                                </div>
                                <div class="company-detail-item">
                                    <span class="company-detail-label">Current Status</span>
                                    <span class="company-detail-value">
                                        @if ($record->status == 1)
                                            <span class="status-badge">Active</span>
                                        @else
                                            <span class="status-badge">Disabled</span>
                                        @endif
                                    </span>
                                </div>
                            </div>
                        </div>

                        <form method="post"
                            action="{{ route('company-management.update', ['company_management' => $record->slug]) }}"
                            enctype="multipart/form-data">
                            {{ csrf_field() }}
                            <input type="hidden" name="_method" value="PUT">

                            <div class="row">
                                <div class="col-md-12">
                                    <div class="form-group">
                                        <label for="statusSelect">Status</label>
                                        <select name="status" class="form-control form-select" id="statusSelect">
                                            <option value="1" {{ $record->status == 1 ? 'selected' : '' }}>
                                                Active
                                            </option>
                                            <option value="0" {{ $record->status == 0 ? 'selected' : '' }}>
                                                Disabled
                                            </option>
                                        </select>
                                        <div class="form-text">
                                            Active companies can operate normally, while disabled companies cannot access
                                            the system.
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="button-group">
                                <button type="submit" class="btn btn-primary">
                                    Update Status
                                </button>
                                <a href="{{ route('company-management.index') }}" class="btn btn-outline-secondary">
                                    Back to List
                                </a>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>

        @include('portal.footer')
    </section>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const statusSelect = document.getElementById('statusSelect');

            // Add visual feedback when status changes
            statusSelect.addEventListener('change', function() {
                if (this.value === '1') {
                    this.style.borderLeft = '4px solid #A0C242';
                } else {
                    this.style.borderLeft = '4px solid #e74c3c';
                }
            });

            // Trigger change event on page load
            statusSelect.dispatchEvent(new Event('change'));
        });
    </script>
@endsection
