{{-- @extends('portal.auth.master')
@section('content')
    <div class="col-5">
        @include('portal.flash-message')
        @include('portal.auth.header')
        <div class="misc-box">
            <form method="post" action="" role="form">
                {{ csrf_field() }}
                <div class="form-group">
                    <label  for="exampleuser1">Email</label>
                    <div class="group-icon">
                        <input id="exampleuser1" type="email" name="email" placeholder="Email" class="form-control" required="">
                        <span class="icon-user text-muted icon-input"></span>
                    </div>
                </div>
                <div class="form-group">
                    <label for="exampleInputPassword1">Password</label>
                    <div class="group-icon">
                        <input id="exampleInputPassword1" type="password" name="password" placeholder="Password" class="form-control">
                        <span class="icon-lock text-muted icon-input"></span>
                    </div>
                </div>
                <div class="clearfix">
                    <div class="float-right">
                        <button type="submit" class="btn btn-block btn-primary btn-rounded box-shadow">Login</button>
                    </div>
                </div>
                <hr>
                <p class="text-center">
                    <a href="{{ route('admin.forgot-password') }}">Forgot Password?</a>
                </p>
            </form>
        </div>
        @include('portal.auth.footer')
    </div>
@endsection
 --}}




{{-- @extends('portal.auth.master')
@section('content')
    <div class="col-5">
        @include('portal.flash-message')
        @include('portal.auth.header')
        <div class="misc-box">
            <form method="post" action="" role="form" class="login-form">
                {{ csrf_field() }}
                <div class="form-group">
                    <label for="exampleuser1">Email Address</label>
                    <div class="input-group-custom">
                        <div class="input-icon">
                            <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                <path d="M4 4h16c1.1 0 2 .9 2 2v12c0 1.1-.9 2-2 2H4c-1.1 0-2-.9-2-2V6c0-1.1.9-2 2-2z"></path>
                                <polyline points="22,6 12,13 2,6"></polyline>
                            </svg>
                        </div>
                        <input id="exampleuser1" type="email" name="email" placeholder="Enter your email" class="form-control" required>
                    </div>
                </div>
                <div class="form-group">
                    <label for="exampleInputPassword1">Password</label>
                    <div class="input-group-custom">
                        <div class="input-icon">
                            <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                <rect x="3" y="11" width="18" height="11" rx="2" ry="2"></rect>
                                <path d="M7 11V7a5 5 0 0 1 10 0v4"></path>
                            </svg>
                        </div>
                        <input id="exampleInputPassword1" type="password" name="password" placeholder="Enter your password" class="form-control" required>
                    </div>
                </div>
                <div class="form-group remember-forgot">
                    <div class="form-check">
                        <input type="checkbox" class="form-check-input cust-check" id="rememberMe">
                        <label class="form-check-label" for="rememberMe">Remember me</label>
                    </div>
                    <div class="forgot-password">
                        <a href="{{ route('admin.forgot-password') }}">Forgot Password?</a>
                    </div>
                </div>
                <div class="form-group">
                    <button type="submit" class="btn btn-primary btn-login">Sign In</button>
                </div>
            </form>
        </div>
        @include('portal.auth.footer')
    </div>

    <style>
        /* Custom CSS for enhanced login UI */
        .misc-box {
            background: #ffffff;
            border-radius: 16px;
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.08);
            padding: 40px;
            margin: 20px 0;
            border: 1px solid #f0f0f0;
            transition: transform 0.3s ease, box-shadow 0.3s ease;
        }

        .misc-box:hover {
            transform: translateY(-5px);
            box-shadow: 0 15px 40px rgba(0, 0, 0, 0.12);
        }

        .login-form .form-group {
            margin-bottom: 24px;
        }

        .login-form label {
            font-weight: 600;
            color: #2d3748;
            display: block;
            font-size: 14px;
        }

        .input-group-custom {
            position: relative;
            display: flex;
            align-items: center;
        }

        .input-icon {
            position: absolute;
            left: 15px;
            z-index: 2;
            color: #a0aec0;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .login-form .form-control {
            width: 100%;
            padding: 14px 15px 14px 50px;
            border: 1px solid #e2e8f0;
            border-radius: 10px;
            font-size: 15px;
            transition: all 0.3s ease;
            background-color: #fafafa;
            color: #4a5568;
        }

        .login-form .form-control:focus {
            border-color: #4299e1;
            box-shadow: 0 0 0 3px rgba(66, 153, 225, 0.15);
            background-color: #ffffff;
            outline: none;
        }

        .remember-forgot {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 30px;
        }

        .form-check {
            display: flex;
            align-items: center;
        }

        .form-check-input {
            margin-right: 8px;
            width: 18px;
            height: 18px;
            border-radius: 4px;
            border: 1px solid #cbd5e0;
            cursor: pointer;
        }

        .form-check-input:checked {
            background-color: #4299e1;
            border-color: #4299e1;
        }

        .form-check-label {
            font-size: 14px;
            color: #4a5568;
            cursor: pointer;
        }

        .forgot-password a {
            color: #4299e1;
            text-decoration: none;
            font-size: 14px;
            font-weight: 500;
            transition: color 0.2s ease;
        }

        .forgot-password a:hover {
            color: #3182ce;
            text-decoration: underline;
        }

        .btn-login {
            width: 100%;
            padding: 14px;
            background: #a0c242 !important;
            border: none;
            border-radius: 10px;
            font-size: 16px;
            font-weight: 600;
            color: white;
            cursor: pointer;
            transition: all 0.3s ease;
            box-shadow: 0 4px 10px rgba(66, 153, 225, 0.3);
        }

        .btn-login:hover {
            transform: translateY(-2px);
            box-shadow: 0 6px 15px rgba(66, 153, 225, 0.4);
            background: #8CAB38 !important;
        }

        .divider {
            display: flex;
            align-items: center;
            margin: 30px 0;
            color: #a0aec0;
            font-size: 14px;
        }

        .divider::before,
        .divider::after {
            content: "";
            flex: 1;
            height: 1px;
            background: #e2e8f0;
        }

        .divider span {
            padding: 0 15px;
        }

        .social-login {
            display: flex;
            gap: 12px;
        }

        .btn-social {
            flex: 1;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 8px;
            padding: 12px;
            border: 1px solid #e2e8f0;
            border-radius: 10px;
            background: white;
            color: #4a5568;
            font-weight: 500;
            font-size: 14px;
            cursor: pointer;
            transition: all 0.2s ease;
        }

        .btn-social:hover {
            border-color: #cbd5e0;
            background: #f7fafc;
            transform: translateY(-1px);
        }

        .btn-google:hover {
            border-color: #e53e3e;
            background: #fff5f5;
        }

        .btn-facebook:hover {
            border-color: #4299e1;
            background: #ebf8ff;
        }
        .cust-check{
            margin-left: -0.25rem;
        }
        /* Responsive adjustments */
        @media (max-width: 768px) {
            .col-5 {
                width: 100% !important;
                max-width: 500px;
                margin: 0 auto;
            }
            
            .misc-box {
                padding: 30px 25px;
            }
            
            .social-login {
                flex-direction: column;
            }
            
            .remember-forgot {
                flex-direction: column;
                align-items: flex-start;
                gap: 15px;
            }
            
            .forgot-password {
                align-self: flex-end;
            }
        }

        /* Animation for form elements */
        @keyframes fadeIn {
            from { opacity: 0; transform: translateY(10px); }
            to { opacity: 1; transform: translateY(0); }
        }

        .form-group {
            animation: fadeIn 0.5s ease forwards;
        }

        .form-group:nth-child(1) { animation-delay: 0.1s; }
        .form-group:nth-child(2) { animation-delay: 0.2s; }
        .remember-forgot { animation-delay: 0.3s; }
        .btn-login { animation-delay: 0.4s; }
        .divider { animation-delay: 0.5s; }
        .social-login { animation-delay: 0.6s; }
    </style>
@endsection --}}


