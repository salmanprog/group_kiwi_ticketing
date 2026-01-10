{{-- @extends('portal.master')
@section('content')
<section class="main-content py-4">
    <div class="container">
        @include('portal.flash-message')

          <!-- Client Details Card -->
        @if ($record)
        <div class="card shadow-sm">
            <div class="card-header bg-success text-white">
                <h5 class="mb-0">Client Details</h5>
            </div>
            <div class="card-body">
                <div class="row align-items-center">
                    <div class="col-md-8">
                        <p><strong>Name:</strong> {{ $record->name }}</p>
                        <p><strong>Email:</strong> {{ $record->email }}</p>
                        <p><strong>Mobile No:</strong> {{ $record->mobile_no }}</p>
                    </div>
                    <div class="col-md-4 text-center">
                        <p><strong>Profile Picture:</strong></p>
                        <img src="{{ $record->image_url }}" 
                             alt="Admin Picture" class="img-fluid rounded border" style="max-height: 180px;" loading="lazy">
                    </div>
                </div>
            </div>
        </div>
        @endif
      
    </div>

    @include('portal.footer')
</section>
@endsection --}}


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
            font-size: 14px !important;
            line-height: 1.4;
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

        .client-details-grid {
            display: grid;
            grid-template-columns: 2fr 1fr;
            gap: 30px;
            align-items: start;
        }

        .client-info {
            display: flex;
            flex-direction: column;
            gap: 20px;
        }

        .info-item {
            display: flex;
            align-items: flex-start;
            gap: 15px;
            padding: 15px;
            background: #ffffff;
            border: 1px solid #eaeaea;
            border-radius: 8px;
            transition: all 0.3s ease;
        }

        .info-item:hover {
            transform: translateX(5px);
            border-color: #A0C242;
        }

        .info-icon {
            width: 45px;
            height: 45px;
            background: #f3f4f6;
            border-radius: 8px;
            display: flex;
            align-items: center;
            justify-content: center;
            color: #374151;
            font-size: 18px;
            flex-shrink: 0;
            font-weight: bold;
        }

        .info-content {
            flex: 1;
        }

        .info-label {
            font-size: 13px;
            color: #7f8c8d;
            font-weight: 500;
            margin-bottom: 5px;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }

        .info-value {
            font-weight: 600;
            color: #2c3e50;
            font-size: 16px;
            margin: 0;
        }

        .profile-section {
            text-align: center;
            padding: 20px;
            background: #ffffff;
            border: 1px solid #eaeaea;
            border-radius: 12px;
        }

        .profile-picture {
            width: 200px;
            height: 200px;
            border-radius: 50%;
            object-fit: cover;
            border: 4px solid #A0C242;
            box-shadow: 0 4px 20px rgba(160, 194, 66, 0.2);
            margin-bottom: 15px;
            transition: all 0.3s ease;
        }

        .profile-picture:hover {
            transform: scale(1.05);
            box-shadow: 0 8px 25px rgba(160, 194, 66, 0.3);
        }

        .profile-label {
            font-weight: 600;
            color: #2c3e50;
            font-size: 16px;
            margin-bottom: 10px;
            display: block;
        }

        .status-badge {
            display: inline-flex;
            align-items: center;
            gap: 6px;
            padding: 6px 12px;
            border-radius: 20px;
            font-size: 12px;
            font-weight: 600;
            margin-top: 8px;
            background: #f3f4f6;
            color: #374151;
            border: 1px solid #e5e7eb;
        }

        .action-buttons {
            display: flex;
            gap: 15px;
            justify-content: flex-end;
            margin-top: 30px;
            padding-top: 20px;
            border-top: 1px solid #eaeaea;
        }

        .btn {
            border-radius: 6px;
            font-weight: 600;
            padding: 10px 25px;
            font-size: 14px;
            transition: all 0.2s ease;
            border: 1px solid transparent;
            cursor: pointer;
            text-decoration: none;
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

        .btn-outline-secondary {
            background: #ffffff;
            border-color: #d1d5db;
            color: #374151;
        }

        .btn-outline-secondary:hover {
            background: #f9fafb;
            border-color: #9ca3af;
        }

        .alert {
            border-radius: 8px;
            border: none;
            padding: 15px 20px;
            margin-bottom: 25px;
        }

        .alert-success {
            background: linear-gradient(135deg, #d4edda, #c3e6cb);
            color: #155724;
            border-left: 4px solid #28a745;
        }

        .alert-danger {
            background: linear-gradient(135deg, #f8d7da, #f5c6cb);
            color: #721c24;
            border-left: 4px solid #dc3545;
        }

        @media (max-width: 768px) {
            .main-content {
                padding: 15px;
                padding-top: 90px;
            }

            .card-body {
                padding: 20px;
            }

            .client-details-grid {
                grid-template-columns: 1fr;
                gap: 20px;
            }

            .profile-section {
                order: -1;
            }

            .profile-picture {
                width: 150px;
                height: 150px;
            }

            .action-buttons {
                flex-direction: column;
            }

            .btn {
                width: 100%;
                justify-content: center;
            }

            .info-item {
                flex-direction: column;
                text-align: center;
                gap: 10px;
            }

            .info-icon {
                align-self: center;
            }
        }

        .empty-state {
            text-align: center;
            padding: 40px 20px;
            color: #6b7280;
        }
    </style>

    <section class="main-content">
        <div class="container">
            @include('portal.flash-message')

            <!-- Client Details Card -->
            @if ($record)
                <div class="card">
                    <div class="card-header">
                        <h3>Client Details</h3>
                    </div>
                    <div class="card-body">
                        <div class="client-details-grid">
                            <!-- Client Information -->
                            <div class="client-info">
                                <div class="info-item">
                                    {{-- <div class="info-icon">
                                        Email
                                    </div> --}}
                                    <div class="info-content">
                                        <div class="info-label">Email Address</div>
                                        <p class="info-value">{{ $record->email }}</p>
                                    </div>
                                </div>

                                <div class="info-item">
                                    {{-- <div class="info-icon">
                                        Phone
                                    </div> --}}
                                    <div class="info-content">
                                        <div class="info-label">Mobile Number</div>
                                        <p class="info-value">{{ $record->mobile_no ?? 'Not provided' }}</p>
                                    </div>
                                </div>
                            </div>

                            <!-- Profile Picture -->
                            <div class="profile-section">
                                <span class="profile-label">
                                    Profile Picture
                                </span>
                                @if ($record->image_url)
                                    <img src="{{ $record->image_url }}" alt="{{ $record->name }}" class="profile-picture"
                                        loading="lazy"
                                        onerror="this.style.display='none'; document.getElementById('avatar-fallback').style.display='block'">
                                @endif
                                <div id="avatar-fallback" style="display: {{ $record->image_url ? 'none' : 'block' }};">
                                    <div
                                        style="width: 200px; height: 200px; border-radius: 50%; background: #f3f4f6; border: 4px solid #A0C242; display: flex; align-items: center; justify-content: center; margin: 0 auto 15px;">
                                        <span
                                            style="font-size: 24px; font-weight: 600; color: #374151;">{{ substr($record->name, 0, 1) }}</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            @else
                <div class="card">
                    <div class="card-body">
                        <div class="empty-state">
                            <h4>Client Not Found</h4>
                            <p>The requested client details could not be found.</p>
                            <a href="{{ route('manager-management.index') }}" class="btn btn-primary mt-3">
                                Back to Clients List
                            </a>
                        </div>
                    </div>
                </div>
            @endif
        </div>

        @include('portal.footer')
    </section>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Add loading state to profile picture
            const profilePictures = document.querySelectorAll('.profile-picture');
            profilePictures.forEach(img => {
                img.addEventListener('load', function() {
                    this.style.opacity = '1';
                });

                img.style.opacity = '0';
                img.style.transition = 'opacity 0.3s ease';
            });
        });
    </script>
@endsection
