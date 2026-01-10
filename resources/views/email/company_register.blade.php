<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Welcome to {{env('APP_NAME')}}</title>
    <style>
        body {
            font-family: Arial, sans-serif;
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
        .support {
            background-color: #e8f4d9;
            padding: 15px;
            border-radius: 5px;
            margin-top: 20px;
        }
    </style>
</head>
<body>
    <div class="header">
        <h1>Welcome to {{env('APP_NAME')}} 🚀</h1>
    </div>
    
    <div class="content">
        <p>Dear {{$username}},</p>
        
        <p>We're excited to welcome you to {{$username}}. Your new company email has been successfully created. Please find your login details below:</p>
        
        <div class="login-details">
            <p><strong>Email:</strong> {{$email}}</p>
            <p><strong>Temporary Password:</strong> {{$password}}</p>
        </div>
        
        <div class="next-steps">
            <p><strong>👉 Next Steps:</strong></p>
            <ul>
                <li>Log in to your account at {{env('APP_URL')}}/login</li>
                <li>Change your temporary password to a secure one</li>
            </ul>
        </div>
        
        <div class="support">
            <p>If you face any issues logging in, please contact our IT support at {{Auth::user()->email}} or {{Auth::user()->mobile_no}}.</p>
        </div>
        
        <p>We're glad to have you on board and look forward to your contributions to the team.</p>
        
        <p>Best regards,<br>
        {{env('APP_NAME')}} Team</p>
    </div>
</body>
</html>