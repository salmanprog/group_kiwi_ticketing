<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Welcome to {{ $company_name }}</title>
    <style>
        body {
            font-family: "Montserrat", sans-serif;
            line-height: 1.6;
            color: #333333;
            margin: 0;
            padding: 0;
            background-color: #f4f4f4;
        }

        .container {
            max-width: 600px;
            margin: 40px auto;
            background-color: #ffffff;
            border-radius: 8px;
            overflow: hidden;
            box-shadow: 0 2px 6px rgba(0,0,0,0.1);
        }

        .header {
            background-color: #A0C242;
            color: #ffffff;
            text-align: center;
            padding: 30px 20px;
        }

        .header h1 {
            margin: 0;
            font-size: 24px;
        }

        .content {
            padding: 30px 20px;
            font-size: 16px;
            color: #333333;
        }

        .content p {
            margin: 15px 0;
        }

        .ticket {
            margin: 20px 0;
            padding: 20px;
            border-left: 4px solid #A0C242;
            background-color: #f9f9f9;
            border-radius: 4px;
            text-align: center;
        }

        .ticket img {
            margin: 15px 0;
        }

        .ticket h5 {
            margin: 10px 0;
            font-size: 18px;
        }

        .footer {
            background-color: #F3F4F6;
            text-align: center;
            padding: 20px;
            font-size: 12px;
            color: #8e8e93;
        }

        @media only screen and (max-width: 620px) {
            .container {
                margin: 20px;
            }

            .header h1 {
                font-size: 20px;
            }

            .content {
                padding: 20px 15px;
            }
        }
    </style>
</head>
<body>
    <div class="container">
        <!-- Header -->
        <div class="header">
            <h1>Your Visit Estimate – {{ $company_name }}</h1>
        </div>

        <!-- Content -->
        <div class="content">
            <p>Dear {{ $username }},</p>

            <p>Thank you for showing interest in {{ $company_name }}. We’re excited to host you and make your visit a memorable one.</p>

            <!-- Ticket Info -->
           {{ $ticketList }}

            <p>Best regards,<br>
            {{ $company_name }} Team</p>
            
        </div>

        <!-- Footer -->
        <div class="footer">
            &copy; {{ date('Y') }} {{ $company_name }}. All rights reserved.
        </div>
    </div>
</body>
</html>