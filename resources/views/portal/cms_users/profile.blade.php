{{-- @extends('portal.master')
@section('content')
    <section class="main-content">
        <div class="row">
            <div class="col-sm-12">
                @include('portal.flash-message')
                <div class="card">
                    <div class="card-header card-default">
                        Profile
                    </div>
                    <div class="card-body">
                        <form method="post" action="" enctype="multipart/form-data">
                            {{ csrf_field() }}
                            <div class="form-group">
                                <label>Name</label>
                                <input type="text" value="{{ currentUser()->name }}" name="name" class="form-control">
                            </div>
                            <div class="form-group">
                                <label>Email</label>
                                <input type="email" name="email" value="{{ currentUser()->email }}" class="form-control">
                            </div>
                            <div class="form-group">
                                <label>Mobile No</label>
                                <input type="text" value="{{ currentUser()->mobile_no }}" name="mobile_no" class="form-control">
                            </div>
                            <div class="form-group">
                                <label>Profile Picture</label>
                                <input type="file" name="image_url" class="form-control">
                                <input type="hidden" name="old_file" value="{{ currentUser()->image_url }}">
                                @if (!empty(currentUser()->image_url))
                                    <div style="margin:10px 0;width:200px; height: 100px;">
                                        <img style="width:100%;height:100%;object-fit:contain;" src="{{ currentUser()->image_url}}">
                                    </div>
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


{{-- @extends('portal.master')

@section('content')
    <section class="main-content">
        <div class="row justify-content-center">
            <div class="col-md-8 col-lg-6">
                
                @include('portal.flash-message')
                
                <div class="profile-card">
                    <div class="card-header-section">
                        <div class="header-content">
                            <div class="avatar-container">
                                @if (!empty(currentUser()->image_url))
                                    <img src="{{ config('constants.storage_url') . currentUser()->image_url }}" alt="Profile" class="profile-avatar" id="currentAvatar">
                                @else
                                    <div class="avatar-placeholder">
                                        <i class="fas fa-user"></i>
                                    </div>
                                @endif
                                <div class="avatar-overlay" onclick="document.getElementById('profileImage').click()">
                                    <i class="fas fa-camera"></i>
                                </div>
                            </div>
                            <h3 class="user-name">{{ currentUser()->name }}</h3>
                            <p class="user-email">{{ currentUser()->email }}</p>
                        </div>
                    </div>
                    
                    <div class="card-body-section">
                        <form method="post" action="" enctype="multipart/form-data" id="profileForm">
                            {{ csrf_field() }}
                            
                            <div class="form-section">
                                <label class="section-label">Profile Picture</label>
                                <div class="file-upload-area">
                                    <input type="file" name="image_url" class="file-input" id="profileImage" accept="image/*">
                                    <div class="upload-placeholder">
                                        <i class="fas fa-cloud-upload-alt"></i>
                                        <p>Click to upload profile picture</p>
                                        <span>JPEG, PNG - Max 2MB</span>
                                    </div>
                                    <div class="image-preview" id="imagePreview">
                                        <img src="" alt="Preview" class="preview-image">
                                        <button type="button" class="remove-preview" onclick="removeImage()">
                                            <i class="fas fa-times"></i>
                                        </button>
                                    </div>
                                </div>
                                <input type="hidden" name="old_file" value="{{ currentUser()->image_url }}">
                            </div>

                            <div class="form-section">
                                <label class="section-label">Personal Information</label>
                                
                                <div class="input-group">
                                    <div class="input-icon">
                                        <i class="fas fa-user"></i>
                                    </div>
                                    <input type="text" value="{{ currentUser()->name }}" name="name" 
                                           class="modern-input" placeholder="Enter your full name">
                                </div>
                                
                                <div class="input-group">
                                    <div class="input-icon">
                                        <i class="fas fa-envelope"></i>
                                    </div>
                                    <input type="email" name="email" value="{{ currentUser()->email }}" 
                                           class="modern-input" placeholder="Enter your email address">
                                </div>
                                
                                <div class="input-group">
                                    <div class="input-icon">
                                        <i class="fas fa-mobile-alt"></i>
                                    </div>
                                    <input type="text" value="{{ currentUser()->mobile_no }}" name="mobile_no" 
                                           class="modern-input" placeholder="Enter your mobile number">
                                </div>
                            </div>
                            
                            <div class="form-actions">
                                <button type="submit" class="submit-btn">
                                    <i class="fas fa-save"></i>
                                    Update Profile
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
    .profile-card {
        background: white;
        border-radius: 20px;
        box-shadow: 0 10px 30px rgba(0,0,0,0.1);
        overflow: hidden;
        margin-bottom: 30px;
    }

    .card-header-section {
        background: linear-gradient(135deg, #A0C242 0%, #8daa3a 100%);
        padding: 40px 30px 30px;
        text-align: center;
        color: white;
        position: relative;
    }

    .avatar-container {
        position: relative;
        display: inline-block;
        margin-bottom: 15px;
    }

    .profile-avatar, .avatar-placeholder {
        width: 100px;
        height: 100px;
        border-radius: 50%;
        border: 4px solid rgba(255,255,255,0.3);
        object-fit: cover;
    }

    .avatar-placeholder {
        background: rgba(255,255,255,0.2);
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 40px;
    }

    .avatar-overlay {
        position: absolute;
        bottom: 5px;
        right: 5px;
        background: white;
        width: 32px;
        height: 32px;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        color: #A0C242;
        cursor: pointer;
        transition: all 0.3s ease;
    }

    .avatar-overlay:hover {
        transform: scale(1.1);
    }

    .user-name {
        font-size: 24px;
        font-weight: 600;
        margin: 0 0 5px 0;
    }

    .user-email {
        opacity: 0.9;
        margin: 0;
        font-size: 14px;
    }

    .card-body-section {
        padding: 40px;
    }

    .form-section {
        margin-bottom: 30px;
    }

    .section-label {
        display: block;
        font-weight: 600;
        color: #2c3e50;
        margin-bottom: 15px;
        font-size: 16px;
        border-left: 4px solid #A0C242;
        padding-left: 12px;
    }

    .file-upload-area {
        border: 2px dashed #e0e6ed;
        border-radius: 12px;
        padding: 30px;
        text-align: center;
        position: relative;
        transition: all 0.3s ease;
        background: #f8f9fa;
    }

    .file-upload-area:hover {
        border-color: #A0C242;
        background: #f0f7e6;
    }

    .file-upload-area.dragover {
        border-color: #A0C242;
        background: #e8f4d9;
    }

    .file-input {
        position: absolute;
        width: 100%;
        height: 100%;
        top: 0;
        left: 0;
        opacity: 0;
        cursor: pointer;
    }

    .upload-placeholder i {
        font-size: 48px;
        color: #A0C242;
        margin-bottom: 15px;
    }

    .upload-placeholder p {
        font-weight: 600;
        color: #2c3e50;
        margin: 0 0 8px 0;
    }

    .upload-placeholder span {
        color: #7e8c9a;
        font-size: 12px;
    }

    .image-preview {
        display: none;
        position: relative;
        max-width: 200px;
        margin: 0 auto;
    }

    .preview-image {
        width: 100%;
        height: 150px;
        object-fit: cover;
        border-radius: 10px;
        border: 2px solid #A0C242;
    }

    .remove-preview {
        position: absolute;
        top: -8px;
        right: -8px;
        background: #e74c3c;
        color: white;
        border: none;
        border-radius: 50%;
        width: 24px;
        height: 24px;
        font-size: 12px;
        cursor: pointer;
        display: flex;
        align-items: center;
        justify-content: center;
    }

    .input-group {
        position: relative;
        margin-bottom: 20px;
    }

    .input-icon {
        position: absolute;
        left: 15px;
        top: 50%;
        transform: translateY(-50%);
        color: #A0C242;
        z-index: 2;
    }

    .modern-input {
        width: 100%;
        padding: 15px 15px 15px 45px !important;
        border: 2px solid #e0e6ed;
        border-radius: 10px;
        font-size: 14px;
        transition: all 0.3s ease;
        background: white;
    }

    .modern-input:focus {
        border-color: #A0C242;
        box-shadow: 0 0 0 3px rgba(160, 194, 66, 0.1);
        outline: none;
    }

    .form-actions {
        text-align: center;
        margin-top: 30px;
    }

    .submit-btn {
        background: linear-gradient(135deg, #A0C242 0%, #8daa3a 100%);
        color: white;
        border: none;
        padding: 15px 40px;
        border-radius: 50px;
        font-weight: 600;
        font-size: 16px;
        cursor: pointer;
        transition: all 0.3s ease;
        display: inline-flex;
        align-items: center;
        gap: 10px;
        box-shadow: 0 4px 15px rgba(160, 194, 66, 0.3);
    }

    .submit-btn:hover {
        transform: translateY(-2px);
        box-shadow: 0 6px 20px rgba(160, 194, 66, 0.4);
    }

    @media (max-width: 768px) {
        .card-body-section {
            padding: 30px 20px;
        }
        
        .card-header-section {
            padding: 30px 20px 20px;
        }
    }
</style>
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const fileInput = document.getElementById('profileImage');
        const imagePreview = document.getElementById('imagePreview');
        const previewImage = imagePreview.querySelector('.preview-image');
        const uploadPlaceholder = document.querySelector('.upload-placeholder');
        const currentAvatar = document.getElementById('currentAvatar');

        fileInput.addEventListener('change', function() {
            const file = this.files[0];
            if (file) {
                const reader = new FileReader();
                
                reader.addEventListener('load', function() {
                    previewImage.src = reader.result;
                    uploadPlaceholder.style.display = 'none';
                    imagePreview.style.display = 'block';
                    
                    if (currentAvatar) {
                        currentAvatar.src = reader.result;
                    }
                });
                
                reader.readAsDataURL(file);
            }
        });

        const uploadArea = document.querySelector('.file-upload-area');
        
        ['dragenter', 'dragover', 'dragleave', 'drop'].forEach(eventName => {
            uploadArea.addEventListener(eventName, preventDefaults, false);
        });

        function preventDefaults(e) {
            e.preventDefault();
            e.stopPropagation();
        }

        ['dragenter', 'dragover'].forEach(eventName => {
            uploadArea.addEventListener(eventName, highlight, false);
        });

        ['dragleave', 'drop'].forEach(eventName => {
            uploadArea.addEventListener(eventName, unhighlight, false);
        });

        function highlight() {
            uploadArea.classList.add('dragover');
        }

        function unhighlight() {
            uploadArea.classList.remove('dragover');
        }

        uploadArea.addEventListener('drop', handleDrop, false);

        function handleDrop(e) {
            const dt = e.dataTransfer;
            const files = dt.files;
            fileInput.files = files;
            
            const event = new Event('change');
            fileInput.dispatchEvent(event);
        }
    });

    function removeImage() {
        const fileInput = document.getElementById('profileImage');
        const imagePreview = document.getElementById('imagePreview');
        const uploadPlaceholder = document.querySelector('.upload-placeholder');
        
        fileInput.value = '';
        imagePreview.style.display = 'none';
        uploadPlaceholder.style.display = 'block';
    }
</script>
@endsection --}}



@extends('portal.master')

@section('content')
    <section class="main-content">
        <div class="row justify-content-center">
            <div class="col-md-8 col-lg-6">

                @include('portal.flash-message')

                <div class="profile-card">
                    <div class="card-header-section">
                        <div class="header-content">
                            <div class="avatar-container">
                                {{-- @if (!empty(currentUser()->image_url))
                                    <img src="{{ config('constants.storage_url') . currentUser()->image_url }}" alt="Profile"
                                        class="profile-avatar" id="currentAvatar">
                                @else
                                    <div class="avatar-placeholder">
                                        Profile
                                    </div>
                                @endif
                                <div class="avatar-overlay" onclick="document.getElementById('profileImage').click()">
                                    Upload
                                </div> --}}
                            </div>
                            <h3 class="user-name">{{ currentUser()->name }}</h3>
                            <p class="user-email">{{ currentUser()->email }}</p>
                        </div>
                    </div>

                    <div class="card-body-section">
                        <form method="post" action="" enctype="multipart/form-data" id="profileForm">
                            {{ csrf_field() }}

                            <div class="form-section">
                                <label class="section-label">Profile Picture</label>
                                <div class="file-upload-area">
                                    <input type="file" name="image_url" class="file-input" id="profileImage"
                                        accept="image/*">
                                    <div class="upload-placeholder">
                                        <p>Click to upload profile picture</p>
                                        <span>JPEG, PNG - Max 2MB</span>
                                    </div>
                                    <div class="image-preview" id="imagePreview">
                                        <img src="" alt="Preview" class="preview-image">
                                        <button type="button" class="remove-preview" onclick="removeImage()">
                                            ×
                                        </button>
                                    </div>
                                </div>
                                <input type="hidden" name="old_file" value="{{ currentUser()->image_url }}">
                            </div>

                            <div class="form-section">
                                <label class="section-label">Personal Information</label>

                                <div class="input-group">
                                    <input type="text" value="{{ currentUser()->name }}" name="name"
                                        class="modern-input" placeholder="Enter your full name">
                                </div>

                                <div class="input-group">
                                    <input type="email" name="email" value="{{ currentUser()->email }}"
                                        class="modern-input" placeholder="Enter your email address">
                                </div>

                                <div class="input-group">
                                    <input type="text" value="{{ currentUser()->mobile_no }}" name="mobile_no"
                                        class="modern-input" placeholder="Enter your mobile number">
                                </div>
                            </div>

                            <div class="form-actions">
                                <button type="submit" class="submit-btn">
                                    Update Profile
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
        /* --- Same UI Theme --- */
        .main-content {
            background: #f8faf9;
            min-height: 100vh;
            padding: 30px;
            padding-top: 90px;
        }

        .profile-card {
            background: #ffffff;
            border: 1px solid #e5e7eb;
            border-radius: 12px;
            box-shadow: 0 4px 20px rgba(160, 194, 66, 0.1);
            overflow: hidden;
            margin-bottom: 30px;
        }

        .card-header-section {
            background: #ffffff;
            border-bottom: 1px solid #e5e7eb;
            padding: 30px 30px 20px;
            text-align: center;
            color: #1f2937;
        }

        .avatar-container {
            position: relative;
            display: inline-block;
            margin-bottom: 15px;
        }

        .profile-avatar,
        .avatar-placeholder {
            width: 100px;
            height: 100px;
            border-radius: 50%;
            border: 3px solid #e5e7eb;
            object-fit: cover;
        }

        .avatar-placeholder {
            background: #f3f4f6;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 16px;
            font-weight: 600;
            color: #6b7280;
        }

        .avatar-overlay {
            position: absolute;
            bottom: 0;
            right: 0;
            background: #9FC23F;
            color: white;
            width: 32px;
            height: 32px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            cursor: pointer;
            transition: all 0.3s ease;
            font-size: 12px;
            font-weight: 600;
            border: 2px solid white;
        }

        .avatar-overlay:hover {
            background: #8AB933;
            transform: scale(1.1);
        }

        .user-name {
            font-size: 18px;
            font-weight: 600;
            margin: 0 0 5px 0;
            color: #1f2937;
        }

        .user-email {
            color: #6b7280;
            margin: 0;
            font-size: 14px;
        }

        .card-body-section {
            padding: 30px;
        }

        .form-section {
            margin-bottom: 25px;
        }

        .section-label {
            display: block;
            font-weight: 600;
            color: #2c3e50;
            margin-bottom: 15px;
            font-size: 15px;
            padding-left: 0;
            border-left: none;
        }

        .file-upload-area {
            border: 2px dashed #dce4e0;
            border-radius: 8px;
            padding: 25px;
            text-align: center;
            position: relative;
            transition: all 0.3s ease;
            background: #fff;
        }

        .file-upload-area:hover {
            border-color: #A0C242;
            background: #f8faf9;
        }

        .file-upload-area.dragover {
            border-color: #A0C242;
            background: #f0f7e6;
        }

        .file-input {
            position: absolute;
            width: 100%;
            height: 100%;
            top: 0;
            left: 0;
            opacity: 0;
            cursor: pointer;
        }

        .upload-placeholder p {
            font-weight: 500;
            color: #2c3e50;
            margin: 0 0 8px 0;
            font-size: 14px;
        }

        .upload-placeholder span {
            color: #7f8c8d;
            font-size: 12px;
        }

        .image-preview {
            display: none;
            position: relative;
            max-width: 200px;
            margin: 0 auto;
        }

        .preview-image {
            width: 100%;
            height: 150px;
            object-fit: cover;
            border-radius: 8px;
            border: 2px solid #A0C242;
        }

        .remove-preview {
            position: absolute;
            top: -8px;
            right: -8px;
            background: #e74c3c;
            color: white;
            border: none;
            border-radius: 50%;
            width: 24px;
            height: 24px;
            font-size: 14px;
            cursor: pointer;
            display: flex;
            align-items: center;
            justify-content: center;
            font-weight: bold;
        }

        .input-group {
            position: relative;
            margin-bottom: 20px;
        }

        .modern-input {
            width: 100%;
            padding: 12px 15px !important;
            border: 1px solid #dce4e0;
            border-radius: 6px;
            font-size: 14px;
            transition: all 0.3s ease;
            background: white;
            color: #1f2937;
        }

        .modern-input:focus {
            border-color: #A0C242;
            box-shadow: 0 0 0 3px rgba(160, 194, 66, 0.1);
            outline: none;
        }

        .form-actions {
            text-align: center;
            margin-top: 25px;
            padding-top: 20px;
            border-top: 1px solid #eaeaea;
        }

        .submit-btn {
            background: linear-gradient(135deg, #A0C242 0%, #8AB933 100%);
            color: white;
            border: none;
            padding: 10px 30px;
            border-radius: 6px;
            font-weight: 600;
            font-size: 14px;
            cursor: pointer;
            transition: all 0.2s ease;
            box-shadow: 0 2px 8px rgba(160, 194, 66, 0.3);
        }

        .submit-btn:hover {
            transform: translateY(-1px);
            box-shadow: 0 4px 12px rgba(160, 194, 66, 0.4);
        }

        @media (max-width: 768px) {
            .main-content {
                padding: 15px;
                padding-top: 90px;
            }

            .card-body-section {
                padding: 20px;
            }

            .card-header-section {
                padding: 20px;
            }
        }
    </style>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const fileInput = document.getElementById('profileImage');
            const imagePreview = document.getElementById('imagePreview');
            const previewImage = imagePreview.querySelector('.preview-image');
            const uploadPlaceholder = document.querySelector('.upload-placeholder');
            const currentAvatar = document.getElementById('currentAvatar');

            fileInput.addEventListener('change', function() {
                const file = this.files[0];
                if (file) {
                    const reader = new FileReader();

                    reader.addEventListener('load', function() {
                        previewImage.src = reader.result;
                        uploadPlaceholder.style.display = 'none';
                        imagePreview.style.display = 'block';

                        if (currentAvatar) {
                            currentAvatar.src = reader.result;
                        }
                    });

                    reader.readAsDataURL(file);
                }
            });

            const uploadArea = document.querySelector('.file-upload-area');

            ['dragenter', 'dragover', 'dragleave', 'drop'].forEach(eventName => {
                uploadArea.addEventListener(eventName, preventDefaults, false);
            });

            function preventDefaults(e) {
                e.preventDefault();
                e.stopPropagation();
            }

            ['dragenter', 'dragover'].forEach(eventName => {
                uploadArea.addEventListener(eventName, highlight, false);
            });

            ['dragleave', 'drop'].forEach(eventName => {
                uploadArea.addEventListener(eventName, unhighlight, false);
            });

            function highlight() {
                uploadArea.classList.add('dragover');
            }

            function unhighlight() {
                uploadArea.classList.remove('dragover');
            }

            uploadArea.addEventListener('drop', handleDrop, false);

            function handleDrop(e) {
                const dt = e.dataTransfer;
                const files = dt.files;
                fileInput.files = files;

                const event = new Event('change');
                fileInput.dispatchEvent(event);
            }
        });

        function removeImage() {
            const fileInput = document.getElementById('profileImage');
            const imagePreview = document.getElementById('imagePreview');
            const uploadPlaceholder = document.querySelector('.upload-placeholder');

            fileInput.value = '';
            imagePreview.style.display = 'none';
            uploadPlaceholder.style.display = 'block';
        }
    </script>
@endsection
