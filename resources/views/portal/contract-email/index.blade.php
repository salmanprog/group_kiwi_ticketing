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
                                <h3>Contract Emails</h3>
                            </div>
<div class="header-actions">
    <button type="button" class="btn btn-primary" id="openModalBtn">
        <i class="fas fa-plus-circle"></i> Add Contract Email
    </button>
</div>

                        </div>
                    </div>
                    <div class="card-body">
                        <div class="table-controls">
                            <div class="controls-left">
                                <!-- <div class="table-info"> -->
                                    {{-- <i class="fas fa-database"></i>
                                    <span>Showing all contract emails</span> --}}
                            </div>
                           <!-- <div class="controls-right">
                                <form id="search_form" method="GET" class="search-form">
                                    <div class="input-group">
                                        <input type="text" name="keyword" class="form-control" placeholder="Search contract emails...">
                                        <button type="submit" class="btn btn-search">
                                            <i class="fas fa-search"></i>
                                        </button>
                                    </div>
                                </form>
                            </div> -->

                        </div>

                        <div class="table-responsive">
                            <table class="table">
                                <thead>
                                    <tr>
                                        <th>Name</th>
                                        <th>Email</th>
                                        <th>Created_at</th>
                                        <th>Action<th>
                                    </tr>
                                </thead>
                                <tbody class="for-fbold">
                                    @if($data->isEmpty())
                                        <tr>
                                            <td colspan="3" class="text-center">No contract emails found</td>
                                        </tr>
                                    @else
                                    @foreach($data as $item)
                                        <tr>
                                            <td>{{ $item->name }}</td>
                                            <td>{{ $item->email }}</td>
                                            <td>{{ $item->created_at }}</td>
                                            <td><a href="{{ route('contract-email.delete', $item->id) }}" class="btn btn-danger">Delete</a></td>
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
        <div class="modal fade" id="contractEmailModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">

            <div class="modal-header">
                <h5 class="modal-title">Add Contract Email</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>

           <form id="contractEmailForm">
                @csrf
                <div class="modal-body">
                    <div class="mb-3">
                        <label class="form-label">Name</label>
                        <input type="text" name="name" class="form-control" required>
                        <span class="text-danger small error-text name_error"></span>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Email</label>
                        <input type="email" name="email" class="form-control" required>
                        <span class="text-danger small error-text email_error"></span>
                    </div>
                    <input type="hidden" name="auth_code" value="{{ Auth::user()->auth_code }}">
                </div>

                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" id="cancelModalBtn">Cancel</button>
                    <button type="submit" class="btn btn-primary" id="saveEmailBtn">Save Email</button>
                </div>
            </form>


        </div>
    </div>
</div>

        @include('portal.footer')
    </section>
    @push('scripts')
        <script>
            let ajax_listing_url = `{{ route('contract-email.ajax-listing') }}`;
        </script>
        <script src="{{ asset('admin/assets/lib/datatables/jquery.dataTables.min.js') }}"></script>
        <script src="{{ asset('admin/assets/lib/datatables/dataTables.responsive.min.js') }}"></script>
        <script src="{{ asset('admin/assets/js/datatable-ajax.js') }}"></script>
    @endpush

@push('scripts')
<script>
$(document).ready(function() {
    var contractModal = new bootstrap.Modal(document.getElementById('contractEmailModal'));

    // Open modal
    $('#openModalBtn').on('click', function() {
        $('#contractEmailForm')[0].reset();
        $('.error-text').text(''); // Clear previous errors
        contractModal.show();
    });

    // Cancel button
    $('#cancelModalBtn').on('click', function() {
        contractModal.hide();
    });

    // AJAX submit
    $('#contractEmailForm').on('submit', function(e) {
        e.preventDefault(); // prevent normal form submit
        $('.error-text').text(''); // clear errors

        var formData = $(this).serialize();

        $.ajax({
            url: "{{ route('contract-email.store') }}",
            method: "POST",
            data: formData,
            success: function(response) {
                if(response.success) {
                    alert(response.message); // or use toast
                    contractModal.hide();

                    // Optionally, append new row to table without reload
                    $('table tbody').prepend(
                        `<tr>
                            <td>${response.data.name}</td>
                            <td>${response.data.email}</td>
                            <td>${response.data.created_at}</td>
                        </tr>`
                    );
                }
            },
            error: function(xhr) {
                if(xhr.status === 422) {
                    let errors = xhr.responseJSON.errors;
                    $.each(errors, function(key, value){
                        $('.' + key + '_error').text(value[0]);
                    });
                } else {
                    alert('Something went wrong! Try again.');
                }
            }
        });
    });
});
</script>
@endpush


<!-- @push('scripts')
<script>
$(document).ready(function() {

    // Function to fetch filtered contract emails
    function fetchContractEmails(keyword = '') {
        $.ajax({
            url: "{{ route('contract-email.ajax-listing') }}",
            type: 'GET',
            data: { keyword: keyword },
            success: function(response) {
                let tbody = $('table tbody');
                tbody.empty();

                if(response.data.length === 0) {
                    tbody.append('<tr><td colspan="3" class="text-center">No contract emails found</td></tr>');
                } else {
                    $.each(response.data, function(index, email) {
                        tbody.append(`
                            <tr>
                                <td>${email.name}</td>
                                <td>${email.email}</td>
                                <td>${email.created_at}</td>
                            </tr>
                        `);
                    });
                }
            },
            error: function() {
            }
        });
    }

    // On page load, fetch all contract emails
    fetchContractEmails();

    // Handle search form submit
    $('#search_form').on('submit', function(e) {
        e.preventDefault();
        let keyword = $(this).find('input[name="keyword"]').val();
        fetchContractEmails(keyword);
    });

});
</script>
@endpush -->





@endsection