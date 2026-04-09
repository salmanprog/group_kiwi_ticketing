@extends('portal.master')

@section('content')
@push('stylesheets')
        <link href="{{ asset('admin/assets/scss/Estimate-style.css') }}" rel="stylesheet" type="text/css">
<style>
.toast-error {
    background: #cf3434ff !important;
    color: #fff !important;
    font-size: 14px;
    padding: 16px 20px;
    border-radius: 6px;
}

.modify-editable-description {
    cursor: pointer;
}
.modify-editable-description:hover {
    background: #f8f9fa;
}

.spinner {
    width: 14px;
    height: 14px;
    border: 2px solid #ccc;
    border-top: 2px solid #3498db;
    border-radius: 50%;
    display: inline-block;
    margin-left: 8px;
    animation: spin 0.6s linear infinite;
}

@keyframes spin {
    100% { transform: rotate(360deg); }
}


.fade-in {
    animation: fadeIn 0.3s ease-in-out;
}

@keyframes fadeIn {
    from { opacity: 0; transform: translateY(3px); }
    to { opacity: 1; transform: translateY(0); }
}

.modify-editable-description.loading {
    opacity: 0.6;
    pointer-events: none;
}

</style>
@endpush

<meta name="csrf-token" content="{{ csrf_token() }}">
<section class="main-content">
<div class="row">
    <div class="col-md-10 offset-md-1">
        @include('portal.flash-message')

        <div class="estimate-wrapper">
            {{-- Header --}}
            <div class="estimate-header">
                <div class="estimate-title">
                    <i class="fas fa-file-invoice-dollar me-2"></i>Modify Contract
                </div>
              
                <div class="estimate-meta">
                    <span class="estimate-number">#{{ ucfirst($data->slug) }}</span>
                </div>
            </div>
        
                            <div class="form-row mt-4">
                                <div class="col-12">
                                    <h5 class="mb-3" style="color: #1f2937;font-size: 18px;">
                                        Product Details
                                    </h5>
                                    <div class="forref">
                                        <table class="table product-table" id="productTable">
                                            <thead>
                                                <tr>
                                                    <th>Product Name</th>
                                                    <th>Description</th>
                                                    <th>Quantity</th>
                                                    <th>Product Price</th>
                                                    <th>Total</th>
                                                    <th class="no-print">Action</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                @if($data->items && $data->items->count())
                                                    @foreach($data->items as $item)
                                                        <tr data-id="{{ $item->id }}">
                                                            <td>
                                                                {{ $item->name }}
                                                                @if($item->itemTaxes && $item->itemTaxes->count())
                                                                    <small class="text-muted d-block" data-taxes='[
                                                                                                    @foreach($item->itemTaxes as $tax)
                                                                                                        {"id":{{ $tax->id }},"name":"{{ $tax->name }}","percent":{{ $tax->percentage }}}@if(!$loop->last),@endif
                                                                                                    @endforeach
                                                                                                ]'>
                                                                                Apply Taxes: 
                                                                                @foreach($item->itemTaxes as $tax)
                                                                                    {{ $tax->name }} (${{ bcdiv($tax->amount, 1, 2) }})  @if(!$loop->last), @endif
                                                                                @endforeach
                                                                    </small>
                                                                @endif
                                                            </td>
                                                            <!-- <td>{{ $item->description }}</td> -->
                                                             <td class="modify-editable-description" 
                                                                data-id="{{ $item->id }}"
                                                                data-url="{{ route('contract.products.update-description') }}"
                                                                data-csrf="{{ csrf_token() }}">
                                                                
                                                                <span class="desc-text">{{ $item->description }}</span>
                                                            </td>
                                                            <td>{{ $item->quantity }}</td>
                                                            <td>${{ number_format($item->price, 2) }}</td>
                                                            <td class="item-total">${{ number_format($item->total_price, 2) }}</td>
                                                            <td class="no-print">
                                                                <span class="f-line for-d-g">
                                                                    <button class="btn btn-sm btn-primary edit-item-modify foest-edit"
                                                                            data-url="{{ route('estimate.products.update') }}"
                                                                            data-contractmodifiedid="{{ $data->id }}"
                                                                            data-csrf="{{ csrf_token() }}"
                                                                            data-id="{{ $item->id }}"
                                                                            data-name="{{ $item->name }}"
                                                                            data-description="{{ $item->description }}"
                                                                            data-quantity="{{ $item->quantity }}"
                                                                            data-unit="{{ $item->unit }}"
                                                                            data-price="{{ $item->price }}">
                                                                        <a href="javascript:void(0);" class="cust-edit">
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
                                                                    </button>
                                                                    <button class="btn btn-sm btn-danger remove-modify-item cust-btn-delete"
                                                                            data-url="{{ route('contract.modify.delete-product') }}"
                                                                            data-id="{{ $item->id }}"
                                                                            data-contractmodifiedid="{{ $data->id }}"
                                                                            data-csrf="{{ csrf_token() }}">
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
                                                                    </button>
                                                                </span>
                                                            </td>
                                                        </tr>
                                                    @endforeach
                                                @else
                                                    <tr class="no-items">
                                                        <td colspan="5" class="text-center">No products added yet.</td>
                                                    </tr>
                                                @endif
                                            </tbody>
                                            <tfoot>
                                                <tr>
                                                    <th colspan="4" class="text-end">Subtotal:</th>
                                                    <th id="subtotal">$0.00</th>
                                                    {{-- <th></th> --}}
                                                </tr>

                                                @if($data && $data->taxes->count())
                                                <tr>
                                                    <th colspan="4" class="text-end">Tax:
                                                        <div class="d-flex flex-wrap gap-2 justify-content-end">
                                                            @foreach($data->taxes as $tax)
                                                                <div class="border rounded px-2 py-1 d-flex align-items-center gap-1"
                                                                    data-tax-id="{{ $tax->id }}">

                                                                    <small class="fw-semibold">
                                                                        {{ $tax->name }} ({{ $tax->percent }}%)

                                                                       ${{
                                                                            bcdiv($tax->amount, 1, 2)
                                                                        }}
                                                                    </small>

                                                                    <button class="btn btn-sm btn-link text-primary edit-tax"
                                                                            data-tax-id="{{ $tax->id }}"
                                                                            data-url="{{ route('contract.tax.get') }}"
                                                                            data-update-url="dsfsdf"
                                                                            data-csrf="{{ csrf_token() }}"
                                                                            data-contractmodifiedid="{{ $data->id }}"
                                                                            data-toggle="modal"
                                                                            data-target="#editTaxModifyModal">
                                                                        <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14"
                                                                                viewBox="0 0 24 24" fill="none" stroke="currentColor"
                                                                                stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
                                                                                class="lucide lucide-pen-line" aria-hidden="true">
                                                                                <path d="M13 21h8"></path>
                                                                                <path
                                                                                    d="M21.174 6.812a1 1 0 0 0-3.986-3.987L3.842 16.174a2 2 0 0 0-.5.83l-1.321 4.352a.5.5 0 0 0 .623.622l4.353-1.32a2 2 0 0 0 .83-.497z">
                                                                                </path>
                                                                        </svg>
                                                                    </button>

                                                                    <button class="btn btn-sm btn-link text-danger p-0 delete-tax"
                                                                            data-url="{{ route('contract.modify.tax-delete', $tax->id) }}"
                                                                            data-csrf="{{ csrf_token() }}">
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
                                                                    </button>
                                                                </div>
                                                            @endforeach
                                                        </div>
                                                    </th>
                                                    <th id="tax_amount">${{ number_format($data->taxes->sum('amount'), 2) }}</th>
                                                    {{-- <th></th> --}}
                                                </tr>
                                                @endif
                                               @if($data->discounts && $data->discounts->count())
                                                <tr class="fw-bold discount-row">
                                                    @foreach($data->discounts as $discount)
                                                        <th colspan="3" class="text-end">
                                                            <span class="dist-all">
                                                                Discount {{ $discount->name }}
                                                                <button class="btn btn-sm btn-link text-danger p-0 delete-modify-discount"
                                                                        data-url="{{ route('contract.modify.product.discount.delete', $discount->id) }}"
                                                                        data-csrf="{{ csrf_token() }}">
                                                                    <i class="fas fa-trash"></i>
                                                                </button>
                                                            </span>
                                                        </th>
                                                        <th class="discount_percent" data-discount-type="{{ $discount->type }}">
                                                            {{ $discount->value }} {{($discount->type == 'fixed') ? '$' : '%'}}
                                                        </th>
                                                        <th id='discount_amount_show'></th>
                                                    @endforeach
                                                </tr>
                                                @endif
                                                <tr class="fw-bold">
                                                    <th colspan="4" class="text-end">Total:</th>
                                                    <th id="total">$0.00</th>
                                                    {{-- <th></th> --}}
                                                </tr>
                                            </tfoot>

                                        </table>
                                        
                                    </div>
                                    <div class="action-buttons">
                                        <button type="button" class="btn btn-primary btn-sm no-print"
                                            data-toggle="modal" data-target="#productModal">
                                            <i class="fas fa-cube me-1"></i>Add Product
                                        </button>
                                        <button class="btn btn-info btn-sm no-print"
                                                data-toggle="modal"
                                                data-target="#taxModifyModal"
                                                data-url="{{ route('contract.modify.products.get') }}"
                                                data-csrf="{{ csrf_token() }}"
                                                data-contractmodifiedid="{{ $data->id }}">
                                            <i class="fas fa-percentage me-1"></i>Add Tax
                                        </button>
                                        @if($data && $data->discounts->count())
                                            @foreach($data->discounts as $discounts)
                                                <button type="button" class="btn btn-warning btn-sm no-print"
                                                    data-toggle="modal" data-target="#editmodifydiscountModal"
                                                    data-url="{{ route('contract.modify.product.discount.get') }}"
                                                    data-csrf="{{ csrf_token() }}"
                                                    data-contractmodifiedid="{{ $data->id }}"
                                                    data-discountid="{{ $discounts->id }}"
                                                >
                                                    <i class="fas fa-tag me-1"></i>Edit Discount
                                                </button>
                                            @endforeach
                                        @else
                                        <button type="button" class="btn btn-warning btn-sm no-print"
                                            data-toggle="modal" data-target="#discountModifyModal">
                                            <i class="fas fa-tag me-1"></i>Add Discount
                                        </button>
                                        @endif
                                    </div>
                                </div>
                            </div>
                                        
                            <div class="row">
                                <div class="col-md-12">
                                    <h5 class="mb-3" style="color: #1f2937;font-size: 18px;">
                                        Payment Schdule
                                    </h5>
                                    <form id="paymentScheduleFormEdit" method="POST" action="{{ route('contract.installments.save', $data->id) }}">
                                        <div class="sec-css">
                                        @csrf
                                        <input type="hidden" name="total_amount" id="total_amount" value="{{ $data->total_amount }}">
                                        @php
                                        $installments = $data->installments ?? collect();
                                        @endphp

                                        <div id="dynamicInputsContainer">
                                            @foreach($installments as $inst)
                                                <div class="row mb-2 installment-row" data-id="{{ $inst->id }}">
                                                    <div class="col-md-5">
                                                        <input type="number" 
                                                            name="installments[{{$loop->index}}][amount]" 
                                                            class="form-control inst-amount" 
                                                            value="{{ $inst->amount }}" 
                                                            step="0.01" min="0" required>
                                                    </div>
                                                    <div class="col-md-5">
                                                        <input type="date" 
                                                            name="installments[{{$loop->index}}][date]" 
                                                            class="form-control inst-date" 
                                                            value="{{ $inst->installment_date }}" 
                                                            min="{{ date('Y-m-d') }}" required>
                                                    </div>
                                                    <div class="col-md-2">
                                                        <button type="button" class="btn btn-danger w-100 btn-remove" data-delete-installment-url="{{ route('estimate.installments.delete') }}">
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
                                                           <span>Remove</span> 
                                                        </button>
                                                    </div>
                                                </div>
                                            @endforeach
                                        </div>

                                        <hr>
                                        <div class="d-flex justify-content-between align-items-center mb-2">
                                            <h6 class="mb-0">Installment Schedule</h6>
                                            <div id="installmentError" class="text-danger mt-2" style="display:none;">
                                                Please add product before adding installment.
                                            </div>
                                            <button type="button" class="btn btn-sm btn-success" id="addRowBtn">+ Add Installment</button>
                                        </div>

                                        <div class="d-flex justify-content-between">
                                            <strong>Remaining Total:</strong>
                                            <span id="remainingTotal">$1,000.00</span>
                                            <input type="hidden" name="remaining_total" id="remaining_total" value="{{ $data->total_amount }}">
                                        </div>
                                    </div>
                                        <button type="submit" id="savePaymentScheduleBtn" class="btn btn-warning btn-sm no-print">
                                            <span class="btn-schedule-text">Save Payment Schedule</span>
                                            <span class="btn-schedule-loading" style="display:none;">
                                                <span class="schedule-spinner"></span> Saving…
                                            </span>
                                        </button>
                                    </form>

                                    <div id="paymentScheduleMessage" class="mt-2 text-success" style="display:none;"></div>

                                </div>
                            </div>

                            <div class="form-row mt-4">
                                <div class="col-12">
                                    <div id="installmentValidationError"
                                        class="text-danger mt-2"
                                        style="display:none;">
                                        Please schedule a payment first.
                                    </div>
                                </div>
                            </div>

                          <div class="row">
                                <div class="col-md-12">
                                    
                                    <div class="card shadow-sm border-0">
                                        <div class="card-body">

                                            <h5 class="section-title">
                                                Client Confirmation Status
                                            </h5>

                                            <div class="form-group-custom">
                                                <label for="clientConfirmation">
                                                    Select Confirmation Status
                                                </label>

                                                <select id="clientConfirmation" name="confirmed_with_client" class="form-control custom-select" required>
                                                    <option value="" disabled selected>
                                                        -- Choose Status --
                                                    </option>
                                                    <option value="0">
                                                        ✔ Approved (No confirmation needed)
                                                    </option>
                                                    <option value="1">
                                                        ❌ Not yet confirmed with client
                                                    </option>
                                                </select>
                                            </div>

                                        </div>
                                    </div>

                                </div>
                            </div>
                              <button id="sendToClientBtn" type="button"
                                                class="btn btn-success"
                                                data-url="{{ route('contract.send.to.client') }}"
                                                data-csrf="{{ csrf_token() }}"
                                                data-contractModifyId="{{ $data->id }}"
                                                data-slug="{{ $data->slug }}"
                                                data-confirmed_with_client=""
                                                disabled>
                                            Update
                                            </button>
                       </div>
                    </div>
                   
                </div>

              
            </div>
        </div>

        {{-- Add Product Modals --}}
        <div class="modal fade" id="productModal" tabindex="-1">
            <div class="modal-dialog modal-lg">
                <div class="modal-content">

                    <div class="modal-header">
                        <div class="modal-header-text">
                            <i class="fas fa-cube me-2"></i>
                            <h5 class="modal-title" id="taxModalLabel">
                                Select Products
                            </h5>
                        </div>
                        <button type="button" class="close" data-dismiss="modal"
                            aria-label="Close">&times;</button> 
                    </div>

                    <div class="modal-body forref-height">
                        <div id="modalAlert" class="alert d-none" role="alert"></div>
                        <table class="table table-hover">
                            <thead>
                                <tr>
                                    <th>Select</th>
                                    <th>Product Name</th>
                                    <th>Product Qty</th>
                                    <th>Product Price</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($products as $product)
                                    <tr>
                                        <td>
                                            <input type="checkbox"
                                                class="product-checkbox"
                                                data-id="{{ $product->id }}"
                                                data-name="{{ $product->name }}"
                                                data-price="{{ $product->price }}">
                                        </td>
                                        <td>{{ $product->name }}</td>
                                        <td>
                                            <input type="number"
                                                class="form-control form-control-sm product-qty"
                                                data-id="{{ $product->id }}"
                                                min="1"
                                                value="1">
                                        </td>
                                        <td>${{ number_format($product->price, 2) }}</td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>

                    <div class="modal-footer">
                        <div id="modalAlert" class="alert d-none w-100 mb-2" role="alert"></div>
                        <button class="btn btn-secondary btn-sm" data-dismiss="modal">Cancel</button>
                        <button id="addProductsModifyBtn" class="btn btn-primary btn-sm"
                            data-url="{{ route('contract.modify.add-product') }}"
                            data-contractmodifiedid="{{ $data->id }}" 
                            data-csrf="{{ csrf_token() }}">
                        <i class="fas fa-plus me-1"></i> Add Selected
                        </button> 
                    </div>

                </div>
            </div>
        </div>

        {{-- Edit Product Modals --}}

        <div class="modal fade" id="editProductModifyModal" tabindex="-1" role="dialog" aria-hidden="true">
            <div class="modal-dialog modal-lg" role="document">
                <form id="editProductModifyForm">
                    @csrf
                    <input type="hidden" name="item_id">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title">Edit Product</h5>
                            <button type="button" class="close" data-dismiss="modal">&times;</button>
                        </div>
                        <div class="modal-body">
                            <div id="modalAlert" class="alert d-none" role="alert"></div>
                            <div class="form-group">
                                <label>Product Name</label>
                                <input type="text" name="name" class="form-control" readonly>
                            </div>
                            <div class="form-group">
                                <label>Quantity</label>
                                <input type="number" name="quantity" class="form-control">
                            </div>
                            <!-- <div class="form-group">
                                <label>Unit</label>
                                <input type="text" name="unit" class="form-control">
                            </div> -->
                            <div class="form-group">
                                <label>Price</label>
                                <input type="number" name="price" class="form-control" step="0.01">
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
                            <button type="submit" class="btn btn-primary" id="saveChangesBtn">Save Changes</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>

        {{-- Add Tax Modals --}}
        <div class="modal fade" id="taxModifyModal" tabindex="-1" role="dialog" aria-labelledby="taxModifyModalLabel"
                    aria-hidden="true">
                    <div class="modal-dialog modal-lg" role="document">
                        <div class="modal-content">
                            <form id="taxForm" onsubmit="event.preventDefault(); addTax();">
                                <div class="modal-header">
                                    <div class="modal-header-text">
                                        <i class="fas fa-percentage me-2"></i>
                                        <h5 class="modal-title" id="taxModalLabel">
                                            Add Tax
                                        </h5>
                                    </div>
                                    <button type="button" class="close" data-dismiss="modal"
                                        aria-label="Close">&times;</button>
                                </div>
                                <div class="modal-body">
                                    <div id="modalAlert" class="alert d-none" role="alert"></div>
                                    <div class="form-group">
                                        <label for="taxName">Tax Name</label>
                                        <input type="text" id="taxName" class="form-control" placeholder="e.g. VAT"
                                            required>
                                    </div>
                                    <div class="form-group">
                                        <label for="taxPercent">Tax Percent (%)</label>
                                        <input type="number" id="taxPercent" class="form-control" placeholder="e.g. 10"
                                            min="0" step="0.01" required>
                                    </div>
                                    <table class="table product-table" id="taxTable">
                                    <thead>
                                        <tr>
                                            <th>Select Product</th>
                                            <th>Product Name</th>
                                            <th>Quantity</th>
                                            <th>Product Price</th>
                                            <th>Total</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <!-- PRODUCTS HERE -->
                                    </tbody>
                                </table>

                                </div>
                                <div class="modal-footer">
                                    <button id="addTaxModifyBtn" class="btn btn-primary btn-sm"
                                            data-url="{{ route('contract.modify.add-tax') }}"
                                            data-contractmodifiedid="{{ $data->id }}"
                                            data-csrf="{{ csrf_token() }}">
                                        <i class="fas fa-plus me-1"></i> Add Tax
                                    </button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>

        {{-- Edit Tax Modals --}}
        <div class="modal fade" id="editTaxModifyModal" tabindex="-1" role="dialog" aria-labelledby="taxModifyModalLabel"
                    aria-hidden="true">
                    <div class="modal-dialog modal-lg" role="document">
                        <div class="modal-content">
                            <form id="taxForm" onsubmit="event.preventDefault(); updateTax();">
                                <div class="modal-header">
                                    <div class="modal-header-text">
                                        <i class="fas fa-percentage me-2"></i>
                                        <h5 class="modal-title" id="taxModalLabel">
                                            Add Tax
                                        </h5>
                                    </div>
                                    <button type="button" class="close" data-dismiss="modal"
                                        aria-label="Close">&times;</button>
                                </div>
                                <div class="modal-body">
                                    <div id="modalAlert" class="alert d-none" role="alert"></div>
                                    <div class="form-group">
                                        <label for="taxName">Tax Name</label>
                                        <input type="text" id="editmodifytaxName" class="form-control" placeholder="e.g. VAT"
                                            required>
                                    </div>
                                    <div class="form-group">
                                        <label for="taxPercent">Tax Percent (%)</label>
                                        <input type="number" id="editmodifytaxPercent" class="form-control" placeholder="e.g. 10"
                                            min="0" step="0.01" required>
                                    </div>
                                    <table class="table product-table" id="editTaxTable">
                                    <thead>
                                        <tr>
                                            <th>Select Product</th>
                                            <th>Product Name</th>
                                            <th>Quantity</th>
                                            <th>Product Price</th>
                                            <th>Total</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <!-- PRODUCTS HERE -->
                                    </tbody>
                                </table>

                                </div>
                                <div class="modal-footer">
                                    <button id="updateTaxBtn" class="btn btn-primary btn-sm"
                                            data-url="{{ route('contract.modify.tax.update') }}"
                                            data-contractmodifiedid="{{ $data->id }}"
                                            data-csrf="{{ csrf_token() }}">
                                        <i class="fas fa-plus me-1"></i> Update Tax
                                    </button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
                {{-- Add Disscount Modals --}}
                <div class="modal fade" id="discountModifyModal" tabindex="-1" role="dialog"
                    aria-labelledby="discountModalLabel" aria-hidden="true">
                    <div class="modal-dialog" role="document">
                        <div class="modal-content">
                            <form id="discountForm" onsubmit="event.preventDefault(); addProductDiscountModify();">
                                <div class="modal-header">
                                    <h5 class="modal-title" id="discountModalLabel">
                                        <i class="fas fa-tag me-2"></i>Add Discount
                                    </h5>
                                    <button type="button" class="close" data-dismiss="modal"
                                        aria-label="Close">&times;</button>
                                </div>
                                <div class="modal-body">
                                     <div class="form-group">
                                        <label for="discountType">Discount Type</label>
                                        <select id="discountType" class="form-control">
                                            <option value="percent">Percent</option>
                                            <option value="fixed">Fixed</option>
                                        </select>
                                    </div>
                                    <div class="form-group">
                                        <label for="discountName">Discount Name</label>
                                        <input type="text" id="discountName" class="form-control"
                                            placeholder="e.g. Summer Sale">
                                    </div>
                                    <div class="form-group">
                                        <label for="discountValue">Discount Amount %</label>
                                        <div class="input-group">
                                            <input type="number" id="discountAmount" class="form-control"
                                                placeholder="e.g. 10">
                                        </div>
                                    </div>
                                </div>
                                <div class="modal-footer">
                                    <button type="button" class="btn btn-secondary btn-sm"
                                        data-dismiss="modal">Cancel</button>
                                    <button id="addDiscountmodify" class="btn btn-primary btn-sm"
                                            data-url="{{ route('contract.modify.product.discount.add') }}"
                                            data-contractmodifiedid="{{ $data->id }}"
                                            data-csrf="{{ csrf_token() }}">
                                        <i class="fas fa-plus me-1"></i> Apply Discount
                                    </button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
                {{-- Edit Disscount Modals --}}
                <div class="modal fade" id="editmodifydiscountModal" tabindex="-1" role="dialog"
                    aria-labelledby="discountModalLabel" aria-hidden="true">
                    <div class="modal-dialog" role="document">
                        <div class="modal-content">
                            <form id="discountForm" onsubmit="event.preventDefault(); addProductDiscount();">
                                <div class="modal-header">
                                    <h5 class="modal-title" id="discountModalLabel">
                                        <i class="fas fa-tag me-2"></i>Edit Discount
                                    </h5>
                                    <button type="button" class="close" data-dismiss="modal"
                                        aria-label="Close">&times;</button>
                                </div>
                                <div class="modal-body">
                                     <div class="form-group">
                                        <label for="discountName">Discount Type</label>
                                        <select id="editdiscountType" class="form-control">
                                            <option value="percent">Percentage</option>
                                            <option value="fixed">Fixed Amount</option> 
                                        </select>
                                    </div>
                                    <div class="form-group">
                                        <label for="discountName">Discount Name</label>
                                        <input type="text" id="editdiscountName" class="form-control"
                                            placeholder="e.g. Summer Sale">
                                    </div>
                                    <div class="form-group">
                                        <label for="discountValue">Discount Amount %</label>
                                        <div class="input-group">
                                            <input type="number" id="editdiscountAmount" class="form-control"
                                                placeholder="e.g. 10">
                                        </div>
                                    </div>
                                </div>
                                <div class="modal-footer">
                                    <button type="button" class="btn btn-secondary btn-sm"
                                        data-dismiss="modal">Cancel</button>
                                    <button type="button"
                                        id="updateDiscount"
                                        class="btn btn-primary btn-sm"
                                        data-url="{{ route('contract.modify.product.discount.update') }}"
                                        data-contractmodifiedid="{{ $data->id }}"
                                        data-csrf="{{ csrf_token() }}">
                                        <i class="fas fa-save me-1"></i> Update Discount
                                    </button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
    </section>