@extends('portal.auth.master')

@section('content')
    <div>
        @include('portal.flash-message')
        <div class="login-container">
            <div class="login-card">
                <!-- Left Section with Gradient Background -->
                <div class="login-left">
                    <div class="brand-section">
                        <div class="brand-logo">
                            <div class="logo-icon">
                                <img src="https://kiwiticketing.com/wp-content/uploads/2024/09/Kiwi-Ticketing-logo-1.png"
                                    alt="">
                            </div>

                        </div>
                        <div>
                            <h1 class="brand-heading">Internal Management Dashboard</h1>
                            <p class="brand-subtitle">
                                Manage users, roles, companies and permissions securely and efficiently.
                            </p>
                        </div>
                    </div>
                </div>

                <!-- Right Section with Login Form -->
                <div class="login-right">
                    <div class="welcome-section">
                        <h2 class="welcome-heading">Welcome Back!</h2>
                        <p class="welcome-subtext">Please sign in to access your dashboard.</p>

                        <form method="post" action="" role="form" class="login-form">
                            {{ csrf_field() }}
                            {{-- @include('portal.flash-message') --}}


                            <div class="form-group">
                                <label for="email" class="form-label">Email Address</label>
                                <div class="input-group-custom">
                                    <div class="input-icon">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20"
                                            viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
                                            stroke-linecap="round" stroke-linejoin="round">
                                            <path
                                                d="M4 4h16c1.1 0 2 .9 2 2v12c0 1.1-.9 2-2 2H4c-1.1 0-2-.9-2-2V6c0-1.1.9-2 2-2z">
                                            </path>
                                            <polyline points="22,6 12,13 2,6"></polyline>
                                        </svg>
                                    </div>
                                    <input id="email" type="email" name="email" placeholder="Enter your email"
                                        class="form-control" required>
                                </div>
                            </div>

                            <div class="form-group">
                                <label for="password" class="form-label">Password</label>
                                <div class="input-group-custom">
                                    <div class="input-icon">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20"
                                            viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
                                            stroke-linecap="round" stroke-linejoin="round">
                                            <rect x="3" y="11" width="18" height="11" rx="2" ry="2">
                                            </rect>
                                            <path d="M7 11V7a5 5 0 0 1 10 0v4"></path>
                                        </svg>
                                    </div>
                                    <input id="password" type="password" name="password" placeholder="Enter your password"
                                        class="form-control" required>
                                </div>
                            </div>

                            <div class="form-group remember-forgot">
                                <div class="form-check">
                                    <input type="checkbox" class="form-check-input" id="rememberMe">
                                    <label class="form-check-label" for="rememberMe">Remember me</label>
                                </div>
                                <div class="forgot-password">
                                    <a href="{{ route('admin.forgot-password') }}">Forgot Password?</a>
                                </div>
                            </div>

                            <div class="form-group">
                                <button type="submit" class="btn-login">Sign In</button>
                            </div>
                        </form>

                        <div class="terms-text">
                            By signing in, you agree to our <a href="#" class="terms-link">Terms of Service</a>
                            and <a href="#" class="terms-link">Privacy Policy</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <style>
        /* Reset and Base Styles */
        body {
            padding: 0;
        }

        .misc-wrapper {
            padding: 0;
        }

        .login-container {
            min-height: 68vh;
            display: flex;
            align-items: center;
            justify-content: center;
            background: linear-gradient(135deg, #f5f7fa 0%, #f0f4f8 100%);
            padding: 20px;
            /* font-family: 'Inter', -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, Oxygen, Ubuntu, sans-serif; */
        }

        /* Login Card Container */
        .login-card {
            display: flex;
            width: 100%;
            max-width: 1000px;
            height: 600px;
            background: white;
            border-radius: 24px;
            box-shadow: 0 20px 60px rgba(0, 0, 0, 0.08), 0 5px 20px rgba(0, 0, 0, 0.04);
            overflow: hidden;
        }

        /* Left Section */
        .login-left {
            flex: 1;
            background: linear-gradient(135deg,
                    rgba(159, 194, 63, 0.1),
                    rgba(59, 130, 246, 0.1));
            padding: 60px 40px;
            display: flex;
            align-items: end;
            justify-content: center;
        }

        .brand-section {
            text-align: center;
            max-width: 400px;
        }

        .brand-logo {
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 12px;
            margin-bottom: 40px;
        }

        .logo-icon img {
            width: 300px;
        }

        .brand-text {
            font-size: 28px;
            font-weight: 700;
            color: #1a202c;
            letter-spacing: -0.5px;
        }

        .brand-heading {
            font-size: 20px;
            font-weight: 700;
            color: #1a202c;
            margin-bottom: 20px;
            line-height: 28px;
            letter-spacing: -0.5px;
        }

        .brand-subtitle {
            font-size: 14px;
            color: #6b7280;
            line-height: 20px;
            font-weight: 400;
        }

        /* Right Section */
        .login-right {
            flex: 1;
            background: white;
            padding: 60px 50px;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .welcome-section {
            width: 100%;
            max-width: 400px;
        }

        .welcome-heading {
            font-size: 24px;
            font-weight: 700;
            color: #1a202c;
            margin-bottom: 12px;
            text-align: center;
            letter-spacing: -0.5px;
        }

        .welcome-subtext {
            font-size: 14px;
            text-align: center;
            color: #6b7280;
            margin-bottom: 40px;
            line-height: 20px;
        }

        /* Login Form Styles */
        .login-form {
            margin-bottom: 30px;
        }

        .form-group {
            margin-bottom: 24px;
        }

        .form-label {
            display: block;
            font-weight: 600;
            color: #2d3748;
            margin-bottom: 8px;
            font-size: 14px;
        }

        .input-group-custom {
            position: relative;
            display: flex;
            align-items: center;
        }

        .input-icon {
            position: absolute;
            left: 16px;
            z-index: 2;
            color: #a0aec0;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .form-control {
            width: 100%;
            padding: 14px 15px 14px 48px !important;
            border: 1px solid #e2e8f0;
            border-radius: 12px !important;
            font-size: 15px;
            transition: all 0.3s ease;
            background-color: #fafafa;
            color: #4a5568;
            height: 48px !important;
        }

        .form-control:focus {
            border-color: #4299e1;
            box-shadow: 0 0 0 3px rgba(66, 153, 225, 0.15);
            background-color: #ffffff;
            outline: none;
        }

        .form-control::placeholder {
            color: #a0aec0;
        }

        /* Remember Me & Forgot Password */
        .remember-forgot {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 30px;
        }

        .form-check {
            display: flex;
            align-items: center;
        }

        .form-check-input {
            margin-right: 8px;
            margin-left: 0;
            width: 18px;
            height: 18px;
            border-radius: 4px;
            border: 1px solid #cbd5e0;
            cursor: pointer;
            accent-color: #a0c242;
        }

        .form-check-input:checked {
            background-color: #a0c242;
            border-color: #a0c242;
        }

        .form-check-label {
            font-size: 14px;
            color: #4a5568;
            cursor: pointer;
            font-weight: 500;
        }

        .forgot-password a {
            color: #4299e1;
            text-decoration: none;
            font-size: 14px;
            font-weight: 500;
            transition: color 0.2s ease;
        }

        .forgot-password a:hover {
            color: #3182ce;
            text-decoration: underline;
        }

        /* Login Button */
        .btn-login {
            width: 100%;
            padding: 14px;
            background: #a0c242;
            border: none;
            border-radius: 12px;
            font-size: 16px;
            font-weight: 600;
            color: white;
            cursor: pointer;
            transition: all 0.3s ease;
            box-shadow: 0 4px 15px rgba(160, 194, 66, 0.3);
            height: 50px;
            margin-top: 10px;
        }

        .btn-login:hover {
            background: #8CAB38;
            transform: translateY(-2px);
            box-shadow: 0 8px 20px rgba(160, 194, 66, 0.4);
        }

        .btn-login:active {
            transform: translateY(0);
            box-shadow: 0 2px 10px rgba(160, 194, 66, 0.3);
        }

        /* Terms Text */
        .terms-text {
            text-align: center;
            font-size: 14px;
            color: #9ca3af;
            line-height: 1.5;
            padding-top: 20px;
            border-top: 1px solid #e2e8f0;
            margin-top: 20px;
        }

        .terms-link {
            color: #4299e1;
            text-decoration: none;
            font-weight: 500;
            transition: color 0.2s ease;
        }

        .terms-link:hover {
            color: #3182ce;
            text-decoration: underline;
        }

        .brand-section {
            height: 280px;
            display: flex;
            flex-direction: column;
            justify-content: space-between;
        }

        /* Responsive Design */
        @media (max-width: 900px) {
            .login-card {
                flex-direction: column;
                height: auto;
                max-width: 480px;
            }

            .login-left {
                padding: 40px 30px;
            }

            .login-right {
                padding: 40px 30px;
            }

            .brand-heading {
                font-size: 28px;
            }

            .welcome-heading {
                font-size: 28px;
            }
        }

        @media (max-width: 480px) {
            .login-container {
                padding: 15px;
            }

            .login-card {
                border-radius: 20px;
            }

            .login-left {
                padding: 30px 20px;
            }

            .login-right {
                padding: 30px 20px;
            }

            .brand-heading {
                font-size: 24px;
            }

            .welcome-heading {
                font-size: 24px;
            }

            .brand-text {
                font-size: 24px;
            }

            .remember-forgot {
                flex-direction: column;
                align-items: flex-start;
                gap: 15px;
            }

            .forgot-password {
                align-self: flex-start;
            }
        }

        /* Animation for subtle entrance */
        @keyframes fadeInUp {
            from {
                opacity: 0;
                transform: translateY(20px);
            }

            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        .brand-section {
            animation: fadeInUp 0.6s ease-out;
        }

        .welcome-section {
            animation: fadeInUp 0.6s ease-out 0.2s both;
        }

        .form-group {
            animation: fadeInUp 0.5s ease forwards;
        }

        .form-group:nth-child(1) {
            animation-delay: 0.3s;
        }

        .form-group:nth-child(2) {
            animation-delay: 0.4s;
        }

        .remember-forgot {
            animation-delay: 0.5s;
        }

        .btn-login {
            animation-delay: 0.6s;
        }

        .terms-text {
            animation-delay: 0.7s;
        }
    </style>
@endsection
