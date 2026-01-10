{{-- @extends('portal.master')
@section('content')
    <section class="main-content">
        <div class="row">
            <div class="col-sm-12">
                @include('portal.flash-message')
                <div class="card">
                    <div class="card-header card-default">
                        Application Setting
                    </div>
                    <div class="card-body">
                        <form method="post" action="" enctype="multipart/form-data">
                            {{ csrf_field() }}
                            <div class="form-group">
                                <label>Application Name</label>
                                <input type="text" value="{{  appSetting('application_setting','application_name') }}" name="application_name" class="form-control" required>
                            </div>
                            <div class="form-group">
                                <label>Logo</label>
                                <input type="file" name="logo" class="form-control">
                                <input type="hidden" name="old_logo" value="{{ appSetting('application_setting','logo') }}">
                                @if (!empty(appSetting('application_setting', 'logo')))
                                    <img style="width: 150px; height: 80px; object-fit: contain" src="{{ appSetting('application_setting','logo') }}">
                                @endif
                            </div>
                            <div class="form-group">
                                <label>Favicon</label>
                                <input type="file" name="favicon" class="form-control">
                                <input type="hidden" name="old_favicon" value="{{ appSetting('application_setting','favicon') }}">
                                @if (!empty(appSetting('application_setting', 'favicon')))
                                    <img style="width:60px; height: 60px; object-fit: contain" src="{{ appSetting('application_setting','favicon') }}">
                                @endif
                            </div>
                            <div class="form-group">
                                <button class="btn btn-primary">Submit</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
        @include('portal.footer')
    </section>
@endsection --}}




