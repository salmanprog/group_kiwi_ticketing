@extends('portal.master')
@section('content')
@push('stylesheets')
    <!-- DataTables CSS -->
    <link href="{{ asset('admin/assets/lib/datatables/jquery.dataTables.min.css') }}" rel="stylesheet" type="text/css">
    <link href="{{ asset('admin/assets/lib/datatables/responsive.bootstrap.min.css') }}" rel="stylesheet" type="text/css">
    <link href="{{ asset('admin/assets/scss/Listing-tables.css') }}" rel="stylesheet" type="text/css">

    <!-- FontAwesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.1/dist/css/bootstrap.min.css" rel="stylesheet">
@endpush

<section class="main-content">
    <div class="row">
        <div class="col-md-12">
            @include('portal.flash-message')
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h3>Sent Ticket Listing</h3>
                    <button type="button" class="btn btn-success" id="openSendTicketModal">
                        <i class="fas fa-paper-plane"></i> Send Ticket
                    </button>
                </div>
                <div class="card-body">
                    <!-- Filter & Search -->
                    <div class="table-controls d-flex justify-content-between mb-3">
                        <form id="filter_form" method="GET" action="{{ route('contract.send-ticket-list', $slug) }}" class="d-flex gap-2 align-items-center flex-wrap">
                            <label>Status</label>
                            <select name="status" class="form-select">
                                <option value="NotSent" {{ request('status') == 'NotSent' ? 'selected' : '' }}>Not Sent</option>
                                <option value="All" {{ request('status') == 'All' ? 'selected' : '' }}>All</option>
                                <option value="Sent" {{ request('status') == 'Sent' ? 'selected' : '' }}>Sent</option>
                            </select>
                            <button type="submit" class="btn btn-primary">Filter</button>
                        </form>
                    </div>

                    <!-- Ticket Table -->
                    <div class="table-responsive">
                        <table id="_ajax_datatable" class="table table-striped">
                            <thead>
                                <tr>
                                    <th>QR Code</th>
                                    <th>Ticket Type</th>
                                </tr>
                            </thead>
                            <tbody>
                                @if(!$tickets)
                                    <tr>
                                        <td colspan="2" class="text-center">No tickets found</td>
                                    </tr>
                                @else
                                @foreach($tickets as $ticket)
                                <tr>
                                    <td>{{ $ticket['visualId'] }}</td>
                                    <td>{{ $ticket['ticketType'] }}</td>
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

    <!-- Dynamic Send Ticket Modal -->
    <div class="modal fade" id="sendTicketModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-lg"> <!-- large modal for many checkboxes -->
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Send Ticket</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body" id="modalContent">
                    <p class="text-center">Loading...</p>
                </div>
            </div>
        </div>
    </div>

    @include('portal.footer')
</section>

@push('scripts')
    <!-- DataTables JS -->
    <script src="{{ asset('admin/assets/lib/datatables/jquery.dataTables.min.js') }}"></script>
    <script src="{{ asset('admin/assets/lib/datatables/dataTables.responsive.min.js') }}"></script>

    <!-- Bootstrap JS Bundle -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.1/dist/js/bootstrap.bundle.min.js"></script>

    <!-- jQuery -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.6.4/jquery.min.js"></script>

    <!-- Toastify -->
    <link rel="stylesheet" type="text/css" href="https://cdn.jsdelivr.net/npm/toastify-js/src/toastify.min.css">
    <script type="text/javascript" src="https://cdn.jsdelivr.net/npm/toastify-js"></script>

    <script>
    $(document).ready(function(){

        // Open modal and load tickets as checkboxes
        $('#openSendTicketModal').click(function() {
            $('#sendTicketModal').modal('show');

            $.ajax({
                url: "{{ route('contract.ajax-tickets', $slug) }}",
                type: 'GET',
                success: function(data) {
                    let formHtml = `
                        <form id="sendTicketForm">
                            @csrf
                            <div class="mb-3">
                                <label class="form-label"><input type="checkbox" id="selectAll"> Select All</label>
                            </div>
                            <div class="mb-3" style="max-height:300px; overflow-y:auto; border:1px solid #ddd; padding:10px;">`;

                        if(data.tickets.length > 0){
                            data.tickets.forEach(ticket => {
                                formHtml += `
                                    <div class="form-check">
                                        <input class="form-check-input ticket-checkbox" type="checkbox" name="ticket_ids[]" value="${ticket.visualId}" id="ticket_${ticket.visualId}">
                                            <label class="form-check-label" for="ticket_${ticket.visualId}">${ticket.ticketType} - ${ticket.visualId}</label>
                                        </div>`;
                                });
                            }else{
                                formHtml += '<p class="text-center">No tickets found.</p>';
                            }

                    formHtml += `</div>
                        <div class="mb-3">
                            <label class="form-label">Recipient Name</label>
                            <input type="text" name="name" class="form-control" placeholder="Enter recipient name" required>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Recipient Email</label>
                            <input type="email" name="email" class="form-control" placeholder="Enter recipient email" required>
                        </div>
                        <div class="text-end">
                            <button type="submit" class="btn btn-primary">Send Ticket</button>
                        </div>
                        </form>`;

                    $('#modalContent').html(formHtml);

                    // Select All functionality
                    $('#selectAll').change(function(){
                        $('.ticket-checkbox').prop('checked', this.checked);
                    });
                },
                error: function() {
                    $('#modalContent').html('<p class="text-danger text-center">Failed to load tickets.</p>');
                }
            });
        });

        // Handle form submission via AJAX
        $(document).on('submit', '#sendTicketForm', function(e){
            e.preventDefault();

            // Collect checked ticket IDs
            let ticket_ids = [];
            $('.ticket-checkbox:checked').each(function(){
                ticket_ids.push($(this).val());
            });

            if(ticket_ids.length === 0){
                Toastify({
                    text: "Please select at least one ticket",
                    duration: 3000,
                    gravity: "top",
                    position: "right",
                    className: "toast-error"
                }).showToast();
                return;
            }

            // Collect other fields
            let name = $('input[name="name"]').val();
            let email = $('input[name="email"]').val();
            let _token = $('input[name="_token"]').val();

            $.ajax({
                url: "{{ route('contract.send-ticket-email') }}",
                type: 'POST',
                data: {
                    ticket_ids: ticket_ids,
                    name: name,
                    email: email,
                    _token: _token
                },
                success: function(response){
                    $('#sendTicketModal').modal('hide');
                    console.log(response.message)
                    // window.location.reload();
                    Toastify({
                        text: response.message || 'Tickets sent successfully!',
                        duration: 3000,
                        gravity: "top",
                        position: "right",
                        className: "toast-success"
                    }).showToast();
                },
                error: function(xhr){
                    console.log(xhr)
                    let errors = xhr.responseJSON?.errors;
                    let errorMsg = '';
                    if(errors){
                        $.each(errors, function(key, val){
                            errorMsg += val + '\n';
                        });
                    } else {
                        errorMsg = xhr;
                    }

                    Toastify({
                        text: errorMsg,
                        duration: 3000,
                        gravity: "top",
                        position: "right",
                        className: "toast-error"
                    }).showToast();
                }
            });
        });

    });
    </script>
@endpush
@endsection