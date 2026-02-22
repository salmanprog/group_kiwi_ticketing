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
                                {{-- <i class="fas fa-calendar-check"></i> --}}
                                <h3>Email Template Listing</h3>
                            </div>
                            <div class="header-actions">
                                <a class="btn btn-primary" href="{{ route('email-template.create') }}">
                                    <i class="fas fa-plus-circle"></i> Add Email Template
                                </a>
                            </div>
                        </div>
                    </div>
                    <div class="card-body">
                        <div class="table-controls">
                            <div class="controls-left">
                                <!-- <div class="table-info"> -->
                                    {{-- <i class="fas fa-database"></i>
                                    <span>Showing all event types</span> --}}

                                    <!-- <form id="filter_form" method="GET" action="{{ route('event-type.index') }}"
                                        class="filter-form"
                                        style="display: flex; gap: 12px; align-items: center; flex-wrap: wrap;"> -->

                                        <!-- Start Date -->
                                        <!-- <div class="date-filter-group">
                                            <label for="start_date" class="filter-label">Start Date</label>
                                            <input type="date" id="start_date" name="start_date" class="date-input"
                                                placeholder="Start Date" value="{{ request('start_date') }}">
                                        </div> -->

                                        <!-- End Date -->
                                        <!-- <div class="date-filter-group">
                                            <label for="end_date" class="filter-label">End Date</label>
                                            <input type="date" id="end_date" name="end_date" class="date-input"
                                                placeholder="End Date" value="{{ request('end_date') }}">
                                        </div> -->

                                        <!-- Status Filter -->
                                        <!-- <div class="status-filter-group">
                                            <label for="status" class="filter-label">Status</label>
                                            <select id="status" name="status" class="status-select">
                                                <option value="" {{ request('status') == '' ? 'selected' : '' }}>All
                                                </option>
                                                <option value="active" {{ request('status') == 'active' ? 'selected' : '' }}>
                                                    Active</option>
                                                <option value="deactive" {{ request('status') == 'deactive' ? 'selected' : '' }}>Deactive
                                                </option>
                                            </select>
                                        </div> -->

                                        <!-- Filter Button -->
                                        <!-- <button type="submit" class="btn btn-filter">Filter</button> -->
                                    <!-- </form> -->

                                <!-- </div> -->
                            </div>
                            <div class="controls-right">
                                <form id="search_form" method="GET" class="search-form">
                                    <div class="input-group">
                                        <input type="text" name="keyword" class="form-control"
                                            placeholder="Search event types...">
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
                                        <th>Identifier</th>
                                        <th>To Email</th>
                                        <th>Subject</th>
                                        <th>Action</th>
                                    </tr>
                                </thead>
                                <tbody class="for-fbold">
                                    <!-- Data via AJAX -->
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
            let ajax_listing_url = `{{ route('email-template.ajax-listing') }}`;
        </script>
        <script src="{{ asset('admin/assets/lib/datatables/jquery.dataTables.min.js') }}"></script>
        <script src="{{ asset('admin/assets/lib/datatables/dataTables.responsive.min.js') }}"></script>
        <script src="{{ asset('admin/assets/js/datatable-ajax.js') }}"></script>
    @endpush
@endsection