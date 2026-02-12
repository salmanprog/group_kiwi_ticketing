@extends('portal.auth.master')

@section('content')
    <div>
        @include('portal.flash-message')
        <div class="login-container">
            <div class="login-card">
                <!-- Left Section with Brand -->
                <div class="login-left">
                    <div class="brand-section">
                        <div class="brand-logo">
                            <div class="logo-icon">
                                <img src="https://kiwiticketing.com/wp-content/uploads/2024/09/Kiwi-Ticketing-logo-1.png" alt="">
                            </div>
                        </div>
                        <div>
                            <h1 class="brand-heading">Business Operations System</h1>
                            <p class="brand-subtitle">
                                Manage groups, estimates, contracts, invoices, and related operational workflows in one centralized platform.
                            </p>
                        </div>
                    </div>
                </div>

                <!-- Right Section: Choose login method -->
                <div class="login-right">
                    <div class="welcome-section">
                        <h2 class="welcome-heading">Sign in</h2>
                        <p class="welcome-subtext">Choose how you want to sign in to your account.</p>

                        <div class="login-options">
                            <a href="{{ route('client.login') }}" class="login-option-card login-option-laravel">
                                <span class="login-option-icon">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="28" height="28" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                        <path d="M4 4h16c1.1 0 2 .9 2 2v12c0 1.1-.9 2-2 2H4c-1.1 0-2-.9-2-2V6c0-1.1.9-2 2-2z"></path>
                                        <polyline points="22,6 12,13 2,6"></polyline>
                                    </svg>
                                </span>
                                <span class="login-option-title">Email & Password</span>
                                <span class="login-option-desc">Sign in with your email and password</span>
                            </a>

                            <a href="{{ route('admin.login') }}" class="login-option-card login-option-auth0">
                                <span class="login-option-icon">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="28" height="28" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                        <path d="M15 3h4a2 2 0 0 1 2 2v14a2 2 0 0 1-2 2h-4"></path>
                                        <polyline points="10 17 15 12 10 7"></polyline>
                                        <line x1="15" y1="12" x2="3" y2="12"></line>
                                    </svg>
                                </span>
                                <span class="login-option-title">Auth0 / SSO</span>
                                <span class="login-option-desc">Sign in with your organization account</span>
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <style>
        body { padding: 0; }
        .misc-wrapper { padding: 0; }
        .login-container {
            min-height: 68vh;
            display: flex;
            align-items: center;
            justify-content: center;
            background: linear-gradient(135deg, #f5f7fa 0%, #f0f4f8 100%);
            padding: 20px;
        }
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
        .login-left {
            flex: 1;
            background: linear-gradient(135deg, rgba(159, 194, 63, 0.1), rgba(59, 130, 246, 0.1));
            padding: 60px 40px;
            display: flex;
            align-items: end;
            justify-content: center;
        }
        .brand-section {
            text-align: center;
            max-width: 400px;
            height: 280px;
            display: flex;
            flex-direction: column;
            justify-content: space-between;
        }
        .brand-logo { display: flex; align-items: center; justify-content: center; gap: 12px; margin-bottom: 40px; }
        .logo-icon img { width: 300px; }
        .brand-heading {
            font-size: 20px;
            font-weight: 700;
            color: #1a202c;
            margin-bottom: 20px;
            line-height: 28px;
            letter-spacing: -0.5px;
        }
        .brand-subtitle { font-size: 14px; color: #6b7280; line-height: 20px; font-weight: 400; }
        .login-right {
            flex: 1;
            background: white;
            padding: 60px 50px;
            display: flex;
            align-items: center;
            justify-content: center;
        }
        .welcome-section { width: 100%; max-width: 400px; }
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
            margin-bottom: 32px;
            line-height: 20px;
        }
        .login-options { display: flex; flex-direction: column; gap: 16px; }
        .login-option-card {
            display: flex;
            align-items: center;
            gap: 16px;
            padding: 20px 24px;
            border: 2px solid #e2e8f0;
            border-radius: 16px;
            text-decoration: none;
            color: #1a202c;
            transition: all 0.25s ease;
            background: #fafafa;
        }
        .login-option-card:hover {
            border-color: #a0c242;
            background: rgba(160, 194, 66, 0.06);
            transform: translateY(-2px);
            box-shadow: 0 8px 24px rgba(0, 0, 0, 0.06);
        }
        .login-option-icon {
            flex-shrink: 0;
            width: 52px;
            height: 52px;
            border-radius: 12px;
            display: flex;
            align-items: center;
            justify-content: center;
            background: white;
            border: 1px solid #e2e8f0;
            color: #4a5568;
        }
        .login-option-laravel:hover .login-option-icon { background: rgba(160, 194, 66, 0.15); color: #8CAB38; border-color: #a0c242; }
        .login-option-auth0:hover .login-option-icon { background: rgba(66, 153, 225, 0.15); color: #3182ce; border-color: #4299e1; }
        .login-option-title { font-size: 17px; font-weight: 600; display: block; margin-bottom: 4px; }
        .login-option-desc { font-size: 13px; color: #6b7280; }
        @media (max-width: 900px) {
            .login-card { flex-direction: column; height: auto; max-width: 480px; }
            .login-left, .login-right { padding: 40px 30px; }
        }
    </style>
@endsection
