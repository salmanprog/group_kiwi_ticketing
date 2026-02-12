@extends('portal.master')

@section('content')
@push('stylesheets')
        <link href="{{ asset('admin/assets/scss/Estimate-style.css') }}" rel="stylesheet" type="text/css">
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
                    <i class="fas fa-file-invoice-dollar me-2"></i>Estimate
                </div>
                @if ($record->organization_deleted_at)
                    <div class="deleted-alert">
                        <i class="fas fa-info-circle me-2"></i>
                        <strong>Deleted:</strong> Organization has been deleted.
                    </div>
                @endif

                <div class="estimate-meta">
                    <span class="estimate-number">#{{ ucfirst($record->slug) }}</span>
                    @switch($record->status)
                        @case('draft')
                            <span class="status draft">
                                <i class="fas fa-edit me-1"></i>Draft
                            </span>
                        @break

                        @case('sent')
                            <span class="status sent">
                                <i class="fas fa-paper-plane me-1"></i>Sent
                            </span>
                        @break

                        @case('approved')
                            <span class="status approved">
                                <i class="fas fa-check-circle me-1"></i>Approved
                            </span>
                        @break

                        @case('rejected')
                            <span class="status rejected">
                                <i class="fas fa-times-circle me-1"></i>Rejected
                            </span>
                        @break

                        @case('revised')
                            <span class="status revised">
                                <i class="fas fa-redo me-1"></i>Revised
                            </span>
                        @break
                    @endswitch
                </div>
            </div>
            {{-- Address Section --}}
            <div class="address-section">
                <div class="address-box">
                    <h4><i class="fas fa-building me-2"></i>From</h4>
                    <p>
                        <strong>{{ $record->company->name }}</strong><br>
                        <strong>Mobile No:</strong> {{ $record->company->mobile_no }}
                        <br>
                        <strong>Email:</strong> {{ $record->company->email }}
                    </p>
                </div>
                <div class="address-box">
                    <h4><i class="fas fa-user me-2"></i>Invoice To</h4>
                    <p>
                        <strong>{{ $record->organization_name }}</strong><br>
                        {{ $record->organization_address_one }}
                        <br>
                        <strong>Email:</strong> {{ $record->organization_email }}
                        <br>
                        <strong>Phone:</strong> {{ $record->organization_phone }}
                    </p>
                </div>
            </div>

                    {{-- Estimate Start --}}
                    <div class="form-section">
                        
                                                    <div class="form-row">
                                @if ($record->contract_id == null)
                                    <div class="form-group">
                                        <label for="client_id">Client</label>
                                        <select name="client_id" id="client_id" class="form-control select2">
                                            <option value="">-- Select Client --</option>
                                            @foreach ($clients as $client)
                                                <option value="{{ $client->client_id }}"
                                                    {{ $record->client_id == $client->client_id ? 'selected' : '' }}>
                                                    {{ $client->first_name }} {{ $client->last_name }}
                                                </option>
                                            @endforeach
                                        </select>
                                        <div class="print-value">
                                            <strong>Client:</strong>
                                            {{ optional($clients->firstWhere('id', $record->client_id))->first_name ?? '' }}
                                            {{ optional($clients->firstWhere('id', $record->client_id))->last_name ?? '' }}
                                        </div>
                                    </div>
                                @else
                                    <input type="hidden" name="client_id" value="{{ $record->client_id }}">
                                @endif

                                <div class="form-group">
                                    <label for="estimate_date">Estimate Date</label>
                                    <input required type="date" name="estimate_date" class="form-control"
                                        value="{{ $record->issue_date }}">
                                    <div class="print-value">
                                        <strong>Estimate Date:</strong>
                                        {{ \Carbon\Carbon::parse($record->issue_date)->format('F j, Y') }}
                                    </div>
                                </div>
                                @if ($record->contract_id == null)
                                    <div class="form-group">
                                        <label for="event_date">Event Date</label>
                                        <input required type="date" name="event_date" class="form-control"
                                            value="{{ $record->event_date }}">
                                        <div class="print-value">
                                            <strong>Event Date:</strong>
                                            {{ \Carbon\Carbon::parse($record->event_date)->format('F j, Y') }}
                                        </div>
                                    </div>
                                @endif
                                <div class="form-group">
                                    <label for="expiration_date">Expiry Date</label>
                                    <input required type="date" name="expiration_date" class="form-control"
                                        value="{{ $record->valid_until }}" min="{{ date('Y-m-d', strtotime('+1 day')) }}">
                                    <div class="print-value">
                                        <strong>Expiry Date:</strong>
                                        {{ \Carbon\Carbon::parse($record->valid_until)->format('F j, Y') }}
                                    </div>
                                </div>
                            </div>

                                                        {{-- Product Table --}}
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
                                                    <th>Quantity</th>
                                                    <th>Product Price</th>
                                                    <th>Total</th>
                                                    <th class="no-print">Action</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                @if($estimate && $estimate->items->count())
                                                    @foreach($estimate->items as $item)
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
                                                                                    {{ $tax->name }}@if(!$loop->last), @endif
                                                                                @endforeach
                                                                    </small>
                                                                @endif
                                                            </td>
                                                            <td>{{ $item->quantity }} {{ $item->unit ?? '' }}</td>
                                                            <td>${{ number_format($item->price, 2) }}</td>
                                                            <td class="item-total">${{ number_format($item->total_price, 2) }}</td>
                                                            <td class="no-print">
                                                                <span class="f-line for-d-g">
                                                                    <button class="btn btn-sm btn-primary edit-item foest-edit"
                                                                            data-url="{{ route('estimate.products.update') }}"
                                                                            data-estimateid="{{ $estimate->id }}"
                                                                            data-csrf="{{ csrf_token() }}"
                                                                            data-id="{{ $item->id }}"
                                                                            data-name="{{ $item->name }}"
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
                                                                    <button class="btn btn-sm btn-danger remove-item cust-btn-delete"
                                                                            data-url="{{ route('estimate.products.delete') }}"
                                                                            data-id="{{ $item->id }}"
                                                                            data-estimateid="{{ $estimate->id }}"
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

                                                @if($estimate && $estimate->taxes->count())
                                                <tr>
                                                    <th colspan="4" class="text-end">Tax:
                                                        <div class="d-flex flex-wrap gap-2 justify-content-end">
                                                            @foreach($estimate->taxes as $tax)
                                                                <div class="border rounded px-2 py-1 d-flex align-items-center gap-1"
                                                                    data-tax-id="{{ $tax->id }}">

                                                                    <small class="fw-semibold">
                                                                        {{ $tax->name }} ({{ $tax->percent }}%)
                                                                    </small>

                                                                    <button class="btn btn-sm btn-link text-primary edit-tax"
                                                                            data-tax-id="{{ $tax->id }}"
                                                                            data-url="{{ route('estimate.tax.get') }}"
                                                                            data-update-url="dsfsdf"
                                                                            data-csrf="{{ csrf_token() }}"
                                                                            data-estimateid="{{ $estimate->id }}"
                                                                            data-toggle="modal"
                                                                            data-target="#editTaxModal">
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
                                                                            data-url="{{ route('estimate.tax.delete', $tax->id) }}"
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
                                                    <th id="tax_amount">${{ number_format($estimate->taxes->sum('amount'), 2) }}</th>
                                                    {{-- <th></th> --}}
                                                </tr>
                                                @endif
                                               @if($estimate && $estimate->discounts->count())
                                                <tr class="fw-bold discount-row">
                                                    @foreach($estimate->discounts as $discount)
                                                        <th colspan="3" class="text-end">
                                                            <span class="dist-all">
                                                                Discount {{ $discount->name }}
                                                                <button class="btn btn-sm btn-link text-danger p-0 delete-discount"
                                                                        data-url="{{ route('estimate.product.discount.delete', $discount->id) }}"
                                                                        data-csrf="{{ csrf_token() }}">
                                                                    <i class="fas fa-trash"></i>
                                                                </button>
                                                            </span>
                                                        </th>
                                                        <th class="discount_percent">
                                                            {{ $discount->value }} %
                                                        </th>
                                                        <th></th>
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
                                        <!-- <button type="button" class="btn btn-success btn-sm no-print" onclick="addRow()">
                                            <i class="fas fa-plus me-1"></i>Add Field
                                        </button> -->
                                        <button type="button" class="btn btn-primary btn-sm no-print"
                                            data-toggle="modal" data-target="#productModal">
                                            <i class="fas fa-cube me-1"></i>Add Product
                                        </button>
                                        <button class="btn btn-info btn-sm no-print"
                                                data-toggle="modal"
                                                data-target="#taxModal"
                                                data-url="{{ route('estimate.products.get') }}"
                                                data-csrf="{{ csrf_token() }}"
                                                data-estimateid="{{ $estimate->id }}">
                                            <i class="fas fa-percentage me-1"></i>Add Tax
                                        </button>
                                        @if($estimate && $estimate->discounts->count())
                                            @foreach($estimate->discounts as $discounts)
                                                <button type="button" class="btn btn-warning btn-sm no-print"
                                                    data-toggle="modal" data-target="#editdiscountModal"
                                                    data-url="{{ route('estimate.product.discount.get') }}"
                                                    data-csrf="{{ csrf_token() }}"
                                                    data-estimateid="{{ $estimate->id }}"
                                                    data-discountid="{{ $discounts->id }}"
                                                >
                                                    <i class="fas fa-tag me-1"></i>Edit Discount
                                                </button>
                                            @endforeach
                                        @else
                                        <button type="button" class="btn btn-warning btn-sm no-print"
                                            data-toggle="modal" data-target="#discountModal">
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
                                    <form id="paymentScheduleForm" method="POST" action="{{ route('estimate.installments.save', $estimate->id) }}">
                                        <div class="sec-css">
                                        @csrf
                                        <input type="hidden" name="total_amount" id="total_amount" value="{{ $estimate->total_amount }}">
                                        @php
                                        $installments = $estimate->installments ?? collect();
                                        @endphp

                                        <div id="dynamicInputsContainer">
                                            @foreach($installments as $inst)
                                                <div class="row mb-2 installment-row">
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
                                                        <button type="button" class="btn btn-danger w-100 btn-remove">
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
                                            <input type="hidden" name="remaining_total" id="remaining_total" value="{{ $estimate->total_amount }}">
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
                                    <h5 class="mb-3" style="color: #1f2937;font-size: 18px;">
                                        Note
                                    </h5>
                                    <div class="forref">
                                        <textarea id="estimate_note" name="note" class="form-control editor" rows="4">{{ $estimate->note }}</textarea>
                                        <div class="print-value">
                                            <strong>Note:</strong>
                                            {!! $estimate->note !!}
                                        </div>
                                        <button type="button"
                                                class="btn btn-warning btn-sm no-print save-note"
                                                data-url="{{ route('estimate.note.save') }}"
                                                data-csrf="{{ csrf_token() }}"
                                                data-estimateid="{{ $estimate->id }}">
                                            <span class="btn-note-text"><i class="fas fa-save me-1"></i> Save Note</span>
                                            <span class="btn-note-loading" style="display:none;"><span class="schedule-spinner"></span> Saving…</span>
                                        </button>

                                        <div id="formMessage" class="mt-2" style="display:none;"></div>
                                    </div>
                                </div>
                            </div>

                            <div class="form-row mt-4">
                                <div class="col-12">
                                    <h5 class="mb-3" style="color: #1f2937;font-size: 18px;">
                                        Terms & Conditions
                                    </h5>
                                    <div class="forref">
                                        <textarea id="terms_and_condition" name="terms_and_condition" class="form-control editor" rows="4" placeholder="Enter terms and conditions">
                                            {!! $estimate->terms ?? ($default_terms_and_condition->content ?? '') !!}</textarea>
                                        
                                        <button type="button"
                                                class="btn btn-warning btn-sm no-print save-note"
                                                data-url="{{ route('estimate.note.save') }}"
                                                data-csrf="{{ csrf_token() }}"
                                                data-estimateid="{{ $estimate->id }}">
                                            <span class="btn-note-text"><i class="fas fa-save me-1"></i> Save Term and Condition</span>
                                            <span class="btn-note-loading" style="display:none;"><span class="schedule-spinner"></span> Saving…</span>
                                        </button>
                                        <div class="print-value mt-3">
                                            <strong>Terms & Conditions (Preview):</strong>
                                            <div class="preview-content">
                                                @if (!empty($estimate->terms_and_condition))
                                                    {!! $estimate->terms_and_condition !!}
                                                @else
                                                    {!! $default_terms_and_condition->content ?? '' !!}
                                                @endif
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                                                        {{-- Action Buttons --}}
                            <div class="form-row mt-4">
                                <div class="col-12">
                                    <div id="installmentValidationError"
                                        class="text-danger mt-2"
                                        style="display:none;">
                                        Please schedule a payment first.
                                    </div>

                                    <div class="action-buttons">
                                        @if ($estimate->status != 'approved')
                                            <!-- <button type="submit" class="btn btn-primary">
                                                <i class="fas fa-save me-1"></i>Save
                                            </button> -->
                                        @endif

                                        @if ($estimate->status == 'approved')
                                            <input type="hidden" name="adjust" value="1">
                                            <button type="submit" class="btn btn-primary">
                                                <i class="fas fa-edit me-1"></i>Adjust
                                            </button>
                                        @endif

                                        @if ($estimate->status != 'approved')
                                            <input type="hidden" name="mail_send" value="1">
                                            <button type="button"
                                                class="btn btn-success send-to-client"
                                                data-url="{{ route('estimates.send.to.client', ['estimate' => $estimate->slug]) }}"
                                                data-csrf="{{ csrf_token() }}"
                                                data-estimateid="{{ $estimate->id }}"
                                                data-slug="{{ $estimate->slug }}"
                                                data-slug="{{ $estimate->status }}">
                                            Sent to Client
                                            </button>
                                            <!-- <button type="button" class="btn btn-success" onclick="submitSentForm()">
                                                    <i class="fas fa-paper-plane me-1"></i>Send
                                                </button> -->
                                        @endif
                                        <!-- <button type="button" class="btn btn-outline-secondary no-print cust-bd"
                                            onclick="window.print()">
                                            <i class="fas fa-print me-1"></i>Print
                                        </button> -->
                                    </div>
                                </div>
                            </div>

                            {{-- Hidden form for Send action --}}
                        @if ($record->status != 'approved')
                            <form id="sentForm" method="POST" action="{{ route('estimate.save') }}"
                                style="display: none;">
                                @csrf
                                <input type="hidden" name="slug" value="{{ $record->slug }}">
                            </form>
                        @endif
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

                    <div class="modal-body">
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
                        <button class="btn btn-secondary btn-sm" data-dismiss="modal">Cancel</button>
                        <button id="addProductsBtn" class="btn btn-primary btn-sm"
                            data-url="{{ route('estimate.products.add') }}"
                            data-estimateid="{{ $estimate->id }}" 
                            data-csrf="{{ csrf_token() }}">
                        <i class="fas fa-plus me-1"></i> Add Selected
                        </button>
                    </div>

                </div>
            </div>
        </div>

        {{-- Edit Product Modals --}}

        <div class="modal fade" id="editProductModal" tabindex="-1" role="dialog" aria-hidden="true">
            <div class="modal-dialog modal-lg" role="document">
                <form id="editProductForm">
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
                            <div class="form-group">
                                <label>Unit</label>
                                <input type="text" name="unit" class="form-control">
                            </div>
                            <div class="form-group">
                                <label>Price</label>
                                <input type="number" name="price" class="form-control" step="0.01">
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
                            <button type="submit" class="btn btn-primary">Save Changes</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>

        {{-- Add Tax Modals --}}
        <div class="modal fade" id="taxModal" tabindex="-1" role="dialog" aria-labelledby="taxModalLabel"
                    aria-hidden="true">
                    <div class="modal-dialog modal-lg" role="document">
                        <div class="modal-content">
                            <form id="taxForm" onsubmit="event.preventDefault(); addOrUpdateTax();">
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
                                    <button id="addTaxBtn" class="btn btn-primary btn-sm"
                                            data-url="{{ route('estimate.tax.add') }}"
                                            data-estimateid="{{ $estimate->id }}"
                                            data-csrf="{{ csrf_token() }}">
                                        <i class="fas fa-plus me-1"></i> Add Tax
                                    </button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>

        {{-- Edit Tax Modals --}}
        <div class="modal fade" id="editTaxModal" tabindex="-1" role="dialog" aria-labelledby="taxModalLabel"
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
                                        <input type="text" id="edittaxName" class="form-control" placeholder="e.g. VAT"
                                            required>
                                    </div>
                                    <div class="form-group">
                                        <label for="taxPercent">Tax Percent (%)</label>
                                        <input type="number" id="edittaxPercent" class="form-control" placeholder="e.g. 10"
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
                                            data-url="{{ route('estimate.tax.update') }}"
                                            data-estimateid="{{ $estimate->id }}"
                                            data-csrf="{{ csrf_token() }}">
                                        <i class="fas fa-plus me-1"></i> Update Tax
                                    </button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
                {{-- Add Disscount Modals --}}
                <div class="modal fade" id="discountModal" tabindex="-1" role="dialog"
                    aria-labelledby="discountModalLabel" aria-hidden="true">
                    <div class="modal-dialog" role="document">
                        <div class="modal-content">
                            <form id="discountForm" onsubmit="event.preventDefault(); addProductDiscount();">
                                <div class="modal-header">
                                    <h5 class="modal-title" id="discountModalLabel">
                                        <i class="fas fa-tag me-2"></i>Add Discount
                                    </h5>
                                    <button type="button" class="close" data-dismiss="modal"
                                        aria-label="Close">&times;</button>
                                </div>
                                <div class="modal-body">
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
                                    <button id="addDiscount" class="btn btn-primary btn-sm"
                                            data-url="{{ route('estimate.product.discount.add') }}"
                                            data-estimateid="{{ $estimate->id }}"
                                            data-csrf="{{ csrf_token() }}">
                                        <i class="fas fa-plus me-1"></i> Apply Discount
                                    </button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
                {{-- Edit Disscount Modals --}}
                <div class="modal fade" id="editdiscountModal" tabindex="-1" role="dialog"
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
                                        data-url="{{ route('estimate.product.discount.update') }}"
                                        data-estimateid="{{ $estimate->id }}"
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
<script>
$(document).ready(function () {

    $('#paymentScheduleForm').on('submit', function(e) {
        e.preventDefault();
        var form = $(this);
        var btn = $('#savePaymentScheduleBtn');
        var msgEl = $('#paymentScheduleMessage');
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
                btn.prop('disabled', false);
                btn.find('.btn-schedule-loading').hide();
                btn.find('.btn-schedule-text').show();
                if (res.status === true) {
                    msgEl.text(res.message || 'Payment schedule saved successfully!').addClass('text-success').show();
                } else {
                    msgEl.text(res.message || 'Something went wrong.').addClass('text-danger').show();
                }
            },
            error: function(xhr) {
                btn.prop('disabled', false);
                btn.find('.btn-schedule-loading').hide();
                btn.find('.btn-schedule-text').show();
                var res = (xhr.responseJSON || {});
                msgEl.text(res.message || (xhr.responseText || 'Request failed.')).addClass('text-danger').show();
            }
        });
    });

    var $saveNoteBtnActive = null;
    $(document).on('click', '.save-note', function() {
        var btn = $(this);
        $saveNoteBtnActive = btn;
        btn.prop('disabled', true);
        btn.find('.btn-note-text').hide();
        btn.find('.btn-note-loading').show();
    });
    $(document).ajaxComplete(function(event, xhr, settings) {
        var url = (settings.url || '').toString();
        if ((url.indexOf('note/save') !== -1 || url.indexOf('estimate.note.save') !== -1) && $saveNoteBtnActive && $saveNoteBtnActive.length) {
            $saveNoteBtnActive.prop('disabled', false);
            $saveNoteBtnActive.find('.btn-note-loading').hide();
            $saveNoteBtnActive.find('.btn-note-text').show();
            $saveNoteBtnActive = null;
        }
        if (url.indexOf('estimates-send-to-client') !== -1 && $sendToClientBtnActive && $sendToClientBtnActive.length) {
            $sendToClientBtnActive.prop('disabled', false);
            $sendToClientBtnActive.find('.btn-send-loading').hide();
            $sendToClientBtnActive.find('.btn-send-text').show();
            $sendToClientBtnActive = null;
        }
    });

    var $sendToClientBtnActive = null;
    $(document).on('click', '.send-to-client', function() {
        var btn = $(this);
        $sendToClientBtnActive = btn;
        btn.prop('disabled', true);
        btn.find('.btn-send-text').hide();
        btn.find('.btn-send-loading').show();
    });
    
    $('#estimate_note').summernote({
        height: 180,
        placeholder: 'Write note here...',
        toolbar: [
            ['style', ['bold', 'italic', 'underline']],
            ['para', ['ul', 'ol']],
            ['insert', ['link']],
            ['view', ['codeview']]
        ]
    });

    $('#terms_and_condition').summernote({
        height: 180,
        placeholder: 'Write note here...',
        toolbar: [
            ['style', ['bold', 'italic', 'underline']],
            ['para', ['ul', 'ol']],
            ['insert', ['link']],
            ['view', ['codeview']]
        ]
    });
});

</script>
 @endpush
@endsection

