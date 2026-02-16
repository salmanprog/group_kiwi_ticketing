@extends('portal.master')
@section('content')
    @push('stylesheets')
        <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
    @endpush
    <section class="main-content">
        <div class="row justify-content-center">
            <div class="col-lg-8">
                @include('portal.flash-message')
                <div class="card">
                    <div class="card-header custfor-flex-header">
                        <div class="header-content">
                            <h3>Create New Estimate</h3>
                        </div>
                        <div class="header-actions">
                            <a href="{{ route('estimate.index') }}" class="btn btn-outline">
                                Back to List
                            </a>
                        </div>
                    </div>
                    <div class="card-body">
                        <form method="post" action="{{ route('estimate.store') }}" class="estimate-form">
                            {{ csrf_field() }}
                            <input type="hidden" name="contract_slug" value="{{ $contract_slug }}">
                            @if(!empty($client_id))
                                <input type="hidden" name="client_id" value="{{ $client_id }}">
                            @endif
                            <div class="form-section">
                                <div class="section-header">
                                    <h5>Estimate Details</h5>
                                    <span class="section-badge">Required Fields</span>
                                </div>

                                <div class="row">
                                    @if(empty($client_id))
                                    
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label class="form-label">
                                                    Client
                                                    <span class="required">*</span>
                                                </label>
                                                <select name="client_id" class="form-control select2" required>
                                                        <option value="">-- Select Client --</option>
                                                        @foreach ($clients as $client)
                                                            <option value="{{ $client->client_id }}"
                                                                {{ old('client_id') == $client->client_id ? 'selected' : '' }}>
                                                                {{ $client->first_name }} {{ $client->last_name }} ({{ $client->organization_name }})
                                                            </option>
                                                        @endforeach
                                                    </select>
                                                </div>
                                            </div>
                                    @endif
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label class="form-label">
                                                    Estimate Date
                                                    <span class="required">*</span>
                                                </label>
                                                <input required type="date" name="estimate_date" class="form-control"
                                                    value="{{ old('estimate_date') }}"  
                                                        min="{{ date('Y-m-d', strtotime('+1 day')) }}"
    >
                                            </div>
                                        </div>

                                        @if(empty($contract_slug))
                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <label class="form-label">
                                                        Event Date
                                                        <span class="required">*</span>
                                                    </label>
                                                    <input required type="date" name="event_date" class="form-control"
                                                        value="{{ old('event_date') }}" 
                                                            min="{{ date('Y-m-d', strtotime('+1 day')) }}">
                                                </div>
                                            </div>
                                        @endif


                                    </div>
                                </div>

                                <div class="form-actions">
                                    <button type="submit" class="btn btn-primary">
                                        Create Estimate
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

            .select2-container--default .select2-selection--single .select2-selection__arrow {
                height: 100%;
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

            /* Date input styling */
            input[type="date"] {
                position: relative;
            }

            input[type="date"]::-webkit-calendar-picker-indicator {
                color: #A0C242;
                opacity: 0.7;
                cursor: pointer;
                transition: opacity 0.3s ease;
            }

            input[type="date"]::-webkit-calendar-picker-indicator:hover {
                opacity: 1;
            }

              /* Select2 Custom CSS - Theme Match */
            .select2-container--default .select2-selection--single {
                border: 1px solid #dce4e0 !important;
                border-radius: 8px !important;
                height: 39px !important;
                background: #ffffff !important;
                box-shadow: 0 2px 4px rgba(0,0,0,0.02) !important;
            }

            .select2-container--default .select2-selection--single:hover {
                border-color: #A0C242 !important;
            }

            .select2-container--default.select2-container--open .select2-selection--single {
                border-color: #A0C242 !important;
                box-shadow: 0 0 0 3px rgba(160, 194, 66, 0.1) !important;
            }

            .select2-container--default .select2-selection--single .select2-selection__rendered {
                color: #2c3e50 !important;
                font-size: 0.95rem !important;
                padding: 5px 15px !important;
                line-height: 16px !important;
                font-weight: 500 !important;
            }

            .select2-container--default .select2-selection--single .select2-selection__placeholder {
                color: #9ca3af !important;
                font-weight: normal !important;
            }

            .select2-container--default .select2-selection--single .select2-selection__arrow {
                height: 39px !important;
                right: 12px !important;
            }

            .select2-container--default .select2-selection--single .select2-selection__arrow b {
                border-color: #A0C242 transparent transparent transparent !important;
                border-width: 6px 5px 0 5px !important;
                margin-left: -5px !important;
                margin-top: -3px !important;
            }

            .select2-container--default.select2-container--open .select2-selection--single .select2-selection__arrow b {
                border-color: transparent transparent #A0C242 transparent !important;
                border-width: 0 5px 6px 5px !important;
            }

            .select2-container--default .select2-selection--single .select2-selection__clear {
                color: #9ca3af !important;
                font-size: 20px !important;
                font-weight: 300 !important;
                margin-right: 25px !important;
            }

            .select2-container--default .select2-selection--single .select2-selection__clear:hover {
                color: #e74c3c !important;
            }

            /* Dropdown Styling */
            .select2-dropdown {
                border: 1px solid #e5e7eb !important;
                border-radius: 8px !important;
                box-shadow: 0 4px 15px rgba(0, 0, 0, 0.08) !important;
                margin-top: 4px !important;
                overflow: hidden !important;
            }

            .select2-results__option {
                padding: 12px 15px !important;
                font-size: 0.95rem !important;
                color: #2c3e50 !important;
                transition: all 0.2s ease !important;
            }

            .select2-container--default .select2-results__option[aria-selected=true] {
                background: #f0f7e7 !important;
                color: #2c3e50 !important;
                font-weight: 500 !important;
            }

            .select2-container--default .select2-results__option--highlighted[aria-selected] {
                background: linear-gradient(135deg, #A0C242 0%, #8AB933 100%) !important;
                color: white !important;
            }

            .select2-container--default .select2-results__option--highlighted[aria-selected] span {
                color: white !important;
            }

            /* Search Box */
            .select2-search--dropdown {
                padding: 12px !important;
                background: #f9fafb !important;
                border-bottom: 1px solid #e5e7eb !important;
            }

            .select2-search--dropdown .select2-search__field {
                border: 1px solid #dce4e0 !important;
                border-radius: 6px !important;
                padding: 8px 12px !important;
                font-size: 0.95rem !important;
                background: white !important;
            }

            .select2-search--dropdown .select2-search__field:focus {
                border-color: #A0C242 !important;
                box-shadow: 0 0 0 3px rgba(160, 194, 66, 0.1) !important;
                outline: none !important;
            }

            /* Loading State */
            .select2-container--default .select2-selection--single .select2-selection__arrow:after {
                display: none !important;
            }

            /* Disabled State */
            .select2-container--default.select2-container--disabled .select2-selection--single {
                background: #f9fafb !important;
                border-color: #e5e7eb !important;
                opacity: 0.7 !important;
            }

            .select2-container--default.select2-container--disabled .select2-selection--single .select2-selection__rendered {
                color: #9ca3af !important;
            }

            /* Responsive */
            @media (max-width: 768px) {
                .select2-container--default .select2-selection--single {
                    height: 42px !important;
                }
                
                .select2-container--default .select2-selection--single .select2-selection__rendered {
                    padding: 9px 12px !important;
                    font-size: 0.9rem !important;
                }
                
                .select2-container--default .select2-selection--single .select2-selection__arrow {
                    height: 42px !important;
                }
                
                .select2-dropdown {
                    font-size: 0.9rem !important;
                }
                
                .select2-results__option {
                    padding: 10px 12px !important;
                }
            }

    
        </style>
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
    
    @push('scripts')
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
    @endpush

        <script>
            document.addEventListener('DOMContentLoaded', function() {
                // Initialize Select2 if available
                // Form validation and enhancements
                const form = document.querySelector('.estimate-form');
                if (form) {
                    form.addEventListener('submit', function(e) {
                        const clientId = document.querySelector('select[name="client_id"]').value;
                        const estimateDate = document.querySelector('input[name="estimate_date"]').value;

                        if (!clientId) {
                            e.preventDefault();
                            showNotification('Please select a client', 'error');
                            return false;
                        }

                        if (!estimateDate) {
                            e.preventDefault();
                            showNotification('Please select an estimate date', 'error');
                            return false;
                        }
                    });
                }

                // Notification function
                function showNotification(message, type = 'info') {
                    // You can integrate with a notification library here
                    alert(message); // Simple alert for now
                }

                // Date input today as default if empty
                const dateInput = document.querySelector('input[name="estimate_date"]');
                if (dateInput && !dateInput.value) {
                    const today = new Date().toISOString().split('T')[0];
                    dateInput.value = today;
                }

                  (function($) {
                        $('#client_id').select2({
                            placeholder: "-- Select Client --",
                            allowClear: true,
                            width: '100%' // makes it full width
                        });
                    })(jQuery);
                
            });
            
        </script>
@endsection







