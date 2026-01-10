{{-- @extends('portal.master')
@section('content')
    <section class="main-content">
        <div class="row">
            <div class="col-sm-12">
                @include('portal.flash-message')
                <div class="card">
                    <div class="card-header card-default">
                        Add Salesman
                    </div>
                    <div class="container mt-4">
                        <form method="post" action="{{ route('salesman-management.store') }}" enctype="multipart/form-data">
                            {{ csrf_field() }}

                            <div class="card">
                                <div class="card-header">
                                    <h5>Salesman Details</h5>
                                </div>
                                <div class="card-body">
                                    <input type="hidden" name="user_type" value="admin">
                                    <input type="hidden" name="user_group_id" value="2">

                                    <div class="row">
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label>Name</label>
                                                <input required type="text" name="name" value="{{ old('name') }}" class="form-control">
                                            </div>
                                        </div>

                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label>Email</label>
                                                <input required type="email" name="email" value="{{ old('email') }}" class="form-control">
                                            </div>
                                        </div>

                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label>Profile Picture</label>
                                                <input required type="file" name="image_url" value="{{ old('image_url') }}" class="form-control">
                                            </div>
                                        </div>

                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label>Mobile No</label>
                                                <input required type="text" name="mobile_no" value="{{ old('mobile_no') }}" class="form-control"
                                                    placeholder="+92-3001234567" pattern="^\+?\d{1,3}-\d{9,11}$"
                                                    title="Enter a valid number like +92-3001234567">
                                            </div>
                                        </div>

                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label>Password</label>
                                                <input required type="text" value="{{ old('password') }}" name="password" class="form-control">
                                            </div>
                                        </div>

                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label>Confirm Password</label>
                                                <input required type="text" value="{{ old('confirm_password') }}" name="confirm_password"
                                                    class="form-control">
                                            </div>
                                        </div>
                                    </div>

                                    <div class="form-group mt-3">
                                        <button class="btn btn-success">Submit</button>
                                    </div>
                                </div>
                            </div>
                        </form>
                    </div>

                </div>
            </div>
        </div>
        @include('portal.footer')
    </section>
@endsection --}}

