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
                    @php
                        $status = match ($data->status) {
                            'sent' => ['label' => 'Sent', 'color' => 'orange'],
                            'accept_by_client', 'accept_by_company' => ['label' => 'Approved', 'color' => 'green'],
                            'reject' => ['label' => 'Rejected', 'color' => 'red'],
                            default => ['label' => 'Pending', 'color' => 'gray'],
                        };
                    @endphp
                    <span class="estimate-number" style="color: {{ $status['color'] }};">{{ ucfirst($status['label']) }}</span>
                </div>
                <br>
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
                                </tr>
                            </thead>
                            <tbody>
                                @if($data->items && $data->items->count())
                                    @foreach($data->items as $item)
                                        <tr data-id="{{ $item->id }}">
                                            <td>
                                                {{ $item->name }}
                                                @if($item->itemTaxes && $item->itemTaxes->count())
                                                    <small class="text-muted d-block">
                                                        Apply Taxes: 
                                                        @foreach($item->itemTaxes as $tax)
                                                            {{ $tax->name }} (${{ bcdiv($tax->amount, 1, 2) }})@if(!$loop->last), @endif
                                                        @endforeach
                                                    </small>
                                                @endif
                                            </td>
                                            <td>
                                                <span class="desc-text">{{ $item->description }}</span>
                                            </td>
                                            <td>{{ $item->quantity }}</td>
                                            <td>${{ number_format($item->price, 2) }}</td>
                                            <td class="item-total">${{ number_format($item->total_price, 2) }}</td>
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
                                    <th id="subtotal">${{ number_format($data->subtotal ?? 0, 2) }}</th>
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
                                                        ${{ bcdiv($tax->amount, 1, 2) }}
                                                    </small>
                                                </div>
                                            @endforeach
                                        </div>
                                    </th>
                                    <th >${{ number_format($data->taxes->sum('amount'), 2) }}</th>
                                </tr>
                                @endif

                                @if($data->discounts && $data->discounts->count())
                                <tr class="fw-bold discount-row">
                                    @foreach($data->discounts as $discount)
                                        <th colspan="3" class="text-end">
                                            <span class="dist-all">
                                                Discount {{ $discount->name }}
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
                                    @php
                                            $subtotal = $data->items->sum('total_price') ?: (float) ($data->total ?? 0);
                                            $taxes = $data->taxes ?? collect(); // force collection
                                            $taxAmount = $taxes->sum('amount');
                                            $total = $subtotal + $taxAmount;
                                        @endphp
                                    <th >${{ number_format($total, 2) }}</th>
                                </tr>
                            </tfoot>
                        </table>
                    </div>
                </div>
            </div>
                                        
            <div class="row">
                <div class="col-md-12">
                    <h5 class="mb-3" style="color: #1f2937;font-size: 18px;">
                        Payment Schedule
                    </h5>
                    
                    @csrf
                    <input type="hidden" name="total_amount" id="total_amount" value="{{ $data->total_amount }}">
                    
                    @php
                        $installments = $data->installments ?? collect();
                    @endphp

                    <ul id="dynamicInputsContainer" style="list-style: none; padding: 0;">
                        @foreach($installments as $index => $inst)
                            <li class="installment-row" data-id="{{ $inst->id }}" style="margin-bottom: 15px;">
                                <div class="row">
                                    <div class="col-md-5">
                                        <input type="number" 
                                            name="installments[{{$index}}][amount]" 
                                            class="form-control inst-amount" 
                                            value="{{ $inst->amount }}" 
                                            step="0.01" 
                                            min="0" 
                                            readonly>
                                    </div>
                                    <div class="col-md-5">
                                        <input type="date" 
                                            name="installments[{{$index}}][date]" 
                                            class="form-control inst-date" 
                                            value="{{ $inst->installment_date }}" 
                                            readonly>
                                    </div>
                                </div>
                            </li>
                        @endforeach
                    </ul>

                    @if($installments->isEmpty())
                        <div class="alert alert-info">
                            No payment schedule available.
                        </div>
                    @endif
                </div>
            </div>

            <div class="form-row mt-4">
                <div class="col-12">
                    <div id="installmentValidationError" class="text-danger mt-2" style="display:none;">
                        Please schedule a payment first.
                    </div>
                </div>
            </div>
       
        </div>
    </div>
</div>
</section>

<link href="https://cdn.jsdelivr.net/npm/summernote@0.8.20/dist/summernote-bs4.min.css" rel="stylesheet">

@push('stylesheets')
<style>
.schedule-spinner { 
    display: inline-block; 
    width: 14px; 
    height: 14px; 
    border: 2px solid rgba(255,255,255,.3); 
    border-top-color: #fff; 
    border-radius: 50%; 
    vertical-align: middle; 
    margin-right: 6px; 
    animation: schedule-spin 0.7s linear infinite; 
}
@keyframes schedule-spin { 
    to { transform: rotate(360deg); } 
}
</style>
@endpush

@push('scripts')
{{-- All JavaScript functionality has been removed for the detail page --}}
@endpush
@endsection