@extends('portal.master')
@section('content')
    @push('stylesheets')
        <link href="{{ asset('admin/assets/lib/datatables/jquery.dataTables.min.css') }}" rel="stylesheet" type="text/css">
        <link href="{{ asset('admin/assets/lib/datatables/responsive.bootstrap.min.css') }}" rel="stylesheet" type="text/css">
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
        <link href="{{ asset('admin/assets/scss/Listing-tables.css') }}" rel="stylesheet" type="text/css">
    @endpush
    <section class="main-content">
        <div class="row">
            <div class="col-md-12">
                @include('portal.flash-message')
                <div class="card">
                    <div class="card-header">
                        <div class="header-content">
                            <div class="header-title">
                                {{-- <i class="fas fa-user-friends"></i> --}}
                                <h3>Account Listing</h3>
                                <span class="badge-count">Total Account: <span id="total-count">0</span></span>
                            </div>
                            <div class="header-actions">
                                @if (Auth::user()->user_type == 'company')
                                    <a class="btn btn-primary" href="{{ route('organization.create') }}">
                                        <i class="fas fa-plus-circle"></i> Add Account
                                    </a>
                                @endif 
                            </div>
                        </div>
                    </div>
                    <div class="card-body">
                        <div class="table-controls">
                            <div class="controls-left">
                                <div class="table-info">
                                    {{-- <i class="fas fa-database"></i>
                                    <span>Showing all organizations</span> --}}

                                    <form id="filter_form" method="GET" action="{{ route('organization.index') }}"
                                        class="filter-form"
                                        style="display: flex; gap: 12px; align-items: center; flex-wrap: wrap;">

                                         <p class="filter-label mb-0">Search Estimate by Creation Date:</p>
                                        <!-- Start Date -->
                                        <div class="date-filter-group">
                                            <label for="start_date" class="filter-label">From</label>
                                            <input type="date" id="start_date" name="start_date" class="date-input"
                                                placeholder="Start Date" value="{{ request('start_date') }}">
                                        </div>

                                        <!-- End Date -->
                                        <div class="date-filter-group">
                                            <label for="end_date" class="filter-label">To</label>
                                            <input type="date" id="end_date" name="end_date" class="date-input"
                                                placeholder="End Date" value="{{ request('end_date') }}">
                                        </div>

                                        <div class="status-filter-group">
                                            <label for="status" class="filter-label">Status</label>
                                            <select id="status" name="status" class="status-select">
                                                <option value="" {{ request('status') == '' ? 'selected' : '' }}>All
                                                </option>
                                                <option value="active"
                                                    {{ request('status') == 'active' ? 'selected' : '' }}>
                                                    Active</option>
                                                <option value="deactive"
                                                    {{ request('status') == 'deactive' ? 'selected' : '' }}>Deactive
                                                </option>
                                            </select>
                                        </div>

                                        <button type="submit" class="btn btn-filter">Filter</button>
                                    </form>
                                </div>
                            </div>
                            <div class="controls-right">
                                <form id="search_form" method="GET" class="search-form">
                                    <div class="input-group">
                                        <input type="text" name="keyword" class="form-control"
                                            placeholder="Search Accounts...">
                                        <button type="submit" class="btn btn-search">
                                            <i class="fas fa-search"></i>
                                        </button>
                                    </div>
                                </form>
                            </div>
                        </div>

                        <div class="table-responsive">
                            <table id="_ajax_datatable" class="table">
                                <thead>
                                    <tr>
                                        <th>Account Name</th>
                                        <th>Contact</th>
                                        <!-- <th>Email</th>
                                        <th>Phone</th> -->
                                        <th>Event Date</th>
                                        <th>Follow-up Date</th>
                                        <th>Status</th>
                                        <!-- <th>Created At</th> -->
                                        @if (Auth::user()->user_type == 'company' ||
                                                Auth::user()->user_type == 'salesman' ||
                                                Auth::user()->user_type == 'manager')
                                            <th>Action</th>
                                        @endif
                                    </tr>
                                </thead>
                                <tbody class="for-fbold">
                                    <!-- Data via AJAX -->
                                </tbody>
                            </table>
                        </div>
                        {{-- <div class="table-responsive">
                            <table id="" class="table">
                                <thead>
                                    <tr>
                                        <th>Account Name</th>
                                        <th>Contact</th>
                                        <th>Email</th>
                                        <th>Phone</th>
                                        <th>Event Date</th>
                                        <th>Follow-up Date</th>
                                        <th>Status</th>
                                        <!-- <th>Created At</th> -->
                                        @if (Auth::user()->user_type == 'company' ||
                                                Auth::user()->user_type == 'salesman' ||
                                                Auth::user()->user_type == 'manager')
                                            <th>Action</th>
                                        @endif
                                    </tr>
                                </thead>
                                <tbody class="for-fbold">
                                    <tr role="row" class="even">
                                        <td>
                                            <a href="#" title="View" class="btn btn-xs btn-info">
                                                Ketaki C
                                                <svg xmlns="http://www.w3.org/2000/svg" width="10" height="10"
                                                    viewBox="0 0 24 24" fill="none" stroke="currentColor"
                                                    stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
                                                    style="margin-right:4px; vertical-align:middle;">
                                                    <path d="M15 3h6v6"></path>
                                                    <path d="M10 14 21 3"></path>
                                                    <path d="M18 13v6a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2V8a2 2 0 0 1 2-2h6">
                                                    </path>
                                                </svg>
                                            </a>
                                        </td>
                                        <td><a href="#" class="btn btn-xs btn-info">Brad Pitt</a></td>
                                        <td>wayes59344@jofuso.com</td>
                                        <td>+92-123654788</td>
                                        <td>
                                            <span class="event-date-badge">
                                                2026-01-06
                                            </span>
                                        </td>
                                        <td>
                                            <span class="follow-update-date">
                                                2026-01-06
                                            </span>
                                        </td>
                                        <td><span class="btn btn-xs btn-success">Active</span></td>
                                        <td>
                                            <span class="f-line">
                                                <a href="#" class="cust-edit">
                                                    <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14"
                                                        viewBox="0 0 24 24" fill="none" stroke="currentColor"
                                                        stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
                                                        class="lucide lucide-pen-line" aria-hidden="true">
                                                        <path d="M13 21h8"></path>
                                                        <path
                                                            d="M21.174 6.812a1 1 0 0 0-3.986-3.987L3.842 16.174a2 2 0 0 0-.5.83l-1.321 4.352a.5.5 0 0 0 .623.622l4.353-1.32a2 2 0 0 0 .83-.497z">
                                                        </path>
                                                    </svg>
                                                </a>

                                                <span class="action-menu-container">
                                                    <button class="action-menu-trigger" data-id="4">⋯</button>
                                                    <div class="action-menu" id="action-menu-4">
                                                        <button class="cust-btn-delete">
                                                            <svg xmlns="http://www.w3.org/2000/svg" width="12"
                                                                height="12" viewBox="0 0 24 24" fill="none"
                                                                stroke="currentColor" stroke-width="2"
                                                                stroke-linecap="round" stroke-linejoin="round"
                                                                class="lucide lucide-trash2 lucide-trash-2"
                                                                aria-hidden="true">
                                                                <path d="M10 11v6"></path>
                                                                <path d="M14 11v6"></path>
                                                                <path d="M19 6v14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V6"></path>
                                                                <path d="M3 6h18"></path>
                                                                <path d="M8 6V4a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2"></path>
                                                            </svg>
                                                            Delete User
                                                        </button>
                                                        <button class="cust-view-blue">
                                                            <svg xmlns="http://www.w3.org/2000/svg" width="12"
                                                                height="12" viewBox="0 0 24 24" fill="none"
                                                                stroke="currentColor" stroke-width="2"
                                                                stroke-linecap="round" stroke-linejoin="round"
                                                                class="lucide lucide-eye" aria-hidden="true">
                                                                <path
                                                                    d="M2.062 12.348a1 1 0 0 1 0-.696 10.75 10.75 0 0 1 19.876 0 1 1 0 0 1 0 .696 10.75 10.75 0 0 1-19.876 0">
                                                                </path>
                                                                <circle cx="12" cy="12" r="3"></circle>
                                                            </svg>
                                                            View Details
                                                        </button>
                                                    </div>
                                                </span>
                                            </span>
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                        </div> --}}
                    </div>
                </div>
            </div>
        </div>
        @include('portal.footer')
    </section>
    @push('scripts')
        <script>
            let ajax_listing_url = `{{ route('organization.ajax-listing') }}`;


            document.querySelectorAll('.action-menu-trigger').forEach(trigger => {


                trigger.addEventListener('click', function(e) {

                    console.log("ok");

                    e.stopPropagation();
                    const menuId = this.getAttribute('data-id');
                    const menu = document.getElementById(`action-menu-${menuId}`);

                    // Close all other open menus
                    document.querySelectorAll('.action-menu.show').forEach(openMenu => {
                        if (openMenu !== menu) {
                            openMenu.classList.remove('show');
                        }
                    });

                    // Toggle current menu
                    menu.classList.toggle('show');
                });
            });
        </script>
        <script src="{{ asset('admin/assets/lib/datatables/jquery.dataTables.min.js') }}"></script>
        <script src="{{ asset('admin/assets/lib/datatables/dataTables.responsive.min.js') }}"></script>
        <script src="{{ asset('admin/assets/js/datatable-ajax.js') }}"></script>
    @endpush
@endsection
