@extends('portal.master')
@section('content')
    <section class="main-content">
        <div class="row justify-content-center">
            <div class="col-lg-8">
                @include('portal.flash-message')
                <div class="card">
                    <div class="card-header custfor-flex-header">
                        <div class="header-content">
                            <h3>Edit Product</h3>
                        </div>
                    </div>
                    <div class="card-body">
                        <form method="POST" action="{{ route('product.update', ['product' => $record->slug]) }}">
                            @method('PUT')
                            @csrf

                            <div class="form-section">
                                <div class="section-header">
                                    <h5>Product Details</h5>
                                    <span class="section-badge">Required Fields</span>
                                </div>

                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="name" class="form-label">Name <span class="required">*</span></label>
                                            <input type="text" id="name" required name="name" class="form-control" value="{{ old('name', $record->name) }}">
                                        </div>
                                    </div>

                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="category_id" class="form-label">Category <span class="required">*</span></label>
                                            <select id="category_id" required name="company_product_category_id" class="form-control select2">
                                                <option value="">Select Category</option>
                                                @foreach ($categories as $category)
                                                    <option value="{{ $category->id }}" {{ (old('company_product_category_id', $record->company_product_category_id) == $category->id) ? 'selected' : '' }}>
                                                        {{ $category->name }}
                                                    </option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>

                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="rate" class="form-label">Base Price <span class="required">*</span></label>
                                            <input type="number" step="0.01" id="rate" required name="price" class="form-control" value="{{ old('price', $record->price) }}">
                                        </div>
                                    </div>

                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="units" class="form-label">Units <span class="required">*</span></label>
                                            <input type="text" id="units" required name="unit" class="form-control" value="{{ old('unit', $record->unit) }}">
                                        </div>
                                    </div>

                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label class="form-label">Tax (%)</label>
                                            <input type="number" step="0.01" name="tax" id="tax_input" class="form-control" value="{{ old('tax', $record->tax ?? 0) }}">
                                        </div>
                                    </div>

                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label class="form-label">Gratuity (%)</label>
                                            <input type="number" step="0.01" name="gratuity" id="gratuity_input" class="form-control" value="{{ old('gratuity', $record->gratuity ?? 0) }}">
                                        </div>
                                    </div>

                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label class="form-label">Total Product Price</label>
                                            <input type="number" step="0.01" name="total_price" id="total_price" 
                                                value="{{ old('total_price', $record->total_price ?? 0) }}"
                                                class="form-control" style="background-color: #f1f3f5; font-weight: bold;" readonly>
                                        </div>
                                    </div>

                                    <div class="col-md-12">
                                        <div class="form-check form-switch mb-3">
                                            <input class="form-check-input" type="checkbox" id="toggleEstimateNotes" {{ !is_null($record->description) ? 'checked' : '' }}>
                                            <label class="form-check-label" for="toggleEstimateNotes">Add Estimate Notes</label>
                                        </div>
                                    </div>

                                    <div class="col-md-12" id="estimateNotesContainer" style="{{ !is_null($record->description) ? '' : 'display:none;' }}">
                                        <div class="form-group">
                                            <label for="notes" class="form-label">Estimate Notes</label>
                                            <textarea id="notes" name="description" class="form-control" style="height: 100px;">{{ old('description', $record->description) }}</textarea>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="form-actions">
                                <button type="submit" class="btn btn-primary">Update Product</button>
                                <a href="{{ route('product.index') }}" class="btn btn-secondary">Cancel</a>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>

        @include('portal.footer')

        {{-- Use push if your master has a @stack('scripts'), otherwise keep it here --}}
        <script>
            // Ensure jQuery is loaded before running
            window.onload = function() {
                if (window.jQuery) {
                    $(document).ready(function() {
                        
                        function calculateTotal() {
                            // Use Number() to avoid string concatenation
                            const price = parseFloat($('#rate').val()) || 0;
                            const tax = parseFloat($('#tax_input').val()) || 0;
                            const gratuity = parseFloat($('#gratuity_input').val()) || 0;

                            const taxAmount = price * (tax / 100);
                            const gratuityAmount = price * (gratuity / 100);
                            const total = price + taxAmount + gratuityAmount;

                            $('#total_price').val(total.toFixed(2));
                        }

                        // Use specific IDs for listeners
                        $('#rate, #tax_input, #gratuity_input').on('input change keyup', function() {
                            calculateTotal();
                        });

                        // Initial calculation
                        calculateTotal();

                        // Toggle Notes
                        $('#toggleEstimateNotes').on('change', function() {
                            if (this.checked) {
                                $('#estimateNotesContainer').show();
                            } else {
                                $('#estimateNotesContainer').hide();
                                $('#notes').val(''); 
                            }
                        });

                        // Init Select2 if it exists
                        if ($.fn.select2) {
                            $('.select2').select2({ width: '100%' });
                        }
                    });
                } else {
                    console.error("jQuery is not loaded!");
                }
            };
        </script>

        <style>
            .main-content { background: #f8faf9; min-height: 100vh; padding: 30px; padding-top: 90px; }
            .card { border-radius: 12px; box-shadow: 0 4px 20px rgba(0,0,0,0.05); background: #fff; }
            .card-header { background: #A0C242; color: white; padding: 20px; }
            .form-section { padding: 20px; }
            .form-label { font-weight: bold; }
            .btn-primary { background: #A0C242; border: none; }
        </style>
    </section>
@endsection