<link href="https://cdn.jsdelivr.net/npm/summernote@0.8.20/dist/summernote-bs4.min.css" rel="stylesheet">
@push('stylesheets')
<style>
.schedule-spinner { display: inline-block; width: 14px; height: 14px; border: 2px solid rgba(255,255,255,.3); border-top-color: #fff; border-radius: 50%; vertical-align: middle; margin-right: 6px; animation: schedule-spin 0.7s linear infinite; }
@keyframes schedule-spin { to { transform: rotate(360deg); } }
</style>
@endpush
@push('scripts')
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/js/bootstrap.bundle.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/summernote@0.8.20/dist/summernote-bs4.min.js"></script>
<script src="{{ asset('admin/assets/js/taxProductModify.js') }}"></script>
<script src="{{ asset('admin/assets/js/productDiscountModify.js') }}"></script>
<script>
// Add products dynamically
$('#addProductsModifyBtn').on('click', function (e) {
    e.preventDefault();
    let products = [];

    $('.product-checkbox:checked').each(function () {
        let productId = $(this).data('id');
        let qty = $('.product-qty[data-id="' + productId + '"]').val();
        let price = $(this).data('price');
        let name = $(this).data('name');

        products.push({
            product_id: productId,
            qty: qty,
            price: price,
            name: name,
        });
    });

    if (products.length === 0) {
        showModalMessage($('#productModal'), 'Please select at least one product', 'danger');
        return;
    }
 
    let btn = $(this);

    btn.text('Saving...').prop('disabled', true);

    let url = $(this).data('url');       
    let csrfToken = $(this).data('csrf'); 
    let contractModifiedId = $(this).data('contractmodifiedid');

    $.ajax({
        url: url,
        type: "POST",
        data: {
            _token: csrfToken,
            contract_modified_id: contractModifiedId,
            products: products
        },
        success: function (res) {
            btn.text('Add Products').prop('disabled', false);

            if(res.status){
                // Close modal
                $('#productModal').modal('hide');
                // Update totals
                updateTotals();
                window.location.reload();
            } else {
                showModalMessage($('#productModal'), res.message || 'Unable to add products', 'danger');
            }
        },
        error: function(err){
            btn.text('Add Products').prop('disabled', false);
            console.error(err.responseText);
            showModalMessage($('#productModal'), 'Something went wrong', 'danger');
        }
    });
});


// Edit item
$(document).on('click', '.edit-item-modify', function() {
    let btn = $(this);
    let modal = $('#editProductModifyModal');

    modal.find('input[name="item_id"]').val(btn.data('id'));
    modal.find('input[name="name"]').val(btn.data('name'));
    modal.find('input[name="quantity"]').val(btn.data('quantity'));
    modal.find('input[name="unit"]').val(btn.data('unit'));
    modal.find('input[name="price"]').val(btn.data('price'));

    modal.data('url', btn.data('url'));
    modal.data('csrf', btn.data('csrf'));
    modal.data('estimateid', btn.data('estimateid'));

    modal.modal('show');
});


// Submit edited item
$('#editProductModifyForm').on('submit', function(e){
    e.preventDefault();
    let btn = $('#saveChangesBtn');
    btn.prop('disabled', true).text('Saving...');
    let modal = $('#editProductModifyModal');
    let csrfToken = modal.data('csrf');
    let contract_modified_id = {{ $data->id }};
    let url = '{{ route("contract.modify.update-product") }}';
    // alert(contract_modified_id);
    let formData = {
        _token: modal.data('csrf'),
        item_id: modal.find('input[name="item_id"]').val(),
        quantity: modal.find('input[name="quantity"]').val(),
        unit: modal.find('input[name="unit"]').val(),
        price: modal.find('input[name="price"]').val(),
        contract_modified_id: contract_modified_id
    };

    $.ajax({
        url: url,
        type: 'POST',
        data: formData,
        success: function(res){
            showModalMessage(modal, res.message, 'success');
            btn.prop('disabled', false).text('Save Changes');
            setTimeout(() => modal.modal('hide'), 1000);
            updateTotals();
            window.location.reload();
        },
        error: function(err){
            btn.prop('disabled', false).text('Save Changes');
            showModalMessage(modal, 'Something went wrong', 'danger');
        }
    });
});


$(document).on('click', '.remove-modify-item', function(){
    if(!confirm('Are you sure?')) return;

    let btn = $(this);
    let url = btn.data('url');
    let csrf = btn.data('csrf');
    let itemId = btn.data('id');
    let contractModifiedId = {{ $data->id }};
    
    let row = $(this).closest('tr');
    let deleteBtn = row.find('.cust-btn-delete');

    deleteBtn.prop('disabled', true);
    deleteBtn.html('<i class="fa fa-spinner fa-spin"></i> Deleting...');

    $.ajax({
        url: url,
        type: 'POST',
        data: { _token: csrf, item_id: itemId, contract_modified_id: contractModifiedId },
        success: function(res){
            if(res.status){
                $('#productTable tbody tr[data-id="'+itemId+'"]').remove();
                updateTotals();
                window.location.reload();
                // showModalMessage($('#productModal'), res.message, 'success');
            } else {
                // showModalMessage($('#productModal'), 'Unable to delete item', 'danger');
            }
        },
        error: function(err){
            // showModalMessage($('#productModal'), 'Something went wrong', 'danger');
        }
    });
});



let isSubmitting = false;

// bind safely (works even with dynamic DOM / modal)
$(document).off('submit', '#paymentScheduleFormEdit')
.on('submit', '#paymentScheduleFormEdit', function(e) {
    e.preventDefault();

    console.log('Submit triggered');

    // prevent multiple API calls
    if (isSubmitting) return;
    isSubmitting = true;

    var form = $(this);
    var btn = $('#savePaymentScheduleBtn');
    var msgEl = $('#paymentScheduleMessage');

    // UI state
    btn.prop('disabled', true);
    btn.find('.btn-schedule-text').hide();
    btn.find('.btn-schedule-loading').show();
    msgEl.hide().removeClass('text-success text-danger');

    $.ajax({
        url: form.attr('action'),
        type: 'POST',
        data: form.serialize(),
        dataType: 'json',

        success: function(res) {
            if (res.status === true) {
                Toastify({
                    text: res.message || 'Payment schedule saved successfully!',
                    duration: 3000,
                    gravity: "top",
                    position: "right",
                    className: "toast-success"
                }).showToast();

                msgEl.text(res.message || 'Payment schedule saved successfully!')
                     .addClass('text-success')
                     .show();
            } else {
                Toastify({
                    text: res.message || 'Something went wrong.',
                    duration: 3000,
                    gravity: "top",
                    position: "right",
                    className: "toast-error"
                }).showToast();

                msgEl.text(res.message || 'Something went wrong.')
                     .addClass('text-danger')
                     .show();
            }
        },

        error: function(xhr) {
            var res = (xhr.responseJSON || {});
            var message = res.message || xhr.responseText || 'Request failed.';

            Toastify({
                text: message,
                duration: 3000,
                gravity: "top",
                position: "right",
                className: "toast-error"
            }).showToast();

            msgEl.text(message)
                 .addClass('text-danger')
                 .show();
        },

        complete: function() {
            // always reset
            isSubmitting = false;

            btn.prop('disabled', false);
            btn.find('.btn-schedule-loading').hide();
            btn.find('.btn-schedule-text').show();
        }
    });
});

</script>

<script>
$(document).ready(function() {
    const select = $('#clientConfirmation');
    const button = $('#sendToClientBtn');

    // Update button when dropdown changes
    select.on('change', function() {
        const value = $(this).val();

        if (value !== "") {
            button.prop('disabled', false); // enable button
            button.attr('data-confirmed_with_client', value); // update data attribute
            // Optional: update button text dynamically
            if (value === "0") {
                button.text('Send Approved Status');
            } else if (value === "1") {
                button.text('Request Confirmation');
            }
        } else {
            button.prop('disabled', true);
            button.text('Update');
            button.attr('data-confirmed_with_client', "0"); // reset to default
        }
    });

    // AJAX call when button clicked
    button.on('click', function() {
        const btn = $(this);
        const url = btn.data('url');
        const csrf = btn.data('csrf');
        const contractmodifyid = btn.data('contractmodifyid');
        const confirmedStatus = btn.attr('data-confirmed_with_client');

        if (!confirmedStatus) { 
             Toastify({
                    text: "Please select a confirmation status",
                    duration: 3000,
                    gravity: "top",
                    position: "right",
                    className: "toast-error"
                }).showToast();
                return;
            }

        if (confirmedStatus === "0") {
            btn.text('Sending Approved Status...');
        } else if (confirmedStatus === "1") {
            btn.text('Sending Request Confirmation...');
        }
        btn.prop('disabled', true);

        $.ajax({
            url: url,
            type: 'POST',
            data: {
                _token: csrf,
                contract_modified_id: contractmodifyid,
                confirmed_with_client: confirmedStatus
            },
            success: function(response) {
                if(confirmedStatus === "0") {
                    btn.text('Sent Approved Status');
                } else if (confirmedStatus === "1") {
                    btn.text('Sent Request Confirmation');
                }
                btn.removeClass('btn-success').addClass('btn-primary');
                btn.prop('disabled', false);

                Toastify({
                    text: response.message || 'Contract sent successfully.',
                    duration: 3000,
                    gravity: "top",
                    position: "right",
                    className: "toast-success"
                }).showToast();
            },
            error: function(xhr, status, error) {
                if(confirmedStatus === "0") {
                    btn.text('Send Approved Status');
                } else if (confirmedStatus === "1") {
                    btn.text('Request Confirmation');   
                }
                btn.prop('disabled', false);
                console.error(error);
                Toastify({
                    text: 'Something went wrong. Please try again.',
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
<script>
    
$(document).on('click', '.modify-editable-description', function () {
    let td = $(this);
    // alert(td);
    // prevent multiple inputs
    if (td.find('input').length) return;

    let text = td.find('.desc-text').text().trim();

    td.html(`<input type="text" class="form-control modify-desc-input" value="${text}" />`);

    td.find('input').focus();
});
let isModifySaving = false;

$(document).on('blur', '.modify-desc-input', function () {
    if (!isModifySaving) {
        saveDescription($(this));
    }
});

$(document).on('keypress', '.modify-desc-input', function (e) {
    if (e.which === 13) {
        isModifySaving = true;
        saveDescription($(this));
        $(this).blur(); // trigger blur safely
    }
});


function saveDescription(input) {
    let td = input.closest('td');
    let newValue = input.val();

    let id = td.data('id');
    let url = td.data('url');
    let csrf = td.data('csrf');

    // ✅ Show loader + disable input
    input.prop('disabled', true);

    td.addClass('loading');
    td.append(`<span class="spinner"></span>`);

    $.ajax({
        url: url,
        method: "POST",
        data: {
            _token: csrf,
            id: id,
            description: newValue
        },
        success: function (res) {

            td.removeClass('loading');
            td.html(`<span class="desc-text fade-in">${newValue}</span>`);

            Toastify({
                text: res.message || "Updated",
                duration: 2000,
                gravity: "top",
                position: "right",
                style: { background: "#2ecc71" }
            }).showToast();
        },
        error: function () {

            td.removeClass('loading');
            td.html(`<span class="desc-text fade-in">${newValue}</span>`);

            Toastify({
                text: "Update failed",
                duration: 2000,
                gravity: "top",
                position: "right",
                style: { background: "#e74c3c" }
            }).showToast();
        }
    });
}

    </script>

@endpush
@endsection


