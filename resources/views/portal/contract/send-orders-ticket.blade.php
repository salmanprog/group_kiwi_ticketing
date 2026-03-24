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
                                <h3>Sent Ticket Listing</h3>
                            </div>
                            <!-- <div class="header-actions">
                                <a class="btn btn-primary" href="{{ route('email-template.create') }}">
                                    <i class="fas fa-plus-circle"></i> Add Email Template
                                </a>
                            </div> -->
                        </div>
                    </div>
                    <div class="card-body">
                        <div class="table-controls">
                            <div class="controls-left">
                                <!-- <div class="table-info"> -->
                                    
                                    <form id="filter_form" method="GET" action="{{ route('contract.send-ticket-list', $slug) }}"
                                        class="filter-form"
                                        style="display: flex; gap: 12px; align-items: center; flex-wrap: wrap;"> 

                                        <div class="status-filter-group">
                                            <label for="status" class="filter-label">Status</label>
                                            <select id="status" name="status" class="status-select">
                                                <option value="NotSent" {{ request('status') == 'NotSent' ? 'selected' : '' }}>Not Sent</option>
                                                <option value="All" {{ request('status') == 'All' ? 'selected' : '' }}>All</option>
                                                <option value="Sent" {{ request('status') == 'Sent' ? 'selected' : '' }}>Sent</option>
                                            </select>
                                        </div>

                                        <button type="submit" class="btn btn-filter">Filter</button>
                                    </form> 

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
                                        <th>QR Code</th>
                                        <th>Ticket Type</th>
                                    </tr>
                                </thead>
                                <tbody class="for-fbold">
                                    @foreach($tickets as $ticket)
                                    <tr>
                                        <td>{{ $ticket['visualId'] }}</td>
                                        <td>{{ $ticket['ticketType'] }}</td>
                                    </tr>
                                    @endforeach
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
        <script src="{{ asset('admin/assets/lib/datatables/jquery.dataTables.min.js') }}"></script>
        <script src="{{ asset('admin/assets/lib/datatables/dataTables.responsive.min.js') }}"></script>
    @endpush
@endsection