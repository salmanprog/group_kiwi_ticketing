{{-- @extends('portal.master')

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
                    <button class="btn btn-secondary btn-sm" data-dismiss="modal">Close</button>
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

@endsection --}}


@extends('portal.master')

@section('content')
    @push('stylesheets')
        <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
        <link rel="stylesheet" type="text/css" href="https://cdn.jsdelivr.net/npm/toastify-js/src/toastify.min.css">
    @endpush

    <style>
        /* --- Matching UI Styling from Create Page --- */
        .main-content {
            background: #f8faf9;
            min-height: 100vh;
            padding: 30px;
            padding-top: 90px;
        }

        .custfor-flex-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
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

        .header-content h3 {
            margin: 0;
            font-weight: 600;
            font-size: 18px;
        }

        .header-actions .btn-outline {
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

        .btn-outline:hover {
            background: rgba(255, 255, 255, 0.3);
            color: white;
            transform: translateY(-1px);
        }

        .card-body {
            padding: 30px;
        }

        .form-section {
            background: #ffffff;
            border: 1px solid #eaeaea;
            border-radius: 8px;
            padding: 25px;
            margin-bottom: 20px;
            transition: transform 0.2s ease;
        }

        .form-section:hover {
            transform: translateY(-1px);
        }

        .section-header {
            display: flex;
            align-items: center;
            gap: 12px;
            margin-bottom: 25px;
            padding-bottom: 15px;
            border-bottom: 1px solid #e0e6e3;
        }

        .section-header h5 {
            margin: 0;
            color: #2c3e50;
            font-weight: 600;
            font-size: 1.1rem;
            flex: 1;
        }

        .section-badge {
            background: #f3f4f6;
            color: #374151;
            padding: 4px 10px;
            border-radius: 4px;
            font-size: 0.8rem;
            font-weight: 600;
            border: 1px solid #e5e7eb;
        }

        .form-group {
            margin-bottom: 25px;
        }

        .form-label {
            display: flex;
            align-items: center;
            gap: 10px;
            font-weight: 600;
            color: #2c3e50;
            margin-bottom: 8px;
            font-size: 0.95rem;
        }

        .required {
            color: #e74c3c;
            margin-left: 4px;
        }

        .form-control {
            border: 1px solid #dce4e0;
            border-radius: 6px;
            padding: 12px 15px;
            font-size: 0.95rem;
            transition: all 0.3s ease;
            background: #fff;
        }

        .form-control:focus {
            border-color: #A0C242 !important;
            box-shadow: 0 0 0 3px rgba(160, 194, 66, 0.1);
            outline: none;
        }

        .form-control[readonly] {
            background-color: #f9fafb;
            cursor: not-allowed;
        }

        .table {
            margin-bottom: 0;
        }

        .table thead th {
            background: #f3f4f6;
            border-bottom: 2px solid #e5e7eb;
            color: #374151;
            font-weight: 600;
            font-size: 0.9rem;
        }

        .table tbody tr:hover {
            background-color: #f9fafb;
        }

        .btn-primary {
            background: linear-gradient(135deg, #A0C242 0%, #8AB933 100%);
            color: white;
            box-shadow: 0 2px 8px rgba(160, 194, 66, 0.3);
            border: none;
            padding: 10px 25px;
            border-radius: 6px;
            font-weight: 600;
            font-size: 14px;
            transition: all 0.2s ease;
            cursor: pointer;
        }

        .btn-primary:hover {
            transform: translateY(-1px);
            box-shadow: 0 4px 12px rgba(160, 194, 66, 0.4);
        }

        .btn-primary.btn-sm {
            padding: 6px 15px;
            font-size: 12px;
        }

        .btn-secondary {
            background: #ffffff;
            border: 1px solid #d1d5db;
            color: #374151;
            padding: 10px 25px;
            border-radius: 6px;
            font-weight: 600;
            font-size: 14px;
            transition: all 0.2s ease;
            cursor: pointer;
        }

        .btn-secondary:hover {
            background: #f3f4f6;
            transform: translateY(-1px);
        }

        .btn-secondary.btn-sm {
            padding: 6px 15px;
            font-size: 12px;
        }

        .btn-success {
            background: #10b981;
            color: white;
            border: none;
        }

        .btn-success:hover {
            background: #059669;
        }

        .seat-btn {
            margin: 2px;
            padding: 5px 10px;
            border-radius: 4px;
            border: 1px solid #d1d5db;
            background: white;
            cursor: pointer;
            transition: all 0.2s ease;
            font-size: 12px;
        }

        .seat-btn.selected {
            background-color: #A0C242 !important;
            color: #fff !important;
            border-color: #A0C242 !important;
        }

        .seat-btn:hover:not(.selected) {
            background: #f3f4f6;
            transform: translateY(-1px);
        }

        .modal-content {
            border-radius: 12px;
            border: none;
            box-shadow: 0 20px 25px -5px rgba(0, 0, 0, 0.1);
        }

        .modal-header {
            border-bottom: 1px solid #e5e7eb;
            padding: 20px 25px;
            background: #f9fafb;
            border-radius: 12px 12px 0 0;
        }

        .modal-title {
            font-weight: 600;
            color: #1f2937;
        }

        .modal-body-scroll {
            max-height: 500px;
            overflow-y: auto;
            padding: 20px;
        }

        .modal-footer {
            border-top: 1px solid #e5e7eb;
            padding: 15px 20px;
        }

        .alert-danger {
            background-color: #fee2e2;
            border-color: #fecaca;
            color: #991b1b;
            padding: 10px;
            border-radius: 6px;
            font-size: 13px;
        }

        .form-actions {
            display: flex;
            gap: 15px;
            justify-content: flex-end;
            padding-top: 20px;
            margin-top: 20px;
            border-top: 1px solid #eaeaea;
        }

        /* Toastify Custom Styles */
        .toastify-error {
            background: #ef4444 !important;
        }

        .toastify-success {
            background: #A0C242 !important;
        }

        /* Responsive */
        @media (max-width: 768px) {
            .main-content {
                padding: 15px;
                padding-top: 90px;
            }

            .custfor-flex-header {
                flex-direction: column;
                gap: 15px;
                text-align: center;
            }

            .header-content {
                justify-content: center;
            }

            .card-body {
                padding: 20px;
            }

            .form-section {
                padding: 20px;
            }

            .section-header {
                flex-direction: column;
                text-align: center;
                gap: 10px;
            }

            .form-actions {
                flex-direction: column;
            }

            .btn {
                width: 100%;
            }
        }
    </style>

    <section class="main-content">
        <div class="row justify-content-center">
            <div class="col-lg-10">
                @include('portal.flash-message')
                <div class="card">
                    <div class="card-header custfor-flex-header">
                        <div class="header-content">
                            <h3>Hold Ticket Details</h3>
                        </div>
                        <div class="header-actions">
                            <a href="{{ route('hold-tickets.index') }}" class="btn btn-outline">
                                Back to List
                            </a>
                        </div>
                    </div>
                    <div class="card-body">
                        <!-- Top Section -->
                        <div class="form-section">
                            <div class="section-header">
                                <h5>Ticket Information</h5>
                                <span class="section-badge">Read Only</span>
                            </div>

                            <div class="row">
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label class="form-label">
                                            Estimate
                                            <span class="required">*</span>
                                        </label>
                                        <input type="text" class="form-control" name="estimate_slug" 
                                            value="{{ $record->estimate_slug }}" readonly>
                                    </div>
                                </div>

                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label class="form-label">
                                            Hold Date
                                            <span class="required">*</span>
                                        </label>
                                        <input type="date" class="form-control" name="hold_date" 
                                            value="{{ $record->hold_date }}" readonly>
                                    </div>
                                </div>

                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label class="form-label">
                                            Expiry Date
                                            <span class="required">*</span>
                                        </label>
                                        <input type="date" class="form-control" name="expiry_date" 
                                            value="{{ $record->expiry_date }}" readonly>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Selected Products Table -->
                        <div class="form-section">
                            <div class="section-header">
                                <h5>Product Details</h5>
                                <span class="section-badge">Manage Products</span>
                            </div>

                            <div class="table-responsive">
                                <table class="table table-bordered" id="selectedProductsTable">
                                    <thead>
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

                            <div class="form-actions" style="margin-top: 20px; justify-content: flex-start;">
                                <button type="button" class="btn btn-primary btn-sm" data-toggle="modal" data-target="#productModal">
                                    Add Product
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Product Modal -->
    <div class="modal fade" id="productModal" tabindex="-1">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Select Products</h5>
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                </div>
                <div class="modal-body modal-body-scroll">
                    <div class="table-responsive">
                        <table class="table table-hover">
                            <thead>
                                <tr>
                                    <th width="50">Select</th>
                                    <th>Product Name</th>
                                    <th width="120">Quantity</th>
                                    <th width="100">Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($products as $product)
                                    <tr>
                                        <td class="text-center">
                                            <input type="checkbox" class="product-checkbox">
                                        </td>
                                        <td>{{ $product->name }}</td>
                                        <td>
                                            <input type="number" class="form-control form-control-sm product-qty" min="1" value="1" style="width: 100px;">
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
                </div>
                <div class="modal-footer">
                    <button class="btn btn-secondary btn-sm" data-dismiss="modal">Cancel</button>
                </div>
            </div>
        </div>
    </div>

    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
    <script type="text/javascript" src="https://cdn.jsdelivr.net/npm/toastify-js"></script>

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
                                                    <td colspan="4">
                                                        <div style="padding: 15px; background: #f9fafb; border-radius: 6px; margin-top: 10px;">
                                                            <h6 style="margin-bottom: 10px; font-weight: 600;">Select Seats</h6>
                                                            ${response.html}
                                                        </div>
                                                    </td>
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

        // Seat button click with Toastify
        $(document).on('click', '.seat-btn', function(){
            let $btn = $(this);
            let $row = $btn.closest('.cabana-row').prev('tr');
            let quantity = parseInt($row.find('.product-qty').val());

            if($btn.data('selected') == 0){
                let selectedCount = $btn.closest('td').find('.seat-btn.selected').length;
                if(selectedCount >= quantity){
                    Toastify({
                        text: 'You have already selected maximum seats for this product.',
                        duration: 3000,
                        gravity: 'top',
                        position: 'right',
                        className: 'toastify-error',
                        style: {
                            background: '#ef4444'
                        }
                    }).showToast();
                    return;
                }
                $btn.addClass('selected');
                $btn.data('selected', 1);
            } else {
                $btn.removeClass('selected');
                $btn.data('selected', 0);
            }
        });

        // Hold button click with Toastify
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
                        className: 'toastify-error',
                        style: {
                            background: '#ef4444'
                        }
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
                    _token: "{{ csrf_token() }}",
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
                        $btn.text('Held ✓').removeClass('btn-primary').addClass('btn-success');
                        $btn.prop('disabled', true);

                        Toastify({
                            text: 'Product added successfully!',
                            duration: 3000,
                            gravity: 'top',
                            position: 'right',
                            className: 'toastify-success',
                            style: {
                                background: '#A0C242'
                            }
                        }).showToast();

                        let productId = $btn.data('product-id');
                        let productName = $row.find('td:nth-child(2)').text();

                        $('#selectedProductsTable tbody .no-record').remove();

                        let existingRow = $('#selectedProductsTable tbody').find(`tr[data-product-id="${productId}"]`);
                        if(!existingRow.length){
                            let newRow = `<tr data-product-id="${productId}">
                                              <td>${productName}</td>
                                              <td>${quantity}</td>
                                            </tr>`;
                            $('#selectedProductsTable tbody').append(newRow);
                        }
                    } else {
                        $btn.prop('disabled', false).text('Hold');
                        $validationBox.html(res.message);
                        $validationRow.removeClass('d-none');
                        
                        Toastify({
                            text: res.message,
                            duration: 3000,
                            gravity: 'top',
                            position: 'right',
                            className: 'toastify-error',
                            style: {
                                background: '#ef4444'
                            }
                        }).showToast();
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
                    
                    Toastify({
                        text: errorHtml,
                        duration: 3000,
                        gravity: 'top',
                        position: 'right',
                        className: 'toastify-error',
                        style: {
                            background: '#ef4444'
                        }
                    }).showToast();
                }
            });
        });

    });
    </script>

@endsection