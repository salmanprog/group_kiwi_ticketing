{{-- @extends('portal.master')

@section('content')
    <section class="main-content">
        <div class="row justify-content-center">
            <div class="col-md-8 col-lg-6">
                
                @include('portal.flash-message')
                
                <div class="password-card">
                    <div class="card-header-section">
                        <div class="header-content">
                            <div class="password-icon">
                                <i class="fas fa-lock"></i>
                            </div>
                            <h3 class="page-title">Change Password</h3>
                            <p class="page-subtitle">Update your account password</p>
                        </div>
                    </div>
                    
                    <div class="card-body-section">
                        <form method="post" action="" id="passwordForm">
                            {{ csrf_field() }}
                            
                            <div class="form-section">
                                <div class="input-group">
                                    <div class="input-icon">
                                        <i class="fas fa-key"></i>
                                    </div>
                                    <input type="password" required name="current_password" 
                                           class="modern-input" 
                                           placeholder="Enter current password"
                                           id="currentPassword">
                                    <div class="password-toggle" onclick="togglePassword('currentPassword')">
                                        <i class="fas fa-eye"></i>
                                    </div>
                                </div>
                                
                                <div class="input-group">
                                    <div class="input-icon">
                                        <i class="fas fa-lock"></i>
                                    </div>
                                    <input type="password" required name="new_password" 
                                           class="modern-input" 
                                           placeholder="Enter new password"
                                           id="newPassword">
                                    <div class="password-toggle" onclick="togglePassword('newPassword')">
                                        <i class="fas fa-eye"></i>
                                    </div>
                                </div>
                                
                                <div class="input-group">
                                    <div class="input-icon">
                                        <i class="fas fa-lock"></i>
                                    </div>
                                    <input type="password" required name="confirm_password" 
                                           class="modern-input" 
                                           placeholder="Confirm new password"
                                           id="confirmPassword">
                                    <div class="password-toggle" onclick="togglePassword('confirmPassword')">
                                        <i class="fas fa-eye"></i>
                                    </div>
                                </div>
                                
                                <div class="password-strength" id="passwordStrength">
                                    <div class="strength-bar">
                                        <div class="strength-fill" id="strengthFill"></div>
                                    </div>
                                    <div class="strength-text" id="strengthText">Password strength</div>
                                </div>
                                
                                <div class="password-requirements">
                                    <p class="requirements-title">Password must contain:</p>
                                    <ul class="requirements-list">
                                        <li id="reqLength" class="requirement-item">
                                            <i class="fas fa-circle"></i>
                                            <span>At least 8 characters</span>
                                        </li>
                                        <li id="reqUppercase" class="requirement-item">
                                            <i class="fas fa-circle"></i>
                                            <span>One uppercase letter</span>
                                        </li>
                                        <li id="reqLowercase" class="requirement-item">
                                            <i class="fas fa-circle"></i>
                                            <span>One lowercase letter</span>
                                        </li>
                                        <li id="reqNumber" class="requirement-item">
                                            <i class="fas fa-circle"></i>
                                            <span>One number</span>
                                        </li>
                                        <li id="reqSpecial" class="requirement-item">
                                            <i class="fas fa-circle"></i>
                                            <span>One special character</span>
                                        </li>
                                    </ul>
                                </div>
                            </div>
                            
                            <div class="form-actions">
                                <button type="submit" class="submit-btn">
                                    <i class="fas fa-save"></i>
                                    Update Password
                                </button>
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
            <div class="col-md-8 col-lg-6">

                @include('portal.flash-message')

                <div class="password-card">
                    <div class="card-header-section">
                        <div class="header-content">
                            {{-- <div class="password-icon">
                                Change Password
                            </div> --}}
                            <h3 class="page-title">Change Password</h3>
                            <p class="page-subtitle">Update your account password</p>
                        </div>
                    </div>

                    <div class="card-body-section">
                        <form method="post" action="">
                            {{ csrf_field() }}

                            <div class="form-section">
                                <div class="input-group">
                                    <input type="password" required name="current_password" class="modern-input"
                                        placeholder="Enter current password">
                                </div>

                                <div class="input-group">
                                    <input type="password" required name="new_password" class="modern-input"
                                        placeholder="Enter new password">
                                </div>

                                <div class="input-group">
                                    <input type="password" required name="confirm_password" class="modern-input"
                                        placeholder="Confirm new password">
                                </div>

                                <div class="password-tips">
                                    <p class="tips-title">Password Tips:</p>
                                    <ul class="tips-list">
                                        <li class="tip-item">
                                            <span> Use at least 8 characters</span>
                                        </li>
                                        <li class="tip-item">
                                            <span> Include uppercase and lowercase letters</span>
                                        </li>
                                        <li class="tip-item">
                                            <span> Add numbers and special characters</span>
                                        </li>
                                        <li class="tip-item">
                                            <span> Avoid common words and patterns</span>
                                        </li>
                                    </ul>
                                </div>
                            </div>

                            <div class="form-actions">
                                <button type="submit" class="submit-btn">
                                    Update Password
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

        .password-card {
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

        .password-icon {
            width: 60px;
            height: 60px;
            background: #f3f4f6;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0 auto 15px;
            font-size: 14px;
            font-weight: 600;
            border: 2px solid #e5e7eb;
            color: #374151;
        }

        .page-title {
            font-size: 18px;
            font-weight: 600;
            margin: 0 0 5px 0;
            color: #1f2937;
        }

        .page-subtitle {
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

        .input-group {
            position: relative;
            margin-bottom: 20px;
        }

        .modern-input {
            width: 100%;
            padding: 12px 15px;
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

        .password-tips {
            background: #ffffff;
            border: 1px solid #eaeaea;
            border-radius: 8px;
            padding: 20px;
            margin-top: 25px;
        }

        .tips-title {
            font-weight: 600;
            color: #2c3e50;
            margin: 0 0 15px 0;
            font-size: 14px;
        }

        .tips-list {
            list-style: none;
            padding: 0;
            margin: 0;
        }

        .tip-item {
            margin-bottom: 10px;
            font-size: 13px;
            color: #5a6c7d;
            padding-left: 20px;
            position: relative;
        }

        .tip-item:before {
            content: '✓';
            position: absolute;
            left: 0;
            color: #A0C242;
            font-weight: bold;
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

            .password-icon {
                width: 50px;
                height: 50px;
                font-size: 12px;
            }

            .page-title {
                font-size: 16px;
            }
        }
    </style>
@endsection
