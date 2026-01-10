{{-- @extends('portal.master')
@section('content') 
    <section class="main-content">
        <div class="row">
            <div class="col-sm-12">
                @include('portal.flash-message')
                <div class="card">
                    <div class="card-header card-default">
                        Edit User
                    </div>
                    <div class="card-body">
                        <form method="post" action="{{ route('manager-management.update',['manager_management' => $record->slug]) }}" enctype="multipart/form-data">
                            {{ csrf_field() }}
                            <input type="hidden" name="_method" value="PUT">
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
                            <div class="form-group">
                                <button class="btn btn-primary">Submit</button>
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

        .alert {
            border-radius: 8px;
            border: none;
            padding: 15px 20px;
            margin-bottom: 25px;
        }

        .alert-success {
            background: linear-gradient(135deg, #d4edda, #c3e6cb);
            color: #155724;
            border-left: 4px solid #28a745;
        }

        .alert-danger {
            background: linear-gradient(135deg, #f8d7da, #f5c6cb);
            color: #721c24;
            border-left: 4px solid #dc3545;
        }

        .form-container {
            max-width: 800px;
            margin: 0 auto;
        }

        .status-badge {
            display: inline-block;
            padding: 4px 12px;
            border-radius: 20px;
            font-size: 12px;
            font-weight: 600;
            margin-left: 10px;
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

            .card-header {
                padding: 20px;
            }
        }

        .user-info {
            background: #ffffff;
            border: 1px solid #eaeaea;
            border-radius: 8px;
            padding: 25px;
            margin-bottom: 25px;
        }

        .user-info h5 {
            color: #2c3e50;
            margin-bottom: 15px;
            font-weight: 600;
            font-size: 1.1rem;
        }

        .user-details {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
            gap: 15px;
            margin-top: 15px;
        }

        .user-detail-item {
            display: flex;
            flex-direction: column;
        }

        .user-detail-label {
            font-size: 12px;
            color: #7f8c8d;
            font-weight: 500;
            margin-bottom: 4px;
        }

        .user-detail-value {
            font-weight: 600;
            color: #2c3e50;
            font-size: 14px;
        }

        /* Form Help Text */
        .form-text {
            color: #7f8c8d;
            font-size: 13px;
            margin-top: 5px;
            display: block;
        }

        /* Button Group */
        .button-group {
            display: flex;
            gap: 15px;
            justify-content: flex-start;
            padding-top: 20px;
            margin-top: 20px;
            border-top: 1px solid #eaeaea;
        }
    </style>

    <section class="main-content">
        <div class="row justify-content-center">
            <div class="col-lg-8">
                <div class="form-container">
                    @include('portal.flash-message')

                    <div class="card">
                        <div class="card-header">
                            <h3>Edit Manager Status</h3>
                        </div>
                        <div class="card-body">
                            <!-- User Information -->
                            <div class="user-info">
                                <h5>User Information</h5>
                                <div class="user-details">
                                    <div class="user-detail-item">
                                        <span class="user-detail-label">Full Name</span>
                                        <span class="user-detail-value">{{ $record->name }}</span>
                                    </div>
                                    <div class="user-detail-item">
                                        <span class="user-detail-label">Email</span>
                                        <span class="user-detail-value">{{ $record->email }}</span>
                                    </div>
                                    <div class="user-detail-item">
                                        <span class="user-detail-label">Mobile</span>
                                        <span class="user-detail-value">{{ $record->mobile_no ?? 'N/A' }}</span>
                                    </div>
                                    <div class="user-detail-item">
                                        <span class="user-detail-label">Current Status</span>
                                        <span class="user-detail-value">
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
                                action="{{ route('manager-management.update', ['manager_management' => $record->slug]) }}"
                                enctype="multipart/form-data">
                                {{ csrf_field() }}
                                <input type="hidden" name="_method" value="PUT">

                                <div class="row">
                                    <div class="col-md-12">
                                        <div class="form-group">
                                            <label for="statusSelect">User Status</label>
                                            <select name="status" class="form-control form-select" id="statusSelect">
                                                <option value="1" {{ $record->status == 1 ? 'selected' : '' }}>Active
                                                </option>
                                                <option value="0" {{ $record->status == 0 ? 'selected' : '' }}>Disabled
                                                </option>
                                            </select>
                                            <small class="form-text">
                                                Active users can access the system, while disabled users cannot login.
                                            </small>
                                        </div>
                                    </div>
                                </div>

                                <div class="button-group">
                                    <button type="submit" class="btn btn-primary">
                                        Update Status
                                    </button>
                                    <a href="{{ route('manager-management.index') }}" class="btn btn-outline-secondary">
                                        Back to List
                                    </a>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        @include('portal.footer')
    </section>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const statusSelect = document.getElementById('statusSelect');
            const submitBtn = document.querySelector('.btn-primary');

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

            // Add loading state to submit button
            const form = document.querySelector('form');
            form.addEventListener('submit', function() {
                submitBtn.innerHTML = 'Updating...';
                submitBtn.disabled = true;
            });
        });
    </script>
@endsection
