@extends('portal.master')
@section('content')
    <section class="main-content">
        <div class="row justify-content-center">
            <div class="col-lg-8">
                @include('portal.flash-message')
                <div class="card">
                    <div class="card-header custfor-flex-header">
                        <div class="header-content">
                            {{-- <i class="fas fa-user-plus"></i> --}}
                            <h3>Add New Contact</h3>
                        </div>
                        <div class="header-actions">
                            <a href="{{ route('client-management.index') }}" class="btn btn-outline">
                                Back to List
                            </a>
                        </div>
                    </div>
                    <div class="card-body">
                        <form method="post" action="{{ route('client-management.store') }}" enctype="multipart/form-data"
                            class="client-form">
                            {{ csrf_field() }}

                            <div class="form-section">
                                <div class="section-header">
                                    {{-- <i class="fas fa-user-circle"></i> --}}
                                    <h5>Contact Details</h5>
                                    <span class="section-badge">Required Fields</span>
                                </div>

                                <div class="row">
                                    <div class="col-md-12">
                                        <div class="form-group">
                                            <label class="form-label">
                                                Title
                                                <span class="required">*</span>
                                            </label>
                                            <input required type="text" name="title" value="{{ old('title') }}"
                                                class="form-control" placeholder="Enter title">
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label class="form-label">
                                                Salutation <span class="required">*</span>
                                            </label>
                                            <select name="salutation" class="form-control" required>
                                                <option value="">-- Select Salutation --</option>
                                                <option value="Mr"  {{ old('salutation') == 'Mr' ? 'selected' : '' }}>Mr</option>
                                                <option value="Mrs" {{ old('salutation') == 'Mrs' ? 'selected' : '' }}>Mrs</option>
                                                <option value="Ms"  {{ old('salutation') == 'Ms' ? 'selected' : '' }}>Ms</option>
                                                <option value="Dr"  {{ old('salutation') == 'Dr' ? 'selected' : '' }}>Dr</option>
                                                <option value="Prof"{{ old('salutation') == 'Prof' ? 'selected' : '' }}>Prof</option>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label class="form-label">
                                                {{-- <i class="fas fa-building"></i> --}}
                                                Account
                                                <span class="required">*</span>
                                            </label>
                                            <select name="organization_id"
                                                    id="organization_id"
                                                    class="form-control select2"
                                                    required>
                                                <option value="">-- Select Account --</option>
                                                @foreach ($organizations as $organization)
                                                    <option value="{{ $organization->id }}">
                                                        {{ $organization->name }}
                                                    </option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>
                                    <!-- Basic Information -->
                                    <div class="form-section">
                                        <div class="section-header">
                                            <h5>Account Information</h5>
                                            <span class="section-badge">Required</span>
                                        </div>
                                        <div class="row">
                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <label class="form-label">Account Name <span class="required">*</span></label>
                                                    <input required type="text" name="name" class="form-control"
                                                        value="{{ old('name') }}" readonly>
                                                </div>
                                            </div>
                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <label class="form-label">Contact <span class="required">*</span></label>
                                                    <input required type="text" name="contact" class="form-control"
                                                        value="{{ old('contact') }}" readonly>
                                                </div>
                                            </div>
                                            <div class="col-md-12">
                                                <div class="form-group">
                                                    <label class="form-label">Department</label>
                                                    <input type="text" name="department" class="form-control"
                                                        value="{{ old('department') }}" readonly>
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
                                                    <input required type="text" name="city" class="form-control"
                                                        value="{{ old('city') }}" readonly>
                                                </div>
                                            </div>
                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <label class="form-label">State</label>
                                                    <input required type="text" name="state" class="form-control"
                                                        value="{{ old('state') }}" readonly>
                                                </div>
                                            </div>
                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <label class="form-label">Country</label>
                                                    <input required type="text" name="country" class="form-control"
                                                        value="{{ old('country') }}" readonly>
                                                </div>
                                            </div>
                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <label class="form-label">Zip</label>
                                                    <input required type="text" name="zip" class="form-control"
                                                        value="{{ old('zip') }}" readonly>
                                                </div>
                                            </div>
                                            <div class="col-md-12">
                                                <div class="form-group">
                                                    <label class="form-label">Address Line 1</label>
                                                    <input required type="text" name="address_one" class="form-control"
                                                        value="{{ old('address_one') }}" readonly>
                                                </div>
                                            </div>
                                            <div class="col-md-12">
                                                <div class="form-group">
                                                    <label class="form-label">Address Line 2</label>
                                                    <input type="text" name="address_two" class="form-control"
                                                        value="{{ old('address_two') }}" readonly>
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
                                                    <label class="form-label">
                                                        {{-- <i class="fas fa-user"></i> --}}
                                                        First Name
                                                        <span class="required">*</span>
                                                    </label>
                                                    <input required type="text" name="first_name" value="{{ old('first_name') }}"
                                                        class="form-control" placeholder="Enter first name">
                                                </div>
                                            </div>

                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <label class="form-label">
                                                        {{-- <i class="fas fa-user"></i> --}}
                                                        Last Name
                                                        <span class="required">*</span>
                                                    </label>
                                                    <input required type="text" name="last_name" value="{{ old('last_name') }}"
                                                        class="form-control" placeholder="Enter last name">
                                                </div>
                                            </div>
                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <label class="form-label">Email</label>
                                                    <input required type="email" name="email" class="form-control"
                                                        value="{{ old('email') }}" readonly>
                                                </div>
                                            </div>
                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <label class="form-label">Phone</label>
                                                    <input required type="text" name="mobile_no" class="form-control"
                                                        value="{{ old('mobile_no') }}">
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
                                                        value="{{ old('rep') }}" readonly>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <!-- Contract Detail -->
                                    <div class="form-section">
                                        <div class="section-header">
                                            <h5>Contract Detail</h5>
                                        </div>
                                        <div class="row">
                                             <div class="col-md-6">
                                                <div class="form-group">
                                                    <label class="form-label">
                                                        Ticket Rate
                                                        <span class="required">*</span>
                                                    </label>
                                                    <input required type="text" name="ticket_rate" value="{{ old('ticket_rate') }}"
                                                        class="form-control" placeholder="Enter ticket rate">
                                                </div>
                                            </div>

                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <label class="form-label">
                                                        Catering Menu
                                                        <span class="required">*</span>
                                                    </label>
                                                    <input required type="text" name="catering_menu" value="{{ old('catering_menu') }}"
                                                        class="form-control" placeholder="Enter catering menu">
                                                </div>
                                            </div>
                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <label class="form-label">Catering Price</label>
                                                    <input required type="text" name="catering_price" class="form-control"
                                                        value="{{ old('catering_price') }}">
                                                </div>
                                            </div>
                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <label class="form-label">Deposite Amount</label>
                                                    <input required type="text" name="deposite_amount" class="form-control"
                                                        value="{{ old('deposite_amount') }}">
                                                </div>
                                            </div>
                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <label class="form-label">Hours</label>
                                                    <input required type="text" name="hours" class="form-control"
                                                        value="{{ old('hours') }}">
                                                </div>
                                            </div>
                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <label class="form-label">Alt Contacts</label>
                                                    <input required type="text" name="alt_contact" class="form-control"
                                                        value="{{ old('alt_contact') }}">
                                                </div>
                                            </div>
                                            <div class="col-md-12">
                                                <div class="form-group">
                                                    <label class="form-label">Notes/History</label>
                                                    <input type="text" name="note_history" class="form-control"
                                                        value="{{ old('note_history') }}">
                                                </div>
                                            </div>
                                            <div class="col-md-12">
                                                <div class="form-group">
                                                    <label>Status</label>
                                                    <select name="contract_status" class="form-control">
                                                        <option value="">Select Status</option>
                                                        <option value="called">Called</option>
                                                        <option value="dead">Dead</option>
                                                        <option value="lead">Lead</option>
                                                        <option value="pending">Pending</option>
                                                        <option value="tentative_date">Tentative Date</option>
                                                        <option value="loa_send_close">LOA Sent (Closed)</option>
                                                        <option value="loa_received">LOA Received</option>
                                                        <option value="gate_group">Gate Group</option>
                                                    </select>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                </div>
                            </div>

                            <div class="form-actions">
                                <button type="submit" class="btn btn-primary btn-outline">
                                    Add Contact
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

        .header-content i {
            font-size: 1.8rem;
            opacity: 0.9;
        }

        .header-content h3 {
            margin: 0;
            font-weight: 600;
            font-size: 18px;
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

        .form-hint {
            color: #7f8c8d;
            font-size: 0.85rem;
            margin-top: 5px;
            display: block;
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

        /* Input focus animations */
        .form-control:focus {
            transform: translateY(-1px);
        }
    </style>
@endsection
@push('scripts')
<script>
$(document).ready(function () {

    $('#organization_id').on('change', function () {
        let organizationId = $(this).val();

        if (!organizationId) {
            // clear fields if no selection
            $('input[type=text]').val('');
            return;
        }

        $.ajax({
            url: "{{ route('organization.fetch', ':id') }}".replace(':id', organizationId),
            type: "GET",
            success: function (res) {
                if (res.status) {
                    let org = res.data;

                    // Account Information
                    $('input[name="name"]').val(org.name);
                    $('input[name="contact"]').val(org.contact);
                    $('input[name="department"]').val(org.department);

                    // Address
                    $('input[name="city"]').val(org.city);
                    $('input[name="state"]').val(org.state);
                    $('input[name="country"]').val(org.country);
                    $('input[name="zip"]').val(org.zip);
                    $('input[name="address_one"]').val(org.address_one);
                    $('input[name="address_two"]').val(org.address_two);

                    $('input[name="first_name"]').val(org.first_name);
                    $('input[name="last_name"]').val(org.last_name);
                    $('input[name="email"]').val(org.email);
                    $('input[name="mobile_no"]').val(org.mobile_no);
                    $('input[name="fax"]').val(org.fax);
                    $('input[name="rep"]').val(org.rep);
                }
            },
            error: function (xhr) {
                console.error(xhr.responseText);
            }
        });
    });

});
</script>
@endpush
