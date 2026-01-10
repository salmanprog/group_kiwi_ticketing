@extends('portal.master')
@section('content')
    <style>
        :root {
            --primary-color: #A0C242;
            --primary-dark: #8AA835;
            --primary-light: #E8F4D3;
            --secondary-color: #2C3E50;
            --light-bg: #F8F9FA;
            --border-color: #E0E0E0;
            --text-color: #333333;
            --text-light: #6C757D;
        }

        body {
            font-family: "Poppins", sans-serif !important;
            background-color: #f5f7fa;
            color: var(--text-color);
        }

        .main-content {
            background: #f8faf9;
            min-height: 100vh;
            padding: 30px;
            padding-top: 90px;
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

        .card-header h3 {
            margin: 0;
            font-weight: 600;
            font-size: 18px;
        }

        .card-body {
            padding: 30px;
        }

        .detail-section {
            margin-bottom: 25px;
        }

        .detail-section:last-child {
            margin-bottom: 0;
        }

        .detail-label {
            font-weight: 600;
            color: var(--secondary-color);
            margin-bottom: 5px;
            font-size: 14px;
        }

        .detail-value {
            color: var(--text-color);
            font-size: 15px;
            margin-bottom: 15px;
        }

        .detail-value a {
            color: var(--primary-color);
            text-decoration: none;
            font-weight: 500;
        }

        .detail-value a:hover {
            text-decoration: underline;
        }

        .user-avatar,
        .company-logo {
            width: 180px;
            height: 180px;
            object-fit: cover;
            border-radius: 12px;
            border: 3px solid #e5e7eb;
            padding: 3px;
            background: white;
        }

        .image-container {
            text-align: center;
            padding: 20px;
        }

        .image-label {
            font-weight: 600;
            color: #2c3e50;
            margin-bottom: 15px;
            display: block;
            font-size: 15px;
        }

        .description-box {
            background: #ffffff;
            border: 1px solid #eaeaea;
            border-radius: 8px;
            padding: 20px;
            margin-top: 10px;
        }

        .description-text {
            color: var(--text-color);
            line-height: 1.6;
            margin: 0;
        }

        .contact-info {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
            gap: 15px;
            margin-top: 10px;
        }

        .contact-item {
            display: flex;
            flex-direction: column;
        }

        @media (max-width: 768px) {
            .main-content {
                padding: 15px;
                padding-top: 90px;
            }

            .card-body {
                padding: 20px;
            }

            .user-avatar,
            .company-logo {
                width: 150px;
                height: 150px;
                margin-bottom: 20px;
            }

            .image-container {
                order: -1;
                padding: 0;
                margin-bottom: 25px;
            }

            .contact-info {
                grid-template-columns: 1fr;
            }
        }

        .status-badge {
            display: inline-block;
            padding: 4px 12px;
            border-radius: 20px;
            font-size: 12px;
            font-weight: 600;
            margin-left: 10px;
            background: #f3f4f6;
            color: #374151;
            border: 1px solid #e5e7eb;
        }

        .divider {
            height: 1px;
            background: #e5e7eb;
            margin: 25px 0;
        }
    </style>

    <section class="main-content">
        <div class="container col-lg-8">
            @include('portal.flash-message')
            <!-- Owner Details Card -->
            @if ($company->companyAdmin)
                <div class="card">
                    <div class="card-header">
                        <h3>Owner Details</h3>
                    </div>
                    <div class="card-body">
                        <div class="row align-items-start">
                            <div class="col-md-8 px-4">
                                <div class="contact-info">
                                    <div class="contact-item">
                                        <span class="detail-label">Full Name</span>
                                        <span class="detail-value">{{ $company->companyAdmin->user->name }}</span>
                                    </div>
                                    <div class="contact-item">
                                        <span class="detail-label">Email Address</span>
                                        <span class="detail-value">{{ $company->companyAdmin->user->email }}</span>
                                    </div>

                                </div>
                            </div>
                            <div class="col-md-4">
                                {{-- <div class="image-container">
                                    <span class="image-label">Profile Picture</span>
                                    @if ($company->companyAdmin->user->image_url)
                                        <img src="{{ config('constants.storage_url') . $company->companyAdmin->user->image_url }}"
                                            alt="Owner Picture" class="user-avatar" loading="lazy">
                                    @else
                                        <p class="text-muted">No image available</p>
                                    @endif
                                </div> --}}
                                <div class="contact-item">
                                    <span class="detail-label">Mobile Number</span>
                                    <span class="detail-value">{{ $company->companyAdmin->user->mobile_no }}</span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            @endif

            <!-- Company Details Card -->
            <div class="card">
                <div class="card-header">
                    <h3>Company Details</h3>
                </div>
                <div class="card-body">
                    <div class="row align-items-start">
                        <div class="col-md-8 px-4">
                            <div class="contact-info">
                                <div class="contact-item">
                                    <span class="detail-label">Company Name</span>
                                    <span class="detail-value">{{ $company->name }}</span>
                                </div>
                                <div class="contact-item">
                                    <span class="detail-label">Email Address</span>
                                    <span class="detail-value">{{ $company->email }}</span>
                                </div>
                                <div class="contact-item">
                                    <span class="detail-label">Mobile Number</span>
                                    <span class="detail-value">{{ $company->mobile_no }}</span>
                                </div>
                                <div class="contact-item">
                                    <span class="detail-label">Website</span>
                                    <span class="detail-value">
                                        <a href="{{ $company->company_website }}" target="_blank"
                                            class="text-decoration-none">
                                            {{ $company->website }}
                                        </a>
                                    </span>
                                </div>
                                <div class="contact-item">
                                    <span class="detail-label">Address</span>
                                    <span class="detail-value">{{ $company->address }}</span>
                                </div>
                            </div>

                            <div class="detail-section">
                                <span class="detail-label">Company Description</span>
                                <div class="description-box">
                                    <p class="description-text">{{ $company->description }}</p>
                                </div>
                            </div>
                        </div>
                        {{-- <div class="col-md-4">
                            <div class="image-container">
                                <span class="image-label">Company Logo</span>
                                @if ($company->image_url)
                                    <img src="{{ config('constants.storage_url') . $company->image_url }}"
                                        alt="Company Logo" class="company-logo" loading="lazy">
                                @else
                                    <p class="text-muted">No logo available</p>
                                @endif
                            </div>
                        </div> --}}
                    </div>
                </div>
            </div>
        </div>

        @include('portal.footer')
    </section>
@endsection
