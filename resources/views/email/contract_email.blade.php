<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
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
        <h1>Your Contract – {{ $company_name }}</h1>
    </div>
    
    <div class="content">
        <p>Dear {{ $username }}</p>
        
        <p>Please find attached your contract for your review.</p>
        
        
        <div class="next-steps">
            <ul>
                <li>Download the contract <a href="{{ $link }}">here</a></li>
            </ul>
        </div>
        
        <p>We look forward to working with you!</p>
        
        <p>Best regards,<br>
        {{ $company_name }} Team</p>
    </div>
</body>
</html>