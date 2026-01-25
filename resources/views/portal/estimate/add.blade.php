{{--

@extends('portal.master')
@section('content')
<section class="main-content">
    <div class="row justify-content-center">
        <div class="col-lg-8">
            @include('portal.flash-message')
            <div class="card">
                <div class="card-header custfor-flex-header">
                    <div class="header-content">
                        <i class="fas fa-file-invoice-dollar"></i>
                        <h3>Create New Estimate</h3>
                    </div>
                    <div class="header-actions">
                        <a href="{{ route('estimate.index') }}" class="btn btn-outline">
                            <i class="fas fa-arrow-left"></i> Back to List
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
                                <i class="fas fa-info-circle"></i>
                                <h5>Estimate Details</h5>
                                <span class="section-badge">Required Fields</span>
                            </div>

                            <div class="row">
                                @if(empty($client_id))
                                
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label class="form-label">
                                            <i class="fas fa-user"></i>
                                            Client
                                            <span class="required">*</span>
                                        </label>
                                        <select name="client_id" class="form-control select2" required>
                                            <option value="">-- Select Client --</option>
                                            
                                            @foreach ($clients as $client)
                                            <option value="{{ $client->client_id }}" {{ old('client_id')==$client->
                                                client_id ? 'selected' : '' }}>
                                                {{ $client->first_name }} {{ $client->last_name }} ({{ $client->client_id }})
                                            </option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                                @endif
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label class="form-label">
                                            <i class="fas fa-calendar-alt"></i>
                                            Estimate Date
                                            <span class="required">*</span>
                                        </label>
                                        <input required type="date" name="estimate_date" class="form-control"
                                            value="{{ old('estimate_date') }}"
                                            min="{{ date('Y-m-d', strtotime('+1 day')) }}">
                                    </div>
                                </div>

                                @if(empty($contract_slug))
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label class="form-label">
                                            <i class="fas fa-calendar-alt"></i>
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
                                <i class="fas fa-plus-circle"></i> Create Estimate
                            </button>
                            <button type="reset" class="btn btn-secondary">
                                <i class="fas fa-redo"></i> Reset Form
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
    /* Professional Green Theme - #A0C242 */
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

    /* Simple animations */
    .form-section {
        transition: transform 0.2s ease;
    }

    .form-section:hover {
        transform: translateY(-1px);
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
</style>

<script>
    document.addEventListener('DOMContentLoaded', function () {
        // Initialize Select2 if available
        if ($('.select2').length) {
            $('.select2').select2({
                placeholder: '-- Select Client --',
                allowClear: true,
                width: '100%'
            });
        }

        // Form validation and enhancements
        const form = document.querySelector('.estimate-form');
        if (form) {
            form.addEventListener('submit', function (e) {
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
    });
</script>
@endsection --}}



@extends('portal.master')
@section('content')
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
        </style>

        <script>
            document.addEventListener('DOMContentLoaded', function() {
                // Initialize Select2 if available
                if ($('.select2').length) {
                    $('.select2').select2({
                        placeholder: '-- Select Client --',
                        allowClear: true,
                        width: '100%'
                    });
                }

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
            });
        </script>
@endsection







