@extends('portal.master')
@section('content')
    <section class="main-content">
        <div class="row justify-content-center">
            <div class="col-lg-8">
                @include('portal.flash-message')
                <div class="card">
                    <div class="card-header card-default">
                        Edit Product
                    </div>
                    <div class="card-body">
                        <form method="POST" action="{{ route('product.update', ['product' => $record->slug]) }}">
                            <input type="hidden" name="_method" value="PUT">
                            @csrf

                            <div class="row mb-3">
                                <div class="col-md-6">
                                    <label for="name" class="form-label">Name</label>
                                    <input type="text" id="name" required name="name" class="form-control"
                                        value="{{ $record->name }}">
                                </div>

                                <div class="col-md-6">
                                    <label for="category_id" class="form-label">Category</label>
                                    <select id="category_id" required name="company_product_category_id"
                                        class="form-control select2">
                                        <option value="">Select Category</option>
                                        @foreach ($categories as $category)
                                            <option value="{{ $category->id }}"
                                                {{ $record->company_product_category_id == $category->id ? 'selected' : '' }}>
                                                {{ $category->name }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>

                            <div class="row mb-3">
                                <div class="col-md-4">
                                    <label for="rate" class="form-label">Price</label>
                                    <input                                     type="number" 
                                    step="0.01"  id="rate" required name="price" class="form-control"
                                        value="{{ $record->price }}">
                                </div>

                                <div class="col-md-4">
                                    <label for="units" class="form-label">Units</label>
                                    <input type="text" id="units" required name="unit" class="form-control"
                                        value="{{ $record->unit }}">
                                </div>
                            </div>

                            {{-- Toggle for Estimate Notes --}}
                            <div class="form-check form-switch mb-3">
                                <input class="form-check-input" type="checkbox" id="toggleEstimateNotes" name="notes_toggle"
                                    {{ !is_null($record->description) ? 'checked' : '' }}>
                                <label class="form-check-label" for="toggleEstimateNotes">
                                    Add Estimate Notes
                                </label>
                            </div>

                            {{-- Estimate Notes textarea (hidden/shown) --}}
                            <div class="mb-3" id="estimateNotesContainer"
                                style="{{ !is_null($record->description) ? '' : 'display:none;' }}">
                                <label for="notes" class="form-label">Estimate Notes</label>
                                <textarea id="notes" name="description" class="form-control" style="height: 100px;">{{ $record->description ?? '' }}</textarea>
                            </div>

                            <button type="submit" class="btn btn-primary">Submit</button>

                        </form>
                    </div>
                </div>
            </div>
        </div>
        @include('portal.footer')

        <style>
            /* Same Professional Green Theme */
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

            .header-actions {
                display: flex;
                gap: 10px;
            }

            .btn-outline {
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
                border-color: #A0C242;
                box-shadow: 0 0 0 3px rgba(160, 194, 66, 0.1);
                outline: none;
            }

            /* Form Actions */
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

            .form-check-input {
                margin-left: 0 !important;
            }

            /* Responsive Design */
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
        </script>
        <script>
            document.addEventListener('DOMContentLoaded', function() {
                const toggle = document.getElementById('toggleEstimateNotes');
                const notesContainer = document.getElementById('estimateNotesContainer');
                const notesTextarea = document.getElementById('notes');

                toggle.addEventListener('change', function() {
                    if (this.checked) {
                        notesContainer.style.display = '';
                        notesTextarea.required = true;
                    } else {
                        notesContainer.style.display = 'none';
                        notesTextarea.required = false;
                    }
                });
            });
        </script>
    </section>
@endsection
