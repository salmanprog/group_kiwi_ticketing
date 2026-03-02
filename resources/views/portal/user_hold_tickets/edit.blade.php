@extends('portal.master')

@section('content')

<style>
.modal-body-scroll { max-height: 400px; overflow-y: auto; }
.seat-btn { margin: 2px; }
.seat-btn.selected { background-color: #0d6efd !important; color: #fff !important; }
</style>

<section class="main-content" style="background:#f8faf9; padding:40px; min-height:100vh;">
    <div class="container">
        <div class="card" style="border:none; border-radius:12px; box-shadow:0 4px 20px rgba(0,0,0,0.08);">

            <!-- Header -->
            <div class="card-header" style="background:#ffffff; padding:20px 30px; border-bottom:1px solid #e5e7eb;">
                <h3 style="margin:0; font-weight:600;">Hold Ticket Details</h3>
            </div>

            <!-- Body -->
            <div class="card-body" style="padding:30px;">

                <!-- Top Section -->
                <div class="row mb-4">
                    <form action="{{ route('hold-tickets.update', $record->slug) }}" method="POST" class="row g-3">
                        @csrf
                        @method('PUT')

                        <div class="col-md-4">
                            <label for="estimate_slug" class="form-label fw-semibold">Estimate</label>
                            <input type="text" class="form-control" id="estimate_slug" name="estimate_slug" 
                                value="{{ $record->estimate_slug }}" readonly>
                        </div>

                        <div class="col-md-4">
                            <label for="hold_date" class="form-label fw-semibold">Hold Date</label>
                            <input type="date" class="form-control" id="hold_date" name="hold_date" 
                                value="{{ $record->hold_date }}" readonly>
                        </div>

                        <div class="col-md-4">
                            <label for="expiry_date" class="form-label fw-semibold">Expiry Date</label>
                            <input type="date" class="form-control" id="expiry_date" name="expiry_date" 
                                value="{{ $record->expiry_date }}" readonly>
                        </div>
                    </form>
                </div>

                <!-- Selected Products Table -->
                <div style="margin-top:40px;">
                    <h5 style="font-weight:600; margin-bottom:20px;">Product Details</h5>

                    <div class="table-responsive">
                        <table class="table table-bordered" id="selectedProductsTable">
                            <thead style="background:#f3f4f6;">
                                <tr>
                                    <th>Product Name</th>
                                    <th width="120">Quantity / Seats</th>
                                </tr>
                            </thead>
                            <tbody>
                                @if($record->user_hold_ticket_items->isEmpty())
                                    <tr class="no-record">
                                        <td colspan="2" class="text-center">No record found</td>
                                    </tr>
                                @else
                                    @foreach($record->user_hold_ticket_items as $p)
                                        <tr data-product-id="{{ $p->id }}">
                                            <td>{{ $p->name }}</td>
                                            <td>{{ $p->quantity }}</td>
                                        </tr>
                                    @endforeach
                                @endif
                            </tbody>
                        </table>
                    </div>

                    <button type="button" class="btn btn-primary btn-sm" data-toggle="modal" data-target="#productModal">
                        Add Product
                    </button>
                </div>
            </div>
        </div>
    </div>

    <!-- Product Modal -->
    <div class="modal fade" id="productModal" tabindex="-1">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">

                <div class="modal-header">
                    <h5 class="modal-title">Select Products</h5>
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                </div>

                <div class="modal-body modal-body-scroll">

                    <table class="table table-hover">
                        <thead>
                            <tr>
                                <th>Select</th>
                                <th>Product Name</th>
                                <th>Quantity</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($products as $product)
                                <tr>
                                    <td><input type="checkbox" class="product-checkbox"></td>
                                    <td>{{ $product->name }}</td>
                                    <td>
                                        <input type="number" class="form-control form-control-sm product-qty" min="1" value="1">
                                    </td>
                                    <td>
                                        <button type="button"
                                                class="btn btn-secondary btn-sm hold-btn"
                                                disabled
                                                data-user_hold_ticket_id="{{ $record->id }}"
                                                data-product-id="{{ $product->id }}"
                                                data-product-slug="{{ $product->slug }}"
                                                data-estimate-id="{{ $record->estimate_id }}"
                                                data-hold-date="{{ $record->hold_date }}"
                                                data-expiry-date="{{ $record->expiry_date }}"
                                                data-has-seats="{{ $product->hasSeats == 1 ? 'true' : 'false' }}">
                                            Hold
                                        </button>
                                    </td>
                                </tr>
                                <tr class="validation-row d-none">
                                    <td colspan="4">
                                        <div class="alert alert-danger validation-message mb-0"></div>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>

                </div>

                <div class="modal-footer">
                    <button class="btn btn-secondary btn-sm" data-dismiss="modal">Cancel</button>
                </div>

            </div>
        </div>
    </div>

</section>

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

<script>
$(document).ready(function(){

    // Checkbox click
    $(document).on('change', '.product-checkbox', function () {
        let $checkbox = $(this);
        let $row = $checkbox.closest('tr');
        let $btn = $row.find('.hold-btn');
        let $validationRow = $row.next('.validation-row');
        let $validationBox = $validationRow.find('.validation-message');

        let productSlug = $btn.data('product-slug');
        let holdDate = $btn.data('hold-date');

        if ($checkbox.is(':checked')) {
            $.ajax({
                url: "{{ route('hold-tickets.check') }}",
                type: "POST",
                data: { _token: "{{ csrf_token() }}", product_slug: productSlug, hold_date: holdDate },
                beforeSend: function () {
                    $checkbox.prop('disabled', true);
                    $btn.prop('disabled', true).text('Checking...');
                    $validationRow.addClass('d-none');
                    $row.next('.cabana-row').remove();
                },
                success: function (response) {
                    if (!response.status) {
                        $btn.prop('disabled', true).text('Hold');
                        $checkbox.prop('checked', false);
                        $validationBox.html(response.message);
                        $validationRow.removeClass('d-none');
                        $row.next('.cabana-row').remove();
                    } else {
                        $btn.prop('disabled', false)
                            .removeClass('btn-secondary')
                            .addClass('btn-primary')
                            .text('Hold');
                        $validationRow.addClass('d-none');

                        // Insert cabana seats HTML inline
                        $row.next('.cabana-row').remove();
                        if(response.html){
                            let cabanaRow = `<tr class="cabana-row">
                                                <td colspan="4">${response.html}</td>
                                              </tr>`;
                            $row.after(cabanaRow);
                        }
                    }
                },
                error: function () {
                    $btn.prop('disabled', true).text('Hold');
                    $checkbox.prop('checked', false);
                    $validationBox.html('Something went wrong. Please try again.');
                    $validationRow.removeClass('d-none');
                    $row.next('.cabana-row').remove();
                },
                complete: function () {
                    $checkbox.prop('disabled', false);
                }
            });

        } else {
            $btn.prop('disabled', true)
                .removeClass('btn-primary btn-success')
                .addClass('btn-secondary')
                .text('Hold');

            $validationRow.addClass('d-none');
            $row.next('.cabana-row').remove();
        }
    });

    // Seat button click
    $(document).on('click', '.seat-btn', function(){
        let $btn = $(this);
        let $row = $btn.closest('tr').prev('tr'); // product row
        let quantity = parseInt($row.find('.product-qty').val());

        if($btn.data('selected') == 0){
            let selectedCount = $btn.closest('td').find('.seat-btn.selected').length;
            if(selectedCount >= quantity){
                Toastify({
                    text: 'You have already selected maximum seats for this product.',
                    duration: 3000,
                    gravity: 'top',
                    position: 'right',
                    className: 'error'
                }).showToast();
                return;
            }
            $btn.addClass('selected btn-primary').removeClass('btn-outline-primary');
            $btn.data('selected',1);
        } else {
            $btn.removeClass('selected btn-primary').addClass('btn-outline-primary');
            $btn.data('selected',0);
        }
    });

    // Hold button click
    $(document).on('click', '.hold-btn', function(){
        let $btn = $(this);
        let $row = $btn.closest('tr');
        let $validationRow = $row.nextAll('.validation-row').first();
        let $validationBox = $validationRow.find('.validation-message');
        let quantity = parseInt($row.find('.product-qty').val());

        // Get selected seats
        let selectedSeats = [];
        $row.next('.cabana-row').find('.seat-btn.selected').each(function(){
            selectedSeats.push($(this).data('seat'));
        });

        if($btn.data('has-seats') == 'true') {
            if(selectedSeats.length != quantity){
                Toastify({
                    text: 'Please select exactly ' + quantity + ' seats.',
                    duration: 3000,
                    gravity: 'top',
                    position: 'right',
                    className: 'error'
                }).showToast();
                return;
            }
        }

        $btn.prop('disabled', true).text('Processing...');
        $validationBox.html('');
        $validationRow.addClass('d-none');

        $.ajax({
            url: "{{ route('hold-tickets-item') }}",
            type: "POST",
            data: {
                _token: $('meta[name="csrf-token"]').attr('content'),
                product_id: $btn.data('product-id'),
                product_slug: $btn.data('product-slug'),
                quantity: quantity,
                seats: selectedSeats,
                estimate_id: $btn.data('estimate-id'),
                hold_date: $btn.data('hold-date'),
                expiry_date: $btn.data('expiry-date'),
                user_hold_ticket_id: $btn.data('user_hold_ticket_id')
            },
            dataType: "json",
            success: function(res){
                if(res.status === true){
                    $btn.text('Held ✅').removeClass('btn-primary').addClass('btn-success');

                    let productId = $btn.data('product-id');
                    let productName = $row.find('td:nth-child(2)').text();

                    $('#selectedProductsTable tbody .no-record').remove();

                    let existingRow = $('#selectedProductsTable tbody').find(`tr[data-product-id="${productId}"]`);
                    // if(existingRow.length){
                    //     existingRow.find('td:nth-child(2)').text(quantity);
                    // } else {
                        let newRow = `<tr data-product-id="${productId}">
                                        <td>${productName}</td>
                                        <td>${quantity}</td>
                                      </tr>`;
                        $('#selectedProductsTable tbody').append(newRow);
                    // }

                } else {
                    $btn.prop('disabled', false).text('Hold');
                    $validationBox.html(res.message);
                    $validationRow.removeClass('d-none');
                }
            },
            error: function(xhr){
                $btn.prop('disabled', false).text('Hold');

                let errorHtml = '';
                if(xhr.status === 422 && xhr.responseJSON.errors){
                    $.each(xhr.responseJSON.errors, function(key,value){
                        errorHtml += `<div>${value[0]}</div>`;
                    });
                } else if(xhr.responseJSON && xhr.responseJSON.message){
                    errorHtml = xhr.responseJSON.message;
                } else {
                    errorHtml = 'Something went wrong. Please try again.';
                }

                $validationBox.html(errorHtml);
                $validationRow.removeClass('d-none');
            }
        });
    });

});
</script>

@endsection