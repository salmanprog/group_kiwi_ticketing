<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Welcome to {{ $company_name }}</title>
    <style>
        body {
            font-family: "Montserrat", sans-serif !important;
            line-height: 1.6;
            color: #333333;
            max-width: 600px;
            margin: 0 auto;
            padding: 20px;
        }
        .header {
            background-color: #A0C242;
            color: white;
            padding: 20px;
            text-align: center;
            border-radius: 5px 5px 0 0;
        }
        .content {
            padding: 20px;
            background-color: #f9f9f9;
            border-radius: 0 0 5px 5px;
        }
        .login-details {
            background-color: #ffffff;
            padding: 15px;
            border-left: 4px solid #A0C242;
            margin: 15px 0;
        }
        .next-steps {
            margin: 20px 0;
        }
    </style>
</head>
<body>
    <div class="header">
        <h1>Your Visit Estimate – {{ $company_name }}</h1>
    </div>
    
    <div class="content">
        <p>Dear {{ $username }}</p>
        
        <p>Thank you for showing interest in {{ $company_name }}. We’re excited at the opportunity to host you and make your visit a memorable one.</p>
        
        
        <div class="next-steps">
            <p><strong>👉 Next Steps:</strong></p>
            <ul>
                <li>Setup your password to a secure one</li>
                <li>Log in to your account at <a href="{{ $link }}">{{ $link }}</a></li>
            </ul>
        </div>
        
        <p>We're glad to have you on board and look forward to your contributions to the team.</p>
        
        <p>Best regards,<br>
        {{ $company_name }} Team</p>
    </div>
</body>
</html>