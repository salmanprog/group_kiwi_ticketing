@extends('portal.master')
@section('content')
    <section class="main-content">
        <div class="row justify-content-center">
            <div class="col-lg-8">
                @include('portal.flash-message')

                <div class="card">
                    <div class="card-header custfor-flex-header">
                        <div class="header-content">
                            <h3>Add Product</h3>
                        </div>
                    </div>

                    <div class="card-body">
                        <form method="POST" action="{{ route('product.store') }}">
                            @csrf

                            <div class="form-section">
                                <div class="section-header">
                                    <h5>Product Details</h5>
                                    <span class="section-badge">Required Fields</span>
                                </div>

                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label class="form-label">Name <span class="required">*</span></label>
                                            <input type="text" required name="name" class="form-control" placeholder="Enter product name" value="{{ old('name') }}">
                                        </div>
                                    </div>

                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label class="form-label">Category <span class="required">*</span></label>
                                            <select required name="company_product_category_id" class="form-control select2">
                                                <option value="">Select Category</option>
                                                @foreach ($categories as $category)
                                                    <option value="{{ $category->id }}" {{ old('company_product_category_id') == $category->id ? 'selected' : '' }}>
                                                        {{ $category->name }}
                                                    </option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>

                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label class="form-label">Base Price <span class="required">*</span></label>
                                            <input type="number" step="0.01" required name="price" id="base_price" class="form-control" placeholder="0.00" value="{{ old('price') }}">
                                        </div>
                                    </div>

                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label class="form-label">Units <span class="required">*</span></label>
                                            <input type="text" required name="unit" class="form-control" placeholder="e.g. pcs, kg" value="{{ old('unit') }}">
                                        </div>
                                    </div>

                                    <!-- <div class="col-md-4">
                                        <div class="form-group">
                                            <label class="form-label">Tax (%)</label>
                                            <input type="hidden" step="0.01" name="tax" id="tax_input" class="form-control" placeholder="0.00" value="{{ old('tax', 0) }}">
                                        </div>
                                    </div> -->

                                    <!-- <div class="col-md-4">
                                        <div class="form-group">
                                            <label class="form-label">Gratuity (%)</label>
                                            <input type="hidden"  step="0.01" name="gratuity" id="gratuity_input" class="form-control" placeholder="0.00" value="{{ old('gratuity', 0) }}">
                                        </div>
                                    </div> -->

                                    <!-- <div class="col-md-4">
                                        <div class="form-group">
                                            <label class="form-label">Total Product Price</label>
                                            <input type="number" step="0.01" name="total_price" id="total_price" class="form-control" style="background-color: #f9fafb; font-weight: bold;" readonly value="{{ old('total_price', 0) }}">
                                        </div>
                                    </div> -->

                                    <div class="col-md-12">
                                        <div class="form-group">
                                            <div class="form-check form-switch">
                                                <input class="form-check-input" type="checkbox" id="toggleEstimateNotes" {{ old('description') ? 'checked' : '' }}>
                                                <label class="form-check-label" for="toggleEstimateNotes">Add Estimate Notes</label>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="col-md-12" id="estimateNotesContainer" style="{{ old('description') ? '' : 'display:none;' }}">
                                        <div class="form-group">
                                            <label class="form-label">Estimate Notes</label>
                                            <textarea name="description" id="notes" class="form-control" style="height: 120px;" placeholder="Enter estimate notes here...">{{ old('description') }}</textarea>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="form-actions">
                                <button type="submit" class="btn btn-primary">Submit</button>
                                <button type="reset" class="btn btn-secondary">Reset Form</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <style>
        .main-content { background: #f8faf9; min-height: 100vh; padding: 30px; padding-top: 90px; }
        .custfor-flex-header { display: flex; justify-content: space-between; align-items: center; }
        .card { border: none; border-radius: 12px; box-shadow: 0 4px 20px rgba(160, 194, 66, 0.1); margin-bottom: 30px; background: #fff; }
        .card-header { background: #ffffff; border-bottom: 1px solid #e5e7eb; padding: 20px 30px; }
        .header-content h3 { margin: 0; font-weight: 600; font-size: 18px; }
        .form-section { background: #ffffff; border: 1px solid #eaeaea; border-radius: 8px; padding: 25px; margin-bottom: 20px; }
        .section-header { display: flex; align-items: center; gap: 12px; margin-bottom: 25px; padding-bottom: 15px; border-bottom: 1px solid #e0e6e3; }
        .section-header h5 { margin: 0; color: #2c3e50; font-weight: 600; }
        .form-label { font-weight: 600; color: #2c3e50; margin-bottom: 8px; display: block; }
        .required { color: #e74c3c; }
        .form-control { border: 1px solid #dce4e0; border-radius: 6px; padding: 12px 15px; width: 100%; }
        .form-control:focus { border-color: #A0C242 !important; box-shadow: 0 0 0 3px rgba(160, 194, 66, 0.1); outline: none; }
        .form-check-input:checked { background-color: #A0C242; border-color: #A0C242; }
        .form-actions { display: flex; gap: 15px; justify-content: flex-end; padding-top: 20px; border-top: 1px solid #eaeaea; }
        .btn-primary { background: linear-gradient(135deg, #A0C242 0%, #8AB933 100%); color: white; border:none; padding: 10px 25px; border-radius: 6px; }
    </style>

    <script>
        function initPageLogic() {
            // Initialize Select2
            if (typeof $.fn.select2 !== 'undefined') {
                $('.select2').select2({
                    placeholder: "Select a Category",
                    allowClear: true,
                    width: '100%'
                });
            } else {
                console.error("Select2 library not loaded!");
            }

            // Price Calculation
            const basePrice = document.getElementById('base_price');
            const taxInput = document.getElementById('tax_input');
            const gratuityInput = document.getElementById('gratuity_input');
            const totalPrice = document.getElementById('total_price');

            function calculateTotal() {
                let price = parseFloat(basePrice.value) || 0;
                let tax = parseFloat(taxInput.value) || 0;
                let gratuity = parseFloat(gratuityInput.value) || 0;
                let total = price + (price * (tax / 100)) + (price * (gratuity / 100));
                totalPrice.value = total.toFixed(2);
            }

            [basePrice, taxInput, gratuityInput].forEach(el => {
                if(el) el.addEventListener('input', calculateTotal);
            });

            // Toggle Estimate Notes
            const toggle = document.getElementById('toggleEstimateNotes');
            const container = document.getElementById('estimateNotesContainer');
            if(toggle && container) {
                toggle.addEventListener('change', function() {
                    container.style.display = this.checked ? '' : 'none';
                });
            }
        }

        // Run when DOM is ready
        if (window.jQuery) {
            $(document).ready(initPageLogic);
        } else {
            window.addEventListener('DOMContentLoaded', initPageLogic);
        }
    </script>
@endsection