@extends('portal.master')
@section('content')
    <section class="main-content">
        <div class="row justify-content-center">
            <div class="col-lg-8">
                @include('portal.flash-message')
                <div class="card">
                    <div class="card-header">
                        <div class="header-content">
                            <h3>Application Settings</h3>
                        </div>
                    </div>
                    <div class="card-body-cust">
                        <form method="post" action="" enctype="multipart/form-data" class="settings-form">
                            {{ csrf_field() }}

                            <div class="form-group">
                                <label class="form-label">
                                    Application Name
                                    <span class="required">*</span>
                                </label>
                                <input type="text" value="{{ appSetting('application_setting', 'application_name') }}"
                                    name="application_name" class="form-control" required
                                    placeholder="Enter your application name">
                            </div>

                            <div class="form-group">
                                <label class="form-label">
                                    Logo
                                </label>
                                <div class="file-upload-section">
                                    <div class="file-input-wrapper">
                                        <input type="file" name="logo" class="form-control file-input"
                                            accept="image/*" onchange="previewImage(this, 'logo-preview')">
                                        <div class="file-upload-display">
                                            <span>Click to upload logo</span>
                                        </div>
                                    </div>
                                    <input type="hidden" name="old_logo"
                                        value="{{ appSetting('application_setting', 'logo') }}">

                                    <div class="image-preview-container">
                                        @if (!empty(appSetting('application_setting', 'logo')))
                                            <div class="current-image">
                                                <span>Current Logo:</span>
                                                <img src="{{ appSetting('application_setting', 'logo') }}" alt="Current Logo"
                                                    class="image-preview" id="logo-preview">
                                            </div>
                                        @else
                                            <div class="no-image">
                                                <span>No logo uploaded</span>
                                            </div>
                                        @endif
                                    </div>
                                </div>
                            </div>

                            <div class="form-group">
                                <label class="form-label">
                                    Favicon
                                </label>
                                <div class="file-upload-section">
                                    <div class="file-input-wrapper">
                                        <input type="file" name="favicon" class="form-control file-input"
                                            accept="image/*" onchange="previewImage(this, 'favicon-preview')">
                                        <div class="file-upload-display">
                                            <span>Click to upload favicon</span>
                                        </div>
                                    </div>
                                    <input type="hidden" name="old_favicon"
                                        value="{{ appSetting('application_setting', 'favicon') }}">

                                    <div class="image-preview-container">
                                        @if (!empty(appSetting('application_setting', 'favicon')))
                                            <div class="current-image">
                                                <span>Current Favicon:</span>
                                                <img src="{{ appSetting('application_setting', 'favicon') }}"
                                                    alt="Current Favicon" class="image-preview favicon-preview"
                                                    id="favicon-preview">
                                            </div>
                                        @else
                                            <div class="no-image">
                                                <span>No favicon uploaded</span>
                                            </div>
                                        @endif
                                    </div>
                                </div>
                            </div>

                            <div class="form-actions">
                                <button type="submit" class="btn btn-primary">
                                    Save Settings
                                </button>
                                <button type="reset" class="btn btn-secondary">
                                    Reset
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

        .card-body-cust {
            padding: 30px;
        }

        .settings-form {
            max-width: 100%;
        }

        .form-group {
            margin-bottom: 25px;
            background: #ffffff;
            border: 1px solid #eaeaea;
            border-radius: 8px;
            padding: 25px;
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

        /* File Upload Styles */
        .file-upload-section {
            display: flex;
            flex-direction: column;
            gap: 15px;
        }

        .file-input-wrapper {
            position: relative;
        }

        .file-input {
            position: absolute;
            width: 100%;
            height: 100%;
            opacity: 0;
            cursor: pointer;
            z-index: 2;
        }

        .file-upload-display {
            border: 2px dashed #A0C242;
            border-radius: 6px;
            padding: 20px;
            text-align: center;
            background: rgba(160, 194, 66, 0.05);
            transition: all 0.3s ease;
            color: #666;
        }

        .file-upload-display span {
            font-weight: 500;
        }

        .file-upload-display:hover {
            background: rgba(160, 194, 66, 0.1);
            border-color: #8AB933;
        }

        .file-input:focus+.file-upload-display {
            border-color: #8AB933;
            box-shadow: 0 0 0 3px rgba(160, 194, 66, 0.1);
        }

        .image-preview-container {
            margin-top: 10px;
        }

        .current-image {
            display: flex;
            align-items: center;
            gap: 15px;
            padding: 15px;
            background: #fff;
            border-radius: 6px;
            border: 1px solid #eaeaea;
        }

        .current-image span {
            font-weight: 600;
            color: #2c3e50;
            min-width: 100px;
        }

        .image-preview {
            max-width: 150px;
            max-height: 80px;
            object-fit: contain;
            border-radius: 4px;
            border: 1px solid #eaeaea;
            padding: 5px;
            background: #fff;
        }

        .favicon-preview {
            max-width: 60px;
            max-height: 60px;
        }

        .no-image {
            padding: 15px;
            background: #f8f9fa;
            border-radius: 6px;
            border: 1px dashed #ccc;
            color: #666;
            text-align: center;
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
            text-decoration: none;
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

        /* Flash Message Styling */
        .alert {
            border-radius: 8px;
            border: none;
            padding: 15px 20px;
            margin-bottom: 25px;
            font-weight: 500;
            border-left: 4px solid #A0C242;
        }

        .alert-success {
            background: #e8f5e8;
            color: #2e7d32;
        }

        .alert-danger {
            background: #ffebee;
            color: #c62828;
            border-left-color: #f44336;
        }

        /* Responsive */
        @media (max-width: 768px) {
            .main-content {
                padding: 15px;
                padding-top: 90px;
            }

            .card-body-cust {
                padding: 20px;
            }

            .form-group {
                padding: 20px;
            }

            .current-image {
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

    <script>
        function previewImage(input, previewId) {
            const preview = document.getElementById(previewId);
            const file = input.files[0];

            if (file) {
                const reader = new FileReader();

                reader.onload = function(e) {
                    preview.src = e.target.result;
                    preview.style.display = 'block';

                    // Hide no-image message if exists
                    const noImage = preview.parentElement.querySelector('.no-image');
                    if (noImage) {
                        noImage.style.display = 'none';
                    }

                    // Show current image container if hidden
                    const currentImage = preview.parentElement;
                    if (currentImage.style.display === 'none') {
                        currentImage.style.display = 'flex';
                    }
                }

                reader.readAsDataURL(file);
            }
        }

        // Initialize previews for existing images
        document.addEventListener('DOMContentLoaded', function() {
            const existingImages = document.querySelectorAll('.image-preview');
            existingImages.forEach(img => {
                img.style.display = 'block';
            });
        });
    </script>
@endsection