{{-- @extends('portal.master')
@section('content')
    <section class="main-content">
        <div class="row justify-content-center">
            <div class="col-lg-8">
                @include('portal.flash-message')
                <div class="card">
                    <div class="card-header custfor-flex-header">
                        <div class="header-content">
                            <i class="fas fa-user-tie"></i>
                            <h3>Add New Salesman</h3>
                        </div>
                        <div class="header-actions">
                            <a href="{{ route('salesman-management.index') }}" class="btn btn-outline">
                                <i class="fas fa-arrow-left"></i> Back to List
                            </a>
                        </div>
                    </div>
                    <div class="card-body">
                        <form method="post" action="{{ route('salesman-management.store') }}" enctype="multipart/form-data" class="salesman-form">
                            {{ csrf_field() }}
                            <input type="hidden" name="user_type" value="admin">
                            <input type="hidden" name="user_group_id" value="2">

                            <div class="form-section">
                                <div class="section-header">
                                    <i class="fas fa-user-check"></i>
                                    <h5>Salesman Details</h5>
                                    <span class="section-badge">Required Fields</span>
                                </div>
                                
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label class="form-label">
                                                <i class="fas fa-user"></i>
                                                Full Name
                                                <span class="required">*</span>
                                            </label>
                                            <input required type="text" name="name" value="{{ old('name') }}" 
                                                   class="form-control" placeholder="Enter salesman's full name">
                                        </div>
                                    </div>

                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label class="form-label">
                                                <i class="fas fa-envelope"></i>
                                                Email Address
                                                <span class="required">*</span>
                                            </label>
                                            <input required type="email" name="email" value="{{ old('email') }}" 
                                                   class="form-control" placeholder="salesman@example.com">
                                        </div>
                                    </div>

                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label class="form-label">
                                                <i class="fas fa-camera"></i>
                                                Profile Picture
                                                <span class="required">*</span>
                                            </label>
                                            <div class="file-upload-wrapper">
                                                <input type="file" name="image_url" value="{{ old('image_url') }}" 
                                                       class="form-control file-upload-input" accept="image/*" id="profile-picture">
                                                <div class="file-upload-display" id="file-upload-display">
                                                    <i class="fas fa-cloud-upload-alt"></i>
                                                    <span>Choose profile picture</span>
                                                </div>
                                                <!-- Image Preview Container -->
                                                <div class="image-preview-container" id="image-preview-container">
                                                    <div class="image-preview-wrapper">
                                                        <img id="image-preview" src="" alt="Profile Picture Preview">
                                                        <button type="button" class="remove-image-btn" id="remove-image-btn">
                                                            <i class="fas fa-times"></i>
                                                        </button>
                                                    </div>
                                                    <div class="image-info">
                                                        <span id="image-name"></span>
                                                        <span id="image-size"></span>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label class="form-label">
                                                <i class="fas fa-mobile-alt"></i>
                                                Mobile Number
                                                <span class="required">*</span>
                                            </label>
                                            <input required type="text" name="mobile_no" value="{{ old('mobile_no') }}" 
                                                   class="form-control" placeholder="+92-3001234567" 
                                                   pattern="^\+?\d{1,3}-\d{9,11}$">
                                            <small class="form-hint">Format: +CountryCode-Number</small>
                                        </div>
                                    </div>

                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label class="form-label">
                                                <i class="fas fa-lock"></i>
                                                Password
                                                <span class="required">*</span>
                                            </label>
                                            <div class="password-wrapper">
                                                <input required type="password" value="{{ old('password') }}" 
                                                       name="password" class="form-control password-input"
                                                       placeholder="Enter secure password">
                                                <button type="button" class="password-toggle">
                                                    <i class="fas fa-eye"></i>
                                                </button>
                                            </div>
                                            <div class="password-strength">
                                                <div class="strength-bar"></div>
                                                <small class="strength-text">Password strength</small>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label class="form-label">
                                                <i class="fas fa-lock"></i>
                                                Confirm Password
                                                <span class="required">*</span>
                                            </label>
                                            <div class="password-wrapper">
                                                <input required type="password" value="{{ old('confirm_password') }}" 
                                                       name="confirm_password" class="form-control password-input"
                                                       placeholder="Confirm your password">
                                                <button type="button" class="password-toggle">
                                                    <i class="fas fa-eye"></i>
                                                </button>
                                            </div>
                                            <div class="password-match">
                                                <i class="fas fa-check-circle"></i>
                                                <span>Passwords match</span>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="form-actions">
                                <button type="submit" class="btn btn-primary">
                                    <i class="fas fa-user-plus"></i> Add Salesman
                                </button>
                                <button type="reset" class="btn btn-secondary" id="reset-form">
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

        .custfor-flex-header{
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

        .form-hint {
            color: #7f8c8d;
            font-size: 0.85rem;
            margin-top: 5px;
            display: block;
        }

        /* File Upload Styles */
        .file-upload-wrapper {
            position: relative;
        }

        .file-upload-input {
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
            cursor: pointer;
        }

        .file-upload-display i {
            font-size: 2rem;
            color: #A0C242;
            margin-bottom: 10px;
            display: block;
        }

        .file-upload-display:hover {
            background: rgba(160, 194, 66, 0.1);
            border-color: #8AB933;
        }

        /* Image Preview Styles */
        .image-preview-container {
            display: none;
            margin-top: 15px;
            animation: fadeIn 0.3s ease;
        }

        .image-preview-wrapper {
            position: relative;
            display: inline-block;
            max-width: 200px;
        }

        #image-preview {
            max-width: 100%;
            max-height: 150px;
            border-radius: 8px;
            border: 2px solid #A0C242;
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
        }

        .remove-image-btn {
            position: absolute;
            top: -8px;
            right: -8px;
            width: 25px;
            height: 25px;
            background: #e74c3c;
            border: none;
            border-radius: 50%;
            color: white;
            cursor: pointer;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 0.8rem;
            transition: all 0.3s ease;
        }

        .remove-image-btn:hover {
            background: #c0392b;
            transform: scale(1.1);
        }

        .image-info {
            margin-top: 8px;
            text-align: center;
        }

        #image-name {
            display: block;
            font-weight: 600;
            color: #2c3e50;
            font-size: 0.9rem;
        }

        #image-size {
            display: block;
            color: #7f8c8d;
            font-size: 0.8rem;
        }

        @keyframes fadeIn {
            from { opacity: 0; transform: translateY(-10px); }
            to { opacity: 1; transform: translateY(0); }
        }

        /* Password Fields */
        .password-wrapper {
            position: relative;
        }

        .password-input {
            padding-right: 45px;
        }

        .password-toggle {
            position: absolute;
            right: 12px;
            top: 50%;
            transform: translateY(-50%);
            background: none;
            border: none;
            color: #7f8c8d;
            cursor: pointer;
            padding: 5px;
            transition: color 0.3s ease;
        }

        .password-toggle:hover {
            color: #A0C242;
        }

        .password-strength {
            margin-top: 8px;
        }

        .strength-bar {
            height: 4px;
            background: #e0e0e0;
            border-radius: 2px;
            overflow: hidden;
            margin-bottom: 4px;
            position: relative;
        }

        .strength-bar::after {
            content: '';
            position: absolute;
            height: 100%;
            width: var(--strength-width, 0%);
            background: var(--strength-color, #e74c3c);
            border-radius: 2px;
            transition: all 0.3s ease;
        }

        .strength-text {
            color: #7f8c8d;
            font-size: 0.8rem;
        }

        .password-match {
            display: none;
            align-items: center;
            gap: 5px;
            color: #27ae60;
            font-size: 0.85rem;
            margin-top: 5px;
        }

        .password-match i {
            font-size: 0.9rem;
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
    </style>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const profilePictureInput = document.getElementById('profile-picture');
            const fileUploadDisplay = document.getElementById('file-upload-display');
            const imagePreviewContainer = document.getElementById('image-preview-container');
            const imagePreview = document.getElementById('image-preview');
            const imageName = document.getElementById('image-name');
            const imageSize = document.getElementById('image-size');
            const removeImageBtn = document.getElementById('remove-image-btn');
            const resetFormBtn = document.getElementById('reset-form');

            // Profile picture preview functionality
            profilePictureInput.addEventListener('change', function(e) {
                const file = e.target.files[0];
                if (file) {
                    if (file.type.startsWith('image/')) {
                        const reader = new FileReader();
                        
                        reader.onload = function(e) {
                            imagePreview.src = e.target.result;
                            imageName.textContent = file.name;
                            imageSize.textContent = formatFileSize(file.size);
                            
                            // Show preview and hide upload display
                            fileUploadDisplay.style.display = 'none';
                            imagePreviewContainer.style.display = 'block';
                        };
                        
                        reader.readAsDataURL(file);
                    } else {
                        alert('Please select a valid image file.');
                        this.value = '';
                    }
                }
            });

            // Remove image functionality
            removeImageBtn.addEventListener('click', function() {
                profilePictureInput.value = '';
                imagePreviewContainer.style.display = 'none';
                fileUploadDisplay.style.display = 'block';
            });

            // Click on upload display to trigger file input
            fileUploadDisplay.addEventListener('click', function() {
                profilePictureInput.click();
            });

            // Reset form functionality
            resetFormBtn.addEventListener('click', function() {
                imagePreviewContainer.style.display = 'none';
                fileUploadDisplay.style.display = 'block';
            });

            // File size formatter
            function formatFileSize(bytes) {
                if (bytes === 0) return '0 Bytes';
                const k = 1024;
                const sizes = ['Bytes', 'KB', 'MB', 'GB'];
                const i = Math.floor(Math.log(bytes) / Math.log(k));
                return parseFloat((bytes / Math.pow(k, i)).toFixed(2)) + ' ' + sizes[i];
            }

            // Password visibility toggle
            const passwordToggles = document.querySelectorAll('.password-toggle');
            passwordToggles.forEach(toggle => {
                toggle.addEventListener('click', function() {
                    const input = this.parentElement.querySelector('.password-input');
                    const icon = this.querySelector('i');
                    
                    if (input.type === 'password') {
                        input.type = 'text';
                        icon.classList.remove('fa-eye');
                        icon.classList.add('fa-eye-slash');
                    } else {
                        input.type = 'password';
                        icon.classList.remove('fa-eye-slash');
                        icon.classList.add('fa-eye');
                    }
                });
            });

            // Password strength indicator
            const passwordInput = document.querySelector('input[name="password"]');
            const strengthBar = document.querySelector('.strength-bar');
            
            if (passwordInput && strengthBar) {
                passwordInput.addEventListener('input', function() {
                    const password = this.value;
                    let strength = 0;
                    
                    if (password.length > 0) strength += 20;
                    if (password.length >= 8) strength += 20;
                    if (/[A-Z]/.test(password)) strength += 20;
                    if (/[0-9]/.test(password)) strength += 20;
                    if (/[^A-Za-z0-9]/.test(password)) strength += 20;
                    
                    strengthBar.style.setProperty('--strength-width', strength + '%');
                    
                    if (strength < 40) {
                        strengthBar.style.setProperty('--strength-color', '#e74c3c');
                    } else if (strength < 70) {
                        strengthBar.style.setProperty('--strength-color', '#f39c12');
                    } else {
                        strengthBar.style.setProperty('--strength-color', '#27ae60');
                    }
                });
            }

            // Password match indicator
            const confirmPassword = document.querySelector('input[name="confirm_password"]');
            const passwordMatch = document.querySelector('.password-match');
            
            if (confirmPassword && passwordMatch) {
                confirmPassword.addEventListener('input', function() {
                    const password = passwordInput.value;
                    const confirm = this.value;
                    
                    if (confirm.length > 0 && password === confirm) {
                        passwordMatch.style.display = 'flex';
                    } else {
                        passwordMatch.style.display = 'none';
                    }
                });
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
                            <h3>Add New Salesman</h3>
                        </div>
                        <div class="header-actions">
                            <a href="{{ route('salesman-management.index') }}" class="btn btn-outline">
                                Back to List
                            </a>
                        </div>
                    </div>
                    <div class="card-body">
                        <form method="post" action="{{ route('salesman-management.store') }}" enctype="multipart/form-data"
                            class="salesman-form">
                            {{ csrf_field() }}
                            <input type="hidden" name="user_type" value="admin">
                            <input type="hidden" name="user_group_id" value="2">

                            <div class="form-section">
                                <div class="section-header">
                                    <h5>Salesman Details</h5>
                                    <span class="section-badge">Required Fields</span>
                                </div>

                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label class="form-label">
                                                Full Name
                                                <span class="required">*</span>
                                            </label>
                                            <input required type="text" name="name" value="{{ old('name') }}"
                                                class="form-control" placeholder="Enter salesman's full name">
                                        </div>
                                    </div>

                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label class="form-label">
                                                Email Address
                                                <span class="required">*</span>
                                            </label>
                                            <input required type="email" name="email" value="{{ old('email') }}"
                                                class="form-control" placeholder="salesman@example.com">
                                        </div>
                                    </div>

                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label class="form-label">
                                                Profile Picture
                                                <span class="required">*</span>
                                            </label>
                                            <div class="file-upload-wrapper">
                                                <input type="file" name="image_url" value="{{ old('image_url') }}"
                                                    class="form-control file-upload-input" accept="image/*"
                                                    id="profile-picture">
                                                <div class="file-upload-display" id="file-upload-display">
                                                    <span>Choose profile picture</span>
                                                </div>
                                                <!-- Image Preview Container -->
                                                <div class="image-preview-container" id="image-preview-container">
                                                    <div class="image-preview-wrapper">
                                                        <img id="image-preview" src=""
                                                            alt="Profile Picture Preview">
                                                        <button type="button" class="remove-image-btn"
                                                            id="remove-image-btn">
                                                            ×
                                                        </button>
                                                    </div>
                                                    <div class="image-info">
                                                        <span id="image-name"></span>
                                                        <span id="image-size"></span>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label class="form-label">
                                                Mobile Number
                                                <span class="required">*</span>
                                            </label>
                                            <input required type="text" name="mobile_no" value="{{ old('mobile_no') }}"
                                                class="form-control" placeholder="+92-3001234567"
                                                pattern="^\+?\d{1,3}-\d{9,11}$">
                                            <small class="form-hint">Format: +CountryCode-Number</small>
                                        </div>
                                    </div>

                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label class="form-label">
                                                Password
                                                <span class="required">*</span>
                                            </label>
                                            <div class="password-wrapper">
                                                <input required type="password" value="{{ old('password') }}"
                                                    name="password" class="form-control password-input"
                                                    placeholder="Enter secure password">
                                                <button type="button" class="password-toggle">
                                                    👁️
                                                </button>
                                            </div>
                                            <div class="password-strength">
                                                <div class="strength-bar"></div>
                                                <small class="strength-text">Password strength</small>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label class="form-label">
                                                Confirm Password
                                                <span class="required">*</span>
                                            </label>
                                            <div class="password-wrapper">
                                                <input required type="password" value="{{ old('confirm_password') }}"
                                                    name="confirm_password" class="form-control password-input"
                                                    placeholder="Confirm your password">
                                                <button type="button" class="password-toggle">
                                                    👁️
                                                </button>
                                            </div>
                                            <div class="password-match">
                                                ✓
                                                <span>Passwords match</span>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="form-actions">
                                <button type="submit" class="btn btn-primary">
                                    Add Salesman
                                </button>
                                <button type="reset" class="btn btn-secondary" id="reset-form">
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

        .form-hint {
            color: #7f8c8d;
            font-size: 0.85rem;
            margin-top: 5px;
            display: block;
        }

        /* File Upload Styles */
        .file-upload-wrapper {
            position: relative;
        }

        .file-upload-input {
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
            cursor: pointer;
        }

        .file-upload-display span {
            font-weight: 500;
        }

        .file-upload-display:hover {
            background: rgba(160, 194, 66, 0.1);
            border-color: #8AB933;
        }

        /* Image Preview Styles */
        .image-preview-container {
            display: none;
            margin-top: 15px;
            animation: fadeIn 0.3s ease;
        }

        .image-preview-wrapper {
            position: relative;
            display: inline-block;
            max-width: 200px;
        }

        #image-preview {
            max-width: 100%;
            max-height: 150px;
            border-radius: 8px;
            border: 2px solid #A0C242;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
        }

        .remove-image-btn {
            position: absolute;
            top: -8px;
            right: -8px;
            width: 25px;
            height: 25px;
            background: #e74c3c;
            border: none;
            border-radius: 50%;
            color: white;
            cursor: pointer;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 0.8rem;
            transition: all 0.3s ease;
            font-weight: bold;
        }

        .remove-image-btn:hover {
            background: #c0392b;
            transform: scale(1.1);
        }

        .image-info {
            margin-top: 8px;
            text-align: center;
        }

        #image-name {
            display: block;
            font-weight: 600;
            color: #2c3e50;
            font-size: 0.9rem;
        }

        #image-size {
            display: block;
            color: #7f8c8d;
            font-size: 0.8rem;
        }

        @keyframes fadeIn {
            from {
                opacity: 0;
                transform: translateY(-10px);
            }

            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        /* Password Fields */
        .password-wrapper {
            position: relative;
        }

        .password-input {
            padding-right: 45px;
        }

        .password-toggle {
            position: absolute;
            right: 12px;
            top: 50%;
            transform: translateY(-50%);
            background: none;
            border: none;
            color: #7f8c8d;
            cursor: pointer;
            padding: 5px;
            transition: color 0.3s ease;
            font-size: 16px;
        }

        .password-toggle:hover {
            color: #A0C242;
        }

        .password-strength {
            margin-top: 8px;
        }

        .strength-bar {
            height: 4px;
            background: #e0e0e0;
            border-radius: 2px;
            overflow: hidden;
            margin-bottom: 4px;
            position: relative;
        }

        .strength-bar::after {
            content: '';
            position: absolute;
            height: 100%;
            width: var(--strength, 0%);
            background: var(--strength-color, #e74c3c);
            border-radius: 2px;
            transition: all 0.3s ease;
        }

        .strength-text {
            color: #7f8c8d;
            font-size: 0.8rem;
        }

        .password-match {
            display: none;
            align-items: center;
            gap: 5px;
            color: #27ae60;
            font-size: 0.85rem;
            margin-top: 5px;
        }

        /* Actions */
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

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const profilePictureInput = document.getElementById('profile-picture');
            const fileUploadDisplay = document.getElementById('file-upload-display');
            const imagePreviewContainer = document.getElementById('image-preview-container');
            const imagePreview = document.getElementById('image-preview');
            const imageName = document.getElementById('image-name');
            const imageSize = document.getElementById('image-size');
            const removeImageBtn = document.getElementById('remove-image-btn');
            const resetFormBtn = document.getElementById('reset-form');

            // Profile picture preview functionality
            profilePictureInput.addEventListener('change', function(e) {
                const file = e.target.files[0];
                if (file) {
                    if (file.type.startsWith('image/')) {
                        const reader = new FileReader();

                        reader.onload = function(e) {
                            imagePreview.src = e.target.result;
                            imageName.textContent = file.name;
                            imageSize.textContent = formatFileSize(file.size);

                            // Show preview and hide upload display
                            fileUploadDisplay.style.display = 'none';
                            imagePreviewContainer.style.display = 'block';
                        };

                        reader.readAsDataURL(file);
                    } else {
                        alert('Please select a valid image file.');
                        this.value = '';
                    }
                }
            });

            // Remove image functionality
            removeImageBtn.addEventListener('click', function() {
                profilePictureInput.value = '';
                imagePreviewContainer.style.display = 'none';
                fileUploadDisplay.style.display = 'block';
            });

            // Click on upload display to trigger file input
            fileUploadDisplay.addEventListener('click', function() {
                profilePictureInput.click();
            });

            // Reset form functionality
            resetFormBtn.addEventListener('click', function() {
                imagePreviewContainer.style.display = 'none';
                fileUploadDisplay.style.display = 'block';
            });

            // File size formatter
            function formatFileSize(bytes) {
                if (bytes === 0) return '0 Bytes';
                const k = 1024;
                const sizes = ['Bytes', 'KB', 'MB', 'GB'];
                const i = Math.floor(Math.log(bytes) / Math.log(k));
                return parseFloat((bytes / Math.pow(k, i)).toFixed(2)) + ' ' + sizes[i];
            }

            // Password visibility toggle
            const passwordToggles = document.querySelectorAll('.password-toggle');
            passwordToggles.forEach(toggle => {
                toggle.addEventListener('click', function() {
                    const input = this.parentElement.querySelector('.password-input');

                    if (input.type === 'password') {
                        input.type = 'text';
                        this.textContent = '👁️‍🗨️';
                    } else {
                        input.type = 'password';
                        this.textContent = '👁️';
                    }
                });
            });

            // Password strength indicator
            const passwordInput = document.querySelector('input[name="password"]');
            const strengthBar = document.querySelector('.strength-bar');

            if (passwordInput && strengthBar) {
                passwordInput.addEventListener('input', function() {
                    const password = this.value;
                    let strength = 0;

                    if (password.length > 0) strength += 20;
                    if (password.length >= 8) strength += 20;
                    if (/[A-Z]/.test(password)) strength += 20;
                    if (/[0-9]/.test(password)) strength += 20;
                    if (/[^A-Za-z0-9]/.test(password)) strength += 20;

                    const bar = strengthBar;
                    bar.style.setProperty('--strength', strength + '%');

                    if (strength < 40) {
                        bar.style.setProperty('--strength-color', '#e74c3c');
                    } else if (strength < 70) {
                        bar.style.setProperty('--strength-color', '#f39c12');
                    } else {
                        bar.style.setProperty('--strength-color', '#27ae60');
                    }
                });
            }

            // Password match indicator
            const confirmPassword = document.querySelector('input[name="confirm_password"]');
            const passwordMatch = document.querySelector('.password-match');

            if (confirmPassword && passwordMatch) {
                confirmPassword.addEventListener('input', function() {
                    const password = passwordInput.value;
                    const confirm = this.value;

                    if (confirm.length > 0 && password === confirm) {
                        passwordMatch.style.display = 'flex';
                    } else {
                        passwordMatch.style.display = 'none';
                    }
                });
            }
        });
    </script>
@endsection
