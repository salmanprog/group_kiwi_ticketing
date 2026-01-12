@extends('portal.master')
@section('content')
    @push('stylesheets')
        <link href="{{ asset('admin/assets/lib/datatables/jquery.dataTables.min.css') }}" rel="stylesheet" type="text/css">
        <link href="{{ asset('admin/assets/lib/datatables/responsive.bootstrap.min.css') }}" rel="stylesheet" type="text/css">
        <link href="{{ asset('admin/assets/scss/Listing-tables.css') }}" rel="stylesheet" type="text/css">
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    @endpush
    <section class="main-content">
        <div class="row">
            <div class="col-md-12">
                @include('portal.flash-message')
                <div class="card">
                    <div class="card-header">
                        <div class="header-content">
                            <div class="header-title">
                                {{-- <i class="fas fa-building"></i> --}}
                                <h3>Company Reports</h3>
                                <span class="badge-count">Total Companies: <span id="total-count">0</span></span>
                            </div>
                        </div>
                    </div>
                    <div class="card-body">
                        <div class="table-controls">
                            <div class="controls-right">
                                <form id="search_form" method="GET" class="search-form">
                                    <div class="input-group">
                                        <input type="text" name="keyword" class="form-control"
                                            placeholder="Search by companies...">
                                        <button type="submit" class="btn btn-search">
                                            <i class="fas fa-search"></i>
                                        </button>
                                    </div>
                                </form>
                            </div>
                        </div>

                        <div class="table-responsive">
                            <table class="table">
                                <thead>
                                    <tr>
                                        <th>Name</th>
                                        <th>No. of Contract</th>
                                        <th>No. of Estimate</th>
                                        <!-- <th>Company Salesman</th>
                                        <th>Company Managers</th>
                                        <th>Company Estimate</th>
                                        <th>Status</th>
                                        <th>Action</th> -->
                                    </tr>
                                </thead>
                                <tbody class="for-fbold">
                                    <!-- Data will be loaded via AJAX -->
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        @include('portal.footer')
    </section>
    @push('scripts')
        <script>
            let ajax_listing_url = '';
        </script>
        <script src="{{ asset('admin/assets/lib/datatables/jquery.dataTables.min.js') }}"></script>
        <script src="{{ asset('admin/assets/lib/datatables/dataTables.responsive.min.js') }}"></script>
        <script src="{{ asset('admin/assets/js/datatable-ajax.js') }}"></script>
        <script>
            $(document).ready(function() {
                // Load all companies on page load
                loadCompanies();

                // Handle search form submit
                $('#search_form').on('submit', function(e) {
                    e.preventDefault(); // prevent page reload
                    let keyword = $(this).find('input[name="keyword"]').val();
                    loadCompanies(keyword);
                });
            });
            function loadCompanies(keyword = '') {
                $.ajax({
                    url: "{{ route('company.ajax-listing') }}",
                    type: "GET",
                    data: { keyword: keyword },
                    success: function(res) {

                        let tbody = '';
                        if (res.data && Array.isArray(res.data)) {
                            res.data.forEach(function(company) {
                                tbody += '<tr>';
                                tbody += '<td>' + company.organization_name + '</td>';
                                tbody += '<td>' + company.total_contract + '</td>';
                                tbody += '<td>';
                                if (company.contracts && Array.isArray(company.contracts)) {
                                    company.contracts.forEach(function(contract) {
                                        tbody += contract.total_estimate || '0';
                                    });
                                } else {
                                    tbody += '0';
                                }
                                tbody += '</td>';

                                tbody += '</tr>';
                            });
                        } else {
                            tbody = '<tr><td colspan="8" class="text-center">No records found</td></tr>';
                        }

                        $('tbody.for-fbold').html(tbody);
                        $('#total-count').text(res.total ?? 0);
                    },
                    error: function(xhr) {
                        console.error(xhr.responseText);
                    }
                });
            }
            </script>
    @endpush

    {{-- <style>
        /* Professional Green Theme - #A0C242 */
        .main-content {
            background: #f8faf9;
            min-height: 100vh;
            padding: 25px;
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
            /* background: linear-gradient(135deg, #A0C242 0%, #8AB933 100%); */
            border-bottom: none;
            padding: 20px 30px;
            color: #1f2937;
        }

        .header-content {
            display: flex;
            justify-content: space-between;
            align-items: center;
            flex-wrap: wrap;
            gap: 15px;
        }

        .header-title {
            display: flex;
            align-items: center;
            gap: 15px;
        }

        .header-title i {
            font-size: 1.8rem;
            opacity: 0.9;
        }

        .header-title h3 {
            margin: 0;
            font-weight: 700;
            font-size: 18px;
        }

        .badge-count {
            background: rgba(255, 255, 255, 0.2);
            padding: 6px 12px;
            border-radius: 20px;
            font-size: 0.9rem;
            font-weight: 500;
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
            border: 1px solid #ffffff !important;
            background-color: #8ab02e !important;
        }

        .card-body {
            padding: 0;
        }

        .table-controls {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 20px 30px;
            background: #f8faf9;
            border-bottom: 1px solid #eaeaea;
            flex-wrap: wrap;
            gap: 15px;
        }

        .controls-left {
            display: flex;
            align-items: center;
            gap: 10px;
        }

        .table-info {
            display: flex;
            align-items: center;
            gap: 8px;
            color: #666;
            font-size: 0.9rem;
        }

        .table-info i {
            color: #A0C242;
        }

        .search-form {
            min-width: 300px;
        }

        .input-group {
            border-radius: 8px;
            overflow: hidden;
            box-shadow: 0 2px 8px rgba(160, 194, 66, 0.1);
            transition: box-shadow 0.3s ease;
        }

        .input-group:focus-within {
            box-shadow: 0 4px 12px rgba(160, 194, 66, 0.2);
        }

        .form-control {
            border: 1px solid #e0e0e0;
            border-right: none;
            padding: 10px 15px;
            font-size: 0.9rem;
        }

        .form-control:focus {
            border-color: #A0C242 !important;
            box-shadow: none !important;
        }

        .btn-search {
            position: unset !important;
            background: #A0C242 !important;
            border: 1px solid #A0C242 !important;
            color: white !important;
            padding: 10px 15px !important;
            transition: all 0.3s ease !important;
        }

        .search-form .form-control {
            height: 43px;
            color: #000;
            font-weight: 500;
        }

        .btn-search:hover {
            background: #8AB933;
            border-color: #8AB933;
        }

        .table-responsive {
            padding: 0 30px 30px 30px;
        }

        .table {
            width: 100%;
            border-collapse: separate;
            border-spacing: 0;
            margin: 0;
        }

        .table thead th {
            background: #f7fafc;
            border-bottom: 0px;
            padding: 7px 12px !important;
            font-weight: 700;
            color: #6b7280;
            font-size: 12px;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }

        .table>thead>tr>th:first-child {
            border-left: 0 !important;
        }

        .table thead th i {
            margin-right: 8px;
            color: #A0C242;
            font-size: 0.8rem;
        }

        .table tbody td {
            padding: 12px;
            border-top: 0;
            border-bottom: 1px solid #f0f0f0;
            color: #6b7280;
            font-size: 14px;
            transition: background 0.3s ease;
        }

        .for-fbold td:first-of-type {
            font-weight: 600;
            color: #333;
            font-size: 14px;
        }

        .table tbody tr {
            transition: all 0.3s ease;
        }

        .table tbody tr:hover td {
            color: #6b7280;
        }

        .table>tbody>tr:nth-of-type(odd) {
            background-color: #fff;
        }

        /* Status Badges */
        .status-badge {
            padding: 6px 12px;
            border-radius: 20px;
            font-size: 0.8rem;
            font-weight: 600;
            text-align: center;
            display: inline-block;
            min-width: 80px;
        }

        .status-active {
            background: #e8f5e8;
            color: #2e7d32;
            border: 1px solid #c8e6c9;
        }

        .status-inactive {
            background: #ffebee;
            color: #c62828;
            border: 1px solid #ffcdd2;
        }

        /* Action Buttons */
        .action-buttons {
            display: flex;
            gap: 8px;
            justify-content: center;
        }

        .btn-action {
            width: 32px;
            height: 32px;
            border-radius: 6px;
            display: flex;
            align-items: center;
            justify-content: center;
            text-decoration: none;
            transition: all 0.3s ease;
            border: none;
            cursor: pointer;
        }

        .btn-edit {
            background: #e3f2fd;
            color: #1976d2;
        }

        .btn-edit:hover {
            background: #1976d2;
            color: white;
            transform: scale(1.1);
        }

        .btn-delete {
            background: #ffebee;
            color: #d32f2f;
        }

        .btn-delete:hover {
            background: #d32f2f;
            color: white;
            transform: scale(1.1);
        }

        /* Pagination Styles */
        .dataTables_wrapper .dataTables_paginate {
            padding: 20px 0;
            text-align: center;
        }

        .dataTables_wrapper .dataTables_paginate .paginate_button {
            border: 1px solid #e0e0e0 !important;
            border-radius: 6px !important;
            padding: 8px 16px !important;
            margin: 0 3px !important;
            color: #666 !important;
            font-weight: 500 !important;
            transition: all 0.3s ease !important;
        }

        .dataTables_wrapper .dataTables_paginate .paginate_button:hover {
            background: #A0C242 !important;
            color: white !important;
            border-color: #A0C242 !important;
        }

        .dataTables_wrapper .dataTables_paginate .paginate_button.current {
            background: #A0C242 !important;
            color: white !important;
            border-color: #A0C242 !important;
        }

        /* Loading State */
        .table tbody tr td {
            position: relative;
        }

        .table tbody tr.loading td:before {
            content: "";
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: linear-gradient(90deg, transparent, rgba(160, 194, 66, 0.1), transparent);
            animation: loading 1.5s infinite;
        }

        @keyframes loading {
            0% {
                transform: translateX(-100%);
            }

            100% {
                transform: translateX(100%);
            }
        }

        /* Responsive Design */
        @media (max-width: 768px) {
            .main-content {
                padding: 15px;
            }

            .header-content {
                flex-direction: column;
                text-align: center;
            }

            .header-title {
                flex-direction: column;
                gap: 10px;
            }

            .table-controls {
                flex-direction: column;
                padding: 15px;
            }

            .search-form {
                min-width: 100%;
            }

            .table-responsive {
                padding: 0 15px 15px 15px;
                overflow-x: auto;
            }

            .table thead th {
                white-space: nowrap;
            }

            .action-buttons {
                flex-direction: column;
                gap: 5px;
            }

            .btn-action {
                width: 100%;
            }
        }

        /* Flash Message Styling */
        .alert {
            border-radius: 8px;
            border: none;
            padding: 15px 20px;
            margin-bottom: 25px;
            font-weight: 500;
        }

        .alert-success {
            background: #e8f5e8;
            color: #2e7d32;
            border-left: 4px solid #A0C242;
        }

        .alert-danger {
            background: #ffebee;
            color: #c62828;
            border-left: 4px solid #f44336;
        }

        .table-info {
            display: flex;
            align-items: center;
            gap: 20px;
            background-color: #ffffff;
            border-radius: 12px;
        }

        .filter-form {
            display: flex;
            align-items: center;
            gap: 15px;
            padding: 10px 20px;
            border-radius: 12px;
        }

        .date-filter-group {
            flex-direction: column;
            font-weight: 600;
            font-size: 0.85rem;
            color: #3a3a3a;
        }

        .date-input {
            border: 1.5px solid #cdd9a7;
            border-radius: 10px;
            padding: 8px 12px;
            font-size: 0.75rem;
            font-weight: 300;
            background-color: #f9fcf7;
            width: 140px;
        }

        .btn-filter {
            background-color: #8ab933;
            color: white;
            border: none;
            padding: 10px 22px;
            border-radius: 12px;
            font-weight: 700;
            cursor: pointer;
            height: 40px;
            box-shadow: 0 4px 12px rgba(138, 185, 51, 0.3);
            transition: background-color 0.3s ease;
        }

        .btn-filter:hover {
            background-color: #7bb82f;
        }

        /* Status Filter Group */
        .status-filter-group {
            flex-direction: column;
            gap: 4px;
        }

        .filter-label {
            font-size: 13px;
            font-weight: 600;
            color: #374151;
        }

        /* Status Dropdown */
        .status-select {
            padding: 6px 10px;
            border: 1px solid #d1d5db;
            border-radius: 6px;
            font-size: 14px;
            background-color: #fff;
            color: #111827;
            min-width: 160px;
            cursor: pointer;
            transition: border-color 0.2s ease, box-shadow 0.2s ease;
        }

        .status-select:focus {
            border-color: #3b82f6;
            box-shadow: 0 0 0 3px rgba(59, 130, 246, 0.2);
            outline: none;
        }

        .status-select:hover {
            border-color: #9ca3af;
        }
    </style> --}}
@endsection
