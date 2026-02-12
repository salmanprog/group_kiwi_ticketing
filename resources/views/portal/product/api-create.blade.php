@extends('portal.master')

@section('content')
    @push('stylesheets')
        <link href="{{ asset('admin/assets/scss/products.css') }}" rel="stylesheet" type="text/css">
    @endpush
    <section class="main-content">
        <div class="row justify-content-center">
            <div class="col-lg-8">
                @include('portal.flash-message')

                <div class="card">
                    <div class="card-header custfor-flex-header">
                        <h3>Add New Ticket</h3>
                    </div>

                    <div class="card-body">
                        <form method="POST" action="{{ route('product.api.store') }}">
                            @csrf
                            <input type="hidden" name="ticketId" id="ticketId" value="0">
                            <input type="hidden" name="ticketType" id="ticketTypeHidden" value="Tickets">
                            <input type="hidden" name="saleChannel" id="saleChannelHidden" value="Groups">
                            <div class="form-section">
                                <div class="row">
                                    <div class="col-md-6">
                                        <label class="form-label">Ticket Name</label>

                                        <select name="ticketName" id="ticketNameSelect" class="form-control" required>
                                            <option value="">Select Ticket Name</option>

                                            @foreach ($tickets as $ticket)
                                                <option value="{{ $ticket['ticketName'] }}" data-id="{{ $ticket['id'] }}"
                                                    data-price="{{ $ticket['ticketPrice'] }}"
                                                    data-type="{{ $ticket['ticketType'] }}"
                                                    data-channel="{{ $ticket['saleChannel'] }}">
                                                    {{ $ticket['ticketName'] }}
                                                </option>
                                            @endforeach

                                            <option value="__new__">➕ Add New Ticket</option>
                                        </select>
                                    </div>

                                    <div class="col-md-6 d-none" id="newTicketWrapper">
                                        <label class="form-label">New Ticket Name</label>
                                        <input type="text" name="newTicketName" class="form-control"
                                            placeholder="Enter new ticket name">
                                    </div>

                                    <div class="col-md-6">
                                        <label class="form-label">Ticket Type</label>
                                        <input type="text" name="ticketType" id="ticketType" class="form-control"
                                            value="Tickets" disabled>
                                    </div>

                                    <div class="col-md-6 mt-3">
                                        <label class="form-label">Sale Channel</label>
                                        <input type="text" name="saleChannel" id="saleChannel" class="form-control"
                                            value="Groups" disabled>
                                    </div>

                                    <div class="col-md-6 mt-3">
                                        <label class="form-label">Price</label>
                                        <input type="number" step="0.01" name="ticketPrice" id="ticketPrice"
                                            class="form-control" required>
                                    </div>
                                    <div class="col-md-12">
                                        <div class="form-group">
                                            <label class="form-label">Description</label>
                                            <textarea name="description" id="description" class="form-control" style="height: 120px;"
                                                placeholder="Enter description here...">{{ old('description') }}</textarea>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="form-actions mt-4">
                                <button type="submit" class="btn btn-primary">Add Ticket</button>
                                <a href="{{ route('product.index') }}" class="btn btn-secondary">Cancel</a>
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

            const select = document.getElementById('ticketNameSelect');
            const newWrapper = document.getElementById('newTicketWrapper');
            const ticketIdInput = document.getElementById('ticketId');

            select.addEventListener('change', function() {

                // Add New Ticket selected
                if (this.value === '__new__') {
                    newWrapper.classList.remove('d-none');
                    ticketIdInput.value = 0;
                    document.getElementById('ticketType').value = 'Tickets';
                    document.getElementById('saleChannel').value = 'Groups';
                    document.getElementById('ticketTypeHidden').value = 'Tickets';
                    document.getElementById('saleChannelHidden').value = 'Groups';
                    document.getElementById('ticketPrice').value = '';
                    return;
                }

                newWrapper.classList.add('d-none');

                const option = this.options[this.selectedIndex];
                ticketIdInput.value = option.getAttribute('data-id');
                document.getElementById('ticketType').value =
                    option.getAttribute('data-type') ?? '';

                document.getElementById('saleChannel').value =
                    option.getAttribute('data-channel') ?? '';

                document.getElementById('ticketTypeHidden').value =
                    option.getAttribute('data-type') ?? '';

                document.getElementById('saleChannelHidden').value =
                    option.getAttribute('data-channel') ?? '';

                document.getElementById('ticketPrice').value =
                    option.getAttribute('data-price') ?? '';
            });

        });
    </script>
@endsection
