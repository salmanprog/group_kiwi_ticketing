{{-- 
@extends('portal.master')
@section('content')
<section class="main-content">
    <div class="row justify-content-center">
        <div class="col-lg-8">
            @include('portal.flash-message')

            <div class="card shadow-lg border-0 rounded-3">
                <!-- Header -->
                <div class="card-header bg-gradient-primary text-white d-flex justify-content-between align-items-center">
                    <h5 class="mb-0"><i class="fa fa-plus-circle me-2"></i> Add Product</h5>
                </div>

                <div class="card-body p-4">
                    <form method="POST" action="{{ route('product.store') }}">
                        @csrf

                        <!-- Name & Category -->
                        <div class="row mb-4">
                            <div class="col-md-6">
                                <label for="name" class="form-label fw-bold">Name</label>
                                <input 
                                    type="text" 
                                    id="name" 
                                    required 
                                    name="name" 
                                    class="form-control form-control-lg"
                                    placeholder="Enter product name"
                                    value="{{ old('name') }}"
                                >
                            </div>

                            <div class="col-md-6">
                                <label for="category_id" class="form-label fw-bold">Category</label>
                                <select 
                                    id="category_id" 
                                    required 
                                    name="company_product_category_id" 
                                    class="form-control select2 form-control-lg"
                                >
                                    <option value="">Select Category</option>
                                    @foreach ($categories as $category)
                                        <option value="{{ $category->id }}" {{ old('category_id') == $category->id ? 'selected' : '' }}>
                                            {{ $category->name }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                        </div>

                        <!-- Price & Units -->
                        <div class="row mb-4">
                            <div class="col-md-4">
                                <label for="rate" class="form-label fw-bold">Price</label>
                                <input 

                                    type="number" 
                                    step="0.01" 
                                    id="rate" 
                                    required 
                                    name="price" 
                                    class="form-control form-control-lg"
                                    placeholder="Enter price"
                                    value="{{ old('price') }}"
                                >
                            </div>

                            <div class="col-md-4">
                                <label for="units" class="form-label fw-bold">Units</label>
                                <input 
                                    type="text" 
                                    id="units" 
                                    required 
                                    name="unit" 
                                    class="form-control form-control-lg"
                                    placeholder="Enter units (e.g. pcs, kg)"
                                    value="{{ old('unit') }}"
                                >
                            </div>
                        </div>

                        <!-- Toggle for Estimate Notes -->
                        <div class="form-check form-switch mb-4">
                            <input 
                                class="form-check-input" 
                                type="checkbox" 
                                id="toggleEstimateNotes"
                                {{ old('notes') ? 'checked' : '' }}
                            >
                            <label class="form-check-label fw-bold" for="toggleEstimateNotes">
                                Add Estimate Notes
                            </label>
                        </div>

                        <!-- Estimate Notes -->
                        <div class="mb-4" id="estimateNotesContainer" style="{{ old('notes') ? '' : 'display:none;' }}">
                            <label for="notes" class="form-label fw-bold">Estimate Notes</label>
                            <textarea 
                                id="notes" 
                                name="description" 
                                class="form-control form-control-lg"
                                style="height: 120px;"
                                placeholder="Enter estimate notes here..."
                            >{{ old('notes') }}</textarea>
                        </div>

                       
                        <div class="text-end">
                            <button type="submit" class="btn btn-gradient-primary px-4 py-2">
                                <i class="fa fa-save me-2"></i> Submit
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    @include('portal.footer')

    
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>

    <script>
        $(document).ready(function() {
            $('.select2').select2({
                placeholder: "Select a Category",
                allowClear: true
            });
        });

        document.addEventListener('DOMContentLoaded', function () {
            const toggle = document.getElementById('toggleEstimateNotes');
            const notesContainer = document.getElementById('estimateNotesContainer');
            const notesTextarea = document.getElementById('notes');

            toggle.addEventListener('change', function () {
                if (this.checked) {
                    notesContainer.style.display = '';
                    notesTextarea.required = true;
                } else {
                    notesContainer.style.display = 'none';
                    notesTextarea.required = false;
                    notesTextarea.value = '';
                }
            });
        });
    </script>
</section>

    <style>
        /* --- Same UI Theme --- */
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
        .form-check-input{
            margin-left: 0 !important;
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
            background: linear-gradient(135deg, #A0C242 0%, #8AB933 100%);
            border-bottom: none;
            padding: 25px 30px;
            color: white;
        }

        .header-content {
            display: flex;
            align-items: center;
            gap: 15px;
        }

        .header-content i {
            font-size: 1.8rem;
            opacity: 0.9;
        }

        .header-content h3 {
            margin: 0;
            font-weight: 600;
            font-size: 1.5rem;
        }

        .header-actions .btn-outline {
            background: rgba(255, 255, 255, 0.2);
            border: 1px solid rgba(255, 255, 255, 0.3);
            border-radius: 6px;
            padding: 8px 16px;
            color: white;
            text-decoration: none;
            font-weight: 500;
            transition: all 0.3s ease;
            display: inline-flex;
            align-items: center;
            gap: 6px;
        }

        .btn-outline:hover {
            background: rgba(255, 255, 255, 0.3);
            color: white;
        }

        .card-body {
            padding: 30px;
        }

        .form-section {
            background: #f8faf9;
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

        .section-header i {
            width: 35px;
            height: 35px;
            background: #A0C242;
            border-radius: 6px;
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            font-size: 1rem;
        }

        .section-header h5 {
            margin: 0;
            color: #2c3e50;
            font-weight: 600;
            font-size: 1.1rem;
            flex: 1;
        }

        .section-badge {
            background: #eafaf1;
            color: #A0C242;
            padding: 4px 10px;
            border-radius: 4px;
            font-size: 0.8rem;
            font-weight: 600;
            border: 1px solid #d5f5e3;
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

        .form-label i {
            color: #A0C242;
            width: 16px;
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

        /* Actions */
        .form-actions {
            display: flex;
            gap: 15px;
            justify-content: flex-end;
            padding-top: 20px;
            margin-top: 20px;
            border-top: 1px solid #eaeaea;
        }

        .btn {
            padding: 12px 25px;
            border-radius: 6px;
            font-weight: 600;
            font-size: 0.95rem;
            display: inline-flex;
            align-items: center;
            gap: 8px;
            transition: all 0.3s ease;
            border: none;
            cursor: pointer;
        }

        .btn-secondary {
            background: #95a5a6;
            color: white;
        }

        .btn-secondary:hover {
            background: #7f8c8d;
            transform: translateY(-1px);
        }

        .btn-primary {
            background: linear-gradient(135deg, #A0C242 0%, #8AB933 100%);
            color: white;
            box-shadow: 0 2px 8px rgba(160, 194, 66, 0.3);
        }

        .btn-primary:hover {
            transform: translateY(-1px);
            box-shadow: 0 4px 12px rgba(160, 194, 66, 0.4);
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
                justify-content: center;
            }
        }
    </style>
@endsection --}}



@extends('portal.master')
@section('content')
    <section class="main-content">
        <div class="row justify-content-center">
            <div class="col-lg-8">
                @include('portal.flash-message')

                <div class="card">
                    <!-- Header -->
                    <div class="card-header custfor-flex-header">
                        <div class="header-content">
                            <h3>Add Product</h3>
                        </div>
                    </div>

                    <div class="card-body">
                        <form method="POST" action="{{ route('product.store') }}">
                            @csrf

                            <!-- Name & Category -->
                            <div class="form-section">
                                <div class="section-header">
                                    <h5>Product Details</h5>
                                    <span class="section-badge">Required Fields</span>
                                </div>

                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label class="form-label">
                                                Name
                                                <span class="required">*</span>
                                            </label>
                                            <input type="text" required name="name" class="form-control"
                                                placeholder="Enter product name" value="{{ old('name') }}">
                                        </div>
                                    </div>

                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label class="form-label">
                                                Category 
                                                <span class="required">*</span>
                                            </label>
                                            <select required name="company_product_category_id"
                                                class="form-control select2">
                                                <option value="">Select Category</option>
                                                @foreach ($categories as $category)
                                                    <option value="{{ $category->id }}"
                                                        {{ old('category_id') == $category->id ? 'selected' : '' }}>
                                                        {{ $category->name }}
                                                    </option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>

                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label class="form-label">
                                                Price
                                                <span class="required">*</span>
                                            </label>
                                            <input type="number" step="0.01" required name="price"
                                                class="form-control" placeholder="Enter price" value="{{ old('price') }}">
                                        </div>
                                    </div>

                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label class="form-label">
                                                Units
                                                <span class="required">*</span>
                                            </label>
                                            <input type="text" required name="unit" class="form-control"
                                                placeholder="Enter units (e.g. pcs, kg)" value="{{ old('unit') }}">
                                        </div>
                                    </div>

                                    <div class="col-md-12">
                                        <div class="form-group">
                                            <div class="form-check form-switch">
                                                <input class="form-check-input" type="checkbox" id="toggleEstimateNotes"
                                                    {{ old('notes') ? 'checked' : '' }}>
                                                <label class="form-check-label" for="toggleEstimateNotes">
                                                    Add Estimate Notes
                                                </label>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="col-md-12" id="estimateNotesContainer"
                                        style="{{ old('notes') ? '' : 'display:none;' }}">
                                        <div class="form-group">
                                            <label class="form-label">
                                                Estimate Notes
                                            </label>
                                            <textarea name="description" class="form-control" style="height: 120px;" placeholder="Enter estimate notes here...">{{ old('notes') }}</textarea>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Submit Button -->
                            <div class="form-actions">
                                <button type="submit" class="btn btn-primary">
                                    Submit
                                </button>
                                <button type="reset" class="btn btn-secondary">
                                    Reset Form
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>

        @include('portal.footer')

        <!-- Select2 -->
        <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
        <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
        <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>

        <script>
            $(document).ready(function() {
                $('.select2').select2({
                    placeholder: "Select a Category",
                    allowClear: true
                });
            });

            document.addEventListener('DOMContentLoaded', function() {
                const toggle = document.getElementById('toggleEstimateNotes');
                const notesContainer = document.getElementById('estimateNotesContainer');
                const notesTextarea = document.querySelector('textarea[name="description"]');

                toggle.addEventListener('change', function() {
                    if (this.checked) {
                        notesContainer.style.display = '';
                        notesTextarea.required = true;
                    } else {
                        notesContainer.style.display = 'none';
                        notesTextarea.required = false;
                        notesTextarea.value = '';
                    }
                });
            });
        </script>
    </section>

    <style>
        /* --- Same UI as Organization Type Page --- */
        .main-content {
            background: #f8faf9;
            min-height: 100vh;
            padding: 30px;
            padding-top: 90px;
        }

        .btn-outline2 {
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

        .header-content {
            display: flex;
            align-items: center;
            gap: 15px;
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

        /* Form Check Switch */
        .form-check-input {
            width: 3em;
            height: 1.5em;
            margin-left: 0 !important;
            margin-right: 10px;
            background-color: #dce4e0;
            border-color: #dce4e0;
        }

        .form-check-input:checked {
            background-color: #A0C242;
            border-color: #A0C242;
        }

        .form-check-input:focus {
            box-shadow: 0 0 0 3px rgba(160, 194, 66, 0.1);
        }

        .form-check-label {
            font-weight: 500;
            color: #2c3e50;
            cursor: pointer;
            padding-left: 2.25rem !important;
        }

        /* Textarea */
        textarea.form-control {
            min-height: 120px;
            resize: vertical;
        }

        /* Select2 Customization */
        .select2-container--default .select2-selection--single {
            border: 1px solid #dce4e0;
            border-radius: 6px;
            padding: 8px 15px;
            height: auto;
            background: #fff;
        }

        .select2-container--default .select2-selection--single:focus {
            border-color: #A0C242;
            box-shadow: 0 0 0 3px rgba(160, 194, 66, 0.1);
            outline: none;
        }

        .select2-container--default .select2-selection--single .select2-selection__rendered {
            color: #2c3e50;
            font-size: 0.95rem;
            padding: 0;
        }

        /* Actions */
        .form-actions {
            display: flex;
            gap: 15px;
            justify-content: flex-end;
            padding-top: 20px;
            margin-top: 20px;
            border-top: 1px solid #eaeaea;
        }

        .btn {
            padding: 10px 25px;
            border-radius: 6px;
            font-weight: 600;
            font-size: 14px;
            transition: all 0.2s ease;
            border: 1px solid transparent;
            cursor: pointer;
            color: #fff;
        }

        .btn-secondary {
            background: #ffffff;
            border-color: #d1d5db;
            color: #374151;
        }

        .btn-secondary:hover {
            background: #7f8c8d;
            transform: translateY(-1px);
        }

        .btn-primary {
            background: linear-gradient(135deg, #A0C242 0%, #8AB933 100%);
            color: white;
            box-shadow: 0 2px 8px rgba(160, 194, 66, 0.3);
        }

        .btn-primary:hover {
            transform: translateY(-1px);
            box-shadow: 0 4px 12px rgba(160, 194, 66, 0.4);
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
                justify-content: center;
            }
        }
    </style>
@endsection
