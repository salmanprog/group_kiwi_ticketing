@extends('portal.master')
@section('content')
    <section class="main-content">
        <div class="row justify-content-center">
            <div class="col-lg-10">
                @include('portal.flash-message')

                <div class="card">
                    <div class="card-header custfor-flex-header">
                        <div class="header-content">
                            <h3>Add Account Details</h3>
                        </div>
                        <div class="header-actions">
                            <a href="{{ route('organization.index') }}" class="btn btn-outline">
                                Back to List
                            </a>
                        </div>
                    </div>

                    <div class="card-body">
                        <form method="post" action="{{ route('organization.store') }}">
                            {{ csrf_field() }}

                            <!-- Basic Information -->
                            <div class="form-section">
                                <div class="section-header">
                                    <h5>Basic Information</h5>
                                    <span class="section-badge">Required</span>
                                </div>
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label class="form-label">Account Name <span class="required">*</span></label>
                                            <input required type="text" name="name" class="form-control"
                                                value="{{ old('name') }}">
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label class="form-label">Contact <span class="required">*</span></label>
                                            <input required type="text" name="contact" class="form-control"
                                                value="{{ old('contact') }}">
                                        </div>
                                    </div>
                                    <div class="col-md-12">
                                        <div class="form-group">
                                            <label class="form-label">Department</label>
                                            <input type="text" name="department" class="form-control"
                                                value="{{ old('department') }}">
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Address Details -->
                            <div class="form-section">
                                <div class="section-header">
                                    <h5>Address Details</h5>
                                </div>
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label class="form-label">City</label>
                                            <input type="text" name="city" class="form-control"
                                                value="{{ old('city') }}">
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label class="form-label">State</label>
                                            <input type="text" name="state" class="form-control"
                                                value="{{ old('state') }}">
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label class="form-label">Country</label>
                                            <input type="text" name="country" class="form-control"
                                                value="{{ old('country') }}">
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label class="form-label">Zip</label>
                                            <input type="text" name="zip" class="form-control"
                                                value="{{ old('zip') }}">
                                        </div>
                                    </div>
                                    <div class="col-md-12">
                                        <div class="form-group">
                                            <label class="form-label">Address Line 1</label>
                                            <input type="text" name="address_one" class="form-control"
                                                value="{{ old('address_one') }}">
                                        </div>
                                    </div>
                                    <div class="col-md-12">
                                        <div class="form-group">
                                            <label class="form-label">Address Line 2</label>
                                            <input type="text" name="address_two" class="form-control"
                                                value="{{ old('address_two') }}">
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Contact Information -->
                            <div class="form-section">
                                <div class="section-header">
                                    <h5>Contact Information</h5>
                                </div>
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label class="form-label">Email</label>
                                            <input type="email" name="email" class="form-control"
                                                value="{{ old('email') }}">
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label class="form-label">Phone</label>
                                            <input type="text" name="phone" class="form-control"
                                                value="{{ old('phone') }}">
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label class="form-label">Fax</label>
                                            <input type="text" name="fax" class="form-control"
                                                value="{{ old('fax') }}">
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label class="form-label">Rep</label>
                                            <input type="text" name="rep" class="form-control"
                                                value="{{ old('rep') }}">
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Event & Opportunity -->
                            <div class="form-section">
                                <div class="section-header">
                                    <h5>Event & Opportunity</h5>
                                </div>
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label class="form-label">Organization Type</label>
                                            <select name="organization_type_id" class="form-control select2">
                                                <option value="">-- Select Type --</option>
                                                @foreach ($organization_types as $organization_type)
                                                    <option value="{{ $organization_type->id }}">
                                                        {{ $organization_type->name }}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label class="form-label">Organization Size</label>
                                            <input type="number" name="size" class="form-control"
                                                value="{{ old('size') }}">
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label class="form-label">Event History</label>
                                            <select name="event_history_id" class="form-control">
                                                <option value="">-- Select Event History --</option>
                                                @foreach ($organization_history_events as $event_history)
                                                    <option value="{{ $event_history->id }}">{{ $event_history->name }}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label class="form-label">Group Type</label>
                                            <select name="event_type_id" class="form-control">
                                                <option value="">-- Select Group Type --</option>
                                                @foreach ($organization_events as $event)
                                                    <option value="{{ $event->id }}">{{ $event->name }}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label class="form-label">Group Size</label>
                                            <input type="number" name="group_size" class="form-control"
                                                value="{{ old('group_size') }}">
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label class="form-label">First Meeting</label>
                                            <input type="date" name="first_meeting" class="form-control"
                                                value="{{ old('first_meeting') }}">
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label class="form-label">Hot Button</label>
                                            <input type="text" name="hot_button" class="form-control"
                                                value="{{ old('hot_button') }}">
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label class="form-label">Closing Probability (%)</label>
                                            <input type="number" name="closing_probability" class="form-control"
                                                value="{{ old('closing_probability') }}">
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label class="form-label">Event Date</label>
                                            <input type="date" name="event_date" class="form-control"
                                                value="{{ old('event_date') }}">
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label class="form-label">Event Status</label>
                                            <input type="text" name="event_status" class="form-control"
                                                value="{{ old('event_status') }}">
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label class="form-label">Next Objective</label>
                                            <input type="text" name="next_objective" class="form-control"
                                                value="{{ old('next_objective') }}">
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label class="form-label">Follow-Up Date</label>
                                            <input type="date" name="follow_up_date" class="form-control"
                                                value="{{ old('follow_up_date') }}">
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Submit -->
                            <div class="form-actions">
                                <button type="submit" class="btn btn-primary btn-outline">
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
    </section>

    <style>
        /* Clean White Theme */
        .main-content {
            background: #ffffff;
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
            border: 1px solid #e5e7eb;
            border-radius: 8px;
            box-shadow: 0 1px 3px rgba(0, 0, 0, 0.1);
            margin-bottom: 30px;
            background: #ffffff;
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
            color: #1f2937;
        }

        .header-actions {
            display: flex;
            gap: 10px;
        }

        .btn-outline {
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
            border: 1px solid #ffffff !important;
            background-color: #8ab02e !important;
            color: white;
        }

        .card-body {
            padding: 30px;
        }

        .form-section {
            background: #ffffff;
            border: 1px solid #e5e7eb;
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
            border-bottom: 1px solid #e5e7eb;
        }

        .section-header h5 {
            margin: 0;
            color: #1f2937;
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
            font-weight: 500;
            color: #1f2937;
            margin-bottom: 8px;
            font-size: 0.95rem;
            display: block;
        }

        .required {
            color: #dc2626;
            margin-left: 4px;
        }

        .form-control {
            border: 1px solid #d1d5db;
            border-radius: 6px;
            padding: 10px 15px;
            font-size: 0.95rem;
            transition: all 0.2s ease;
            background: #ffffff;
            color: #1f2937;
        }

        .form-control:focus {
            border-color: #9fc23f !important;
            box-shadow: 0 0 0 3px rgba(37, 99, 235, 0.1);
            outline: none;
        }

        .form-hint {
            color: #6b7280;
            font-size: 0.85rem;
            margin-top: 5px;
            display: block;
        }

        /* Select2 Customization */
        .select2-container--default .select2-selection--single {
            border: 1px solid #d1d5db;
            border-radius: 6px;
            padding: 8px 15px;
            height: auto;
            background: #ffffff;
        }

        .select2-container--default .select2-selection--single:focus {
            border-color: #2563eb;
            box-shadow: 0 0 0 3px rgba(37, 99, 235, 0.1);
            outline: none;
        }

        .select2-container--default .select2-selection--single .select2-selection__rendered {
            color: #1f2937;
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
            border-top: 1px solid #e5e7eb;
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
            background: #f9fafb;
            border-color: #9ca3af;
        }

        .btn-primary {
            background: #2563eb;
            border-color: #2563eb;
            color: #ffffff;
        }

        .btn-primary:hover {
            background: #1d4ed8;
            border-color: #1d4ed8;
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
                text-align: center;
            }
        }

        /* Input focus animations */
        .form-control:focus {
            transform: translateY(-1px);
        }

        /* Date input styling */
        input[type="date"] {
            color: #1f2937;
        }

        input[type="date"]::-webkit-calendar-picker-indicator {
            filter: invert(0.4);
            cursor: pointer;
        }

        /* Placeholder color */
        .form-control::placeholder {
            color: #9ca3af;
        }
    </style>
@endsection
