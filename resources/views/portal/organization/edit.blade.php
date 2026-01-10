{{-- @extends('portal.master')
@section('content')
 <section class="main-content">
    <div class="row">
        <div class="col-sm-12">
            @include('portal.flash-message')

            <div class="card">
                <div class="card-header card-default">
                    Edit Organization Details
                </div>

                <div class="card-body">
                    <form method="post" action="{{ route('organization.update', ['organization' => $record->slug]) }}" enctype="multipart/form-data" onsubmit="return validateForm(') }}">
                        {{ csrf_field() }}
                        <input type="hidden" name="_method" value="PUT">

                        <!-- SECTION 1: Basic Info -->
                        <div class="card mb-4 border">
                            <div class="card-header bg-light">
                                <strong>Basic Information</strong>
                            </div>
                            <div class="card-body">
                                <div class="form-row">
                                    <div class="form-group col-md-6">
                                        <label>Name <span class="text-danger">*</span></label>
                                        <input required type="text" name="name" class="form-control" value="{{ $record->name }}">
                                    </div>
                                    <div class="form-group col-md-6">
                                        <label>Organization Type</label>
                                        <select name="organization_type_id" class="form-control select2">
                                            <option value="">-- Select Type --</option>
                                            @foreach ($organization_types as $organization_type)
                                                <option value="{{ $organization_type->id }}" {{ $record->organization_type_id == $organization_type->id ? 'selected' : '' }}>{{ $organization_type->name }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <div class="form-group col-md-6">
                                        <label>Event Type</label>
                                        <select name="event_type_id" class="form-control">
                                            <option value="">-- Select Event Type --</option>
                                            @foreach ($organization_events as $event)
                                                <option value="{{ $event->id }}"  {{ $record->id == $event->id ? 'selected' : '' }}>{{ $event->name }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <div class="form-group col-md-6">
                                        <label>Department</label>
                                        <input type="text" name="department" class="form-control" value="{{ $record->department }}">
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- SECTION 2: Address Details -->
                        <div class="card mb-4 border">
                            <div class="card-header bg-light">
                                <strong>Address Details</strong>
                            </div>
                            <div class="card-body">
                                <div class="form-row">
                                    <div class="form-group col-md-6">
                                        <label>Address Line 1</label>
                                        <input type="text" name="address_one" class="form-control" value="{{ $record->address_one }}">
                                    </div>
                                    <div class="form-group col-md-6">
                                        <label>Address Line 2</label>
                                        <input type="text" name="address_two" class="form-control" value="{{ $record->address_two }}">
                                    </div>
                                    <div class="form-group col-md-4">
                                        <label>City</label>
                                        <input type="text" name="city" class="form-control" value="{{ $record->city }}">
                                    </div>
                                    <div class="form-group col-md-4">
                                        <label>State</label>
                                        <input type="text" name="state" class="form-control" value="{{ $record->state }}">
                                    </div>
                                    <div class="form-group col-md-4">
                                        <label>Zip</label>
                                        <input type="text" name="zip" class="form-control" value="{{ $record->zip }}">
                                    </div>
                                    <div class="form-group col-md-6">
                                        <label>Country</label>
                                        <input type="text" name="country" class="form-control" value="{{ $record->country }}">
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- SECTION 3: Contact Info -->
                        <div class="card mb-4 border">
                            <div class="card-header bg-light">
                                <strong>Contact Information</strong>
                            </div>
                            <div class="card-body">
                                <div class="form-row">
                                    <div class="form-group col-md-6">
                                        <label>Email</label>
                                        <input type="email" name="email" class="form-control" value="{{ $record->email }}">
                                    </div>
                                    <div class="form-group col-md-6">
                                        <label>Phone</label>
                                        <input type="text" name="phone" class="form-control" value="{{ $record->phone }}">
                                    </div>
                                    <div class="form-group col-md-6">
                                        <label>Fax</label>
                                        <input type="text" name="fax" class="form-control" value="{{ $record->fax }}">
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- SECTION 4: Event & Opportunity -->
                        <div class="card mb-4 border">
                            <div class="card-header bg-light">
                                <strong>Event & Opportunity</strong>
                            </div>
                            <div class="card-body">
                                <div class="form-row">
                                    <div class="form-group col-md-6">
                                        <label>Size</label>
                                        <input type="number" name="size" class="form-control" value="{{ $record->size }}">
                                    </div>
                                    <div class="form-group col-md-6">
                                        <label>First Meeting</label>
                                        <input type="date" name="first_meeting" class="form-control" value="{{ $record->first_meeting }}">
                                    </div>
                                    <div class="form-group col-md-6">
                                        <label>Closing Probability (%)</label>
                                        <input type="number" name="closing_probability" class="form-control" value="{{ $record->closing_probability }}">
                                    </div>
                                    <div class="form-group col-md-6">
                                        <label>Event Date</label>
                                        <input type="date" name="event_date" class="form-control" value="{{ $record->event_date }}">
                                    </div>
                                    <div class="form-group col-md-6">
                                        <label>Event Status</label>
                                        <input type="text" name="event_status" class="form-control" value="{{ $record->event_status }}">
                                    </div>
                                    <div class="form-group col-md-6">
                                        <label>Next Objective</label>
                                        <input type="text" name="next_objective" class="form-control" value="{{ $record->next_objective }}">
                                    </div>
                                    <div class="form-group col-md-6">
                                        <label>Follow-Up Date</label>
                                        <input type="date" name="follow_up_date" class="form-control" value="{{ $record->follow_up_date }}">
                                    </div>
                                     <div class="col-md-6">
                                                <label>Status</label>
                                                <select name="status" class="form-control">
                                                    <option value="1" {{ $record->status == 1 ? 'selected' : '' }}>Active</option>
                                                    <option value="0" {{ $record->status == 0 ? 'selected' : '' }}>Disabled</option>
                                                </select>
                                        </div>
                                </div>
                            </div>
                        </div>

                        <!-- Submit -->
                        <div class="text-right mt-4">
                            <button class="btn btn-success btn-lg">Submit</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
    @include('portal.footer')
</section>

@endsection
 --}}




@extends('portal.master')
@section('content')
    <section class="main-content">
        <div class="row justify-content-center">
            <div class="col-lg-10">
                @include('portal.flash-message')

                <div class="card">
                    <div class="card-header custfor-flex-header">
                        <div class="header-content">
                            <h3>Edit Organization Details</h3>
                        </div>
                        <div class="header-actions">
                            <a href="{{ route('organization.index') }}" class="btn btn-outline">
                                Back to List
                            </a>
                        </div>
                    </div>

                    <div class="card-body">
                        <form method="post" action="{{ route('organization.update', ['organization' => $record->slug]) }}"
                            enctype="multipart/form-data">
                            {{ csrf_field() }}
                            @method('PUT')

                            <!-- Basic Information -->
                            <div class="form-section">
                                <div class="section-header">
                                    <h5>Basic Information</h5>
                                    <span class="section-badge">Required</span>
                                </div>
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label class="form-label">Name <span class="required">*</span></label>
                                            <input required type="text" name="name" class="form-control"
                                                value="{{ old('name', $record->name) }}">
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label class="form-label">Organization Type</label>
                                            <select name="organization_type_id" class="form-control select2">
                                                <option value="">-- Select Type --</option>
                                                @foreach ($organization_types as $organization_type)
                                                    <option value="{{ $organization_type->id }}"
                                                        {{ old('organization_type_id', $record->organization_type_id) == $organization_type->id ? 'selected' : '' }}>
                                                        {{ $organization_type->name }}
                                                    </option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label class="form-label">Event Type</label>
                                            <select name="event_type_id" class="form-control">
                                                <option value="">-- Select Event Type --</option>
                                                @foreach ($organization_events as $event)
                                                    <option value="{{ $event->id }}"
                                                        {{ old('event_type_id', $record->event_type_id) == $event->id ? 'selected' : '' }}>
                                                        {{ $event->name }}
                                                    </option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label class="form-label">Department</label>
                                            <input type="text" name="department" class="form-control"
                                                value="{{ old('department', $record->department) }}">
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
                                            <label class="form-label">Address Line 1</label>
                                            <input type="text" name="address_one" class="form-control"
                                                value="{{ old('address_one', $record->address_one) }}">
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label class="form-label">Address Line 2</label>
                                            <input type="text" name="address_two" class="form-control"
                                                value="{{ old('address_two', $record->address_two) }}">
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label class="form-label">City</label>
                                            <input type="text" name="city" class="form-control"
                                                value="{{ old('city', $record->city) }}">
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label class="form-label">State</label>
                                            <input type="text" name="state" class="form-control"
                                                value="{{ old('state', $record->state) }}">
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label class="form-label">Zip</label>
                                            <input type="text" name="zip" class="form-control"
                                                value="{{ old('zip', $record->zip) }}">
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label class="form-label">Country</label>
                                            <input type="text" name="country" class="form-control"
                                                value="{{ old('country', $record->country) }}">
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
                                                value="{{ old('email', $record->email) }}">
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label class="form-label">Phone</label>
                                            <input type="text" name="phone" class="form-control"
                                                value="{{ old('phone', $record->phone) }}">
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label class="form-label">Fax</label>
                                            <input type="text" name="fax" class="form-control"
                                                value="{{ old('fax', $record->fax) }}">
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
                                            <label class="form-label">Size</label>
                                            <input type="number" name="size" class="form-control"
                                                value="{{ old('size', $record->size) }}">
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label class="form-label">First Meeting</label>
                                            <input type="date" name="first_meeting" class="form-control"
                                                value="{{ old('first_meeting', $record->first_meeting) }}">
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label class="form-label">Closing Probability (%)</label>
                                            <input type="number" name="closing_probability" class="form-control"
                                                value="{{ old('closing_probability', $record->closing_probability) }}">
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label class="form-label">Event Date</label>
                                            <input type="date" name="event_date" class="form-control"
                                                value="{{ old('event_date', $record->event_date) }}">
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label class="form-label">Event Status</label>
                                            <input type="text" name="event_status" class="form-control"
                                                value="{{ old('event_status', $record->event_status) }}">
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label class="form-label">Next Objective</label>
                                            <input type="text" name="next_objective" class="form-control"
                                                value="{{ old('next_objective', $record->next_objective) }}">
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label class="form-label">Follow-Up Date</label>
                                            <input type="date" name="follow_up_date" class="form-control"
                                                value="{{ old('follow_up_date', $record->follow_up_date) }}">
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label class="form-label">Status</label>
                                            <select name="status" class="form-control">
                                                <option value="1"
                                                    {{ old('status', $record->status) == 1 ? 'selected' : '' }}>Active
                                                </option>
                                                <option value="0"
                                                    {{ old('status', $record->status) == 0 ? 'selected' : '' }}>Disabled
                                                </option>
                                            </select>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Submit -->
                            <div class="form-actions">
                                <button type="submit" class="btn btn-primary">
                                    Update
                                </button>
                                <a href="{{ route('organization.index') }}" class="btn btn-secondary">
                                    Cancel
                                </a>
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
    </style>
@endsection
