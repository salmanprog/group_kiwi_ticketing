{{-- @extends('portal.master')
@section('content')
    @push('stylesheets')
        <link href="{{ asset('admin/assets/lib/datatables/jquery.dataTables.min.css') }}" rel="stylesheet" type="text/css">
        <link href="{{ asset('admin/assets/lib/datatables/responsive.bootstrap.min.css') }}" rel="stylesheet" type="text/css">
    @endpush
    <section class="main-content">
        <div class="row">
            <div class="col-md-12">
                @include('portal.flash-message')
                <div class="card">
                    <div class="card-header card-default">
                        <div class="row">
                            <div class="col-md-6">
                               Access Denied
                            </div>
                        </div>
                    </div>
                    <div class="card-body">
                        <h1 class="display-4 text-danger">Access Denied</h1>
                        <p class="lead">{{ $errorMessage }}</p>
                    </div>
                </div>
            </div>
        </div>
        @include('portal.footer')
    </section>
    @push('scripts')
        <script src="{{ asset('admin/assets/lib/datatables/jquery.dataTables.min.js') }}"></script>
        <script src="{{ asset('admin/assets/lib/datatables/dataTables.responsive.min.js') }}"></script>
        <script src="{{ asset('admin/assets/js/datatable-ajax.js') }}"></script>
    @endpush
@endsection --}}

@extends('portal.master')
@section('content')
    @push('stylesheets')
        <link href="{{ asset('admin/assets/lib/datatables/jquery.dataTables.min.css') }}" rel="stylesheet" type="text/css">
        <link href="{{ asset('admin/assets/lib/datatables/responsive.bootstrap.min.css') }}" rel="stylesheet" type="text/css">
        <style>
            .access-denied-card {
                max-width: 600px;
                margin: 50px auto;
                background: #fff;
                border: 1px solid #e9ecef;
                border-radius: 10px;
                padding: 40px;
                text-align: center;
                box-shadow: 0 2px 10px rgba(0, 0, 0, 0.05);
            }

            .error-code {
                font-size: 80px;
                font-weight: 700;
                color: #dc3545;
                line-height: 1;
                margin-bottom: 15px;
            }

            .error-title {
                font-size: 28px;
                color: #333;
                margin-bottom: 20px;
            }

            .error-message {
                background: #f8f9fa;
                border-left: 4px solid #dc3545;
                padding: 15px;
                margin: 25px 0;
                text-align: left;
                color: #666;
                font-size: 16px;
            }

            .btn-group {
                display: flex;
                gap: 10px;
                justify-content: center;
                margin-top: 30px;
            }

            .btn {
                padding: 10px 25px;
                border-radius: 5px;
                text-decoration: none;
                font-size: 14px;
                transition: all 0.2s;
            }

            .btn-primary {
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

            .btn-primary:hover {
                background: #0056b3;
            }

            .btn-secondary {
                background: #6c757d;
                color: #fff;
            }

            .btn-secondary:hover {
                background: #545b62;
            }

            .btn-outline {
                border: 1px solid #dee2e6;
                color: #6c757d;
            }

            .btn-outline:hover {
                background: #f8f9fa;
            }
        </style>
    @endpush

    <section class="main-content">
        <div class="container">
            @include('portal.flash-message')

            <div class="access-denied-card">
                <div class="error-code">403</div>
                <h2 class="error-title">Access Denied</h2>

                <div class="error-message">
                    <i class="fa fa-exclamation-triangle text-danger mr-2"></i>
                    {{ $errorMessage ?? 'You do not have permission to access this page.' }}
                </div>

                <div class="btn-group">
                    <a href="{{ url('/portal/dashboard') }}" class="btn btn-primary">
                        <i class="fa fa-home"></i> Dashboard
                    </a>

                </div>
            </div>
        </div>

        @include('portal.footer')
    </section>

    @push('scripts')
        <script src="{{ asset('admin/assets/lib/datatables/jquery.dataTables.min.js') }}"></script>
        <script src="{{ asset('admin/assets/lib/datatables/dataTables.responsive.min.js') }}"></script>
        <script src="{{ asset('admin/assets/js/datatable-ajax.js') }}"></script>
    @endpush
@endsection
