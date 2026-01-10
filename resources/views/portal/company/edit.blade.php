@extends('portal.master')
@section('content')
    <section class="main-content">
        <div class="row justify-content-center">
            <div class="col-lg-10">
                @include('portal.flash-message')
                <div class="card">
                    <div class="card-header">
                        <div class="header-content">
                            <h3>Edit Company</h3>
                        </div>
                        <div class="header-actions">
                            <a href="{{ route('company-management.index') }}" class="btn btn-outline">
                                Back to List
                            </a>
                        </div>
                    </div>
                    <div class="card-body">
                        <form method="post" action="{{ route('company-management.update', ['company_management' => $record->slug]) }}" enctype="multipart/form-data">
                            {{ csrf_field() }}
                            <input type="hidden" name="_method" value="PUT">
                            <!-- Company Details -->
                            <div class="form-section">
                                <div class="section-header">
                                    <h5>Company Information</h5>
                                    <span class="section-badge">Required Fields</span>
                                </div>
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label class="form-label">Company Name <span class="required">*</span></label>
                                            <input required type="text" name="company_name" class="form-control"
                                                value="{{ old('company_name',$record->name)}}" placeholder="Enter company name">
                                        </div>
                                    </div>

                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label class="form-label">Company Email <span class="required">*</span></label>
                                            <input required type="email" value="{{ old('email',$record->email) }}"
                                                name="company_email" class="form-control" placeholder="company@example.com" readonly>
                                        </div>
                                    </div>

                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label class="form-label">Company Mobile No <span
                                                    class="required">*</span></label>
                                            <input required type="text" name="company_mobile_no"
                                                value="{{ old('company_mobile_no',$record->mobile_no) }}" class="form-control"
                                                placeholder="+92-3001234567" pattern="^\+?\d{1,3}-\d{9,11}$" readonly>
                                            <small class="form-hint">Format: +CountryCode-Number</small>
                                        </div>
                                    </div>

                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label class="form-label">Company Website</label>
                                            <input type="url" name="company_website"
                                                value="{{ old('company_website',$record->website) }}" class="form-control"
                                                placeholder="https://example.com">
                                        </div>
                                    </div>

                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label class="form-label">Company Address <span
                                                    class="required">*</span></label>
                                            <input required type="text" name="company_address"
                                                value="{{ old('company_address',$record->address) }}" class="form-control"
                                                placeholder="Enter full address">
                                        </div>
                                    </div>

                                    <div class="col-md-6">
                                        <div class="form-group">
                                            @if($record->image_url)
                                                <img src="{{ \Storage::url($record->image_url) }}" height="60" class="mt-2" alt="Company Logo">
                                            @endif
                                            <label class="form-label">Company Logo <span class="required">*</span></label>
                                            <input type="file" name="company_image_url"
                                                value="{{ old('company_image_url') }}" class="form-control"
                                                accept="image/*">
                                        </div>
                                    </div>

                                    <div class="col-md-12">
                                        <div class="form-group">
                                            <label class="form-label">Company Description</label>
                                            <textarea name="company_description" rows="4" class="form-control"
                                                placeholder="Brief description about the company">{{ old('company_description', $record->description) }}</textarea>
                                            @error('company_description')
                                                <small class="error-message">{{ $message }}</small>
                                            @enderror
                                        </div>
                                    </div>
                                    <div class="col-md-12">
                                            <div class="form-group">
                                                <label>Status</label>
                                                <select name="status" class="form-control">
                                                    <option value="1" {{ $record->status == 1 ? 'selected' : '' }}>Active</option>
                                                    <option value="0" {{ $record->status == 0 ? 'selected' : '' }}>Disabled</option>
                                                </select>
                                            </div>
                                        </div>
                                </div>
                            </div>

                            <!-- Admin Details -->
                            <div class="form-section">
                                <div class="section-header">
                                    <h5>Owner/Admin Details</h5>
                                    <span class="section-badge">Required Fields</span>
                                </div>
                                <input type="hidden" name="user_type" value="admin">
                                <input type="hidden" name="user_group_id" value="2">

                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label class="form-label">Full Name <span class="required">*</span></label>
                                            <input required type="text" name="namea" value="{{ old('name', $record->admin_name) }}"
                                                class="form-control" placeholder="Enter full name" readonly>
                                        </div>
                                    </div>

                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label class="form-label">Email Address <span
                                                    class="required">*</span></label>
                                            <input required type="email" name="emaila" value="{{ old('email', $record->admin_email) }}"
                                                class="form-control" placeholder="user@example.com" readonly>
                                        </div>
                                    </div>

                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label class="form-label">Profile Picture <span
                                                    class="required">*</span></label>
                                            @if($record->admin_image_url)
                                            <img style="width: 150px; height: 100px; object-fit: contain;" src="{{ \Storage::url($record->admin_image_url) }}">
                                            @endif
                                        </div>
                                    </div>

                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label class="form-label">Mobile Number <span
                                                    class="required">*</span></label>
                                            <input required type="text" name="mobile_noa"
                                                value="{{ old('mobile_no', $record->admin_mobile_no) }}" class="form-control"
                                                placeholder="+92-3001234567" pattern="^\+?\d{1,3}-\d{9,11}$" readonly>
                                            <small class="form-hint">Format: +CountryCode-Number</small>
                                        </div>
                                    </div>
                                    
                                </div>
                            </div>

                            <div class="form-actions">
                                <button type="submit" class="btn btn-primary">
                                    Update Company
                                </button>
                                <a href="{{ route('company-management.index') }}" class="btn btn-secondary">
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
            display: flex;
            justify-content: space-between;
            align-items: center;
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

        .form-hint {
            color: #7f8c8d;
            font-size: 0.85rem;
            margin-top: 5px;
            display: block;
        }

        textarea.form-control {
            resize: vertical;
            min-height: 100px;
        }

        .error-message {
            color: #e74c3c;
            font-size: 0.85rem;
            margin-top: 5px;
            display: block;
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

            .card-header {
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
