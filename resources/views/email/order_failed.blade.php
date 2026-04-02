<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Order Failed - {{ $company_name }}</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f4f4;
            margin: 0;
            padding: 20px;
        }
        .container {
            max-width: 600px;
            margin: auto;
            background: #fff;
            border-radius: 6px;
            padding: 20px;
        }
        .header {
            background-color: #e74c3c;
            color: #fff;
            padding: 15px;
            text-align: center;
            border-radius: 5px 5px 0 0;
        }
        .section {
            margin: 15px 0;
        }
        .label {
            font-weight: bold;
            margin-bottom: 5px;
        }
        .code-box {
            background: #272822;
            color: #f8f8f2;
            padding: 10px;
            border-radius: 4px;
            font-size: 12px;
            white-space: pre-wrap;
            word-break: break-word;
        }
        .footer {
            text-align: center;
            font-size: 12px;
            color: #888;
            margin-top: 20px;
        }
    </style>
</head>
<body>

<div class="container">

    <div class="header">
        <h2>❌ {{ $error_message }}</h2>
        <p>{{ $company_name }}</p>
    </div>

    <div class="section">
        <div class="label">Error Message:</div>
        <div>{{ $error_message ?? 'Unknown error' }}</div>
    </div>

    <div class="section">
        <div class="label">Payload (Request JSON):</div>
        <div class="code-box">
{{ json_encode($payload, JSON_PRETTY_PRINT) }}
        </div>
    </div>

    <div class="section">
        <div class="label">Response (API Output):</div>
        <div class="code-box">
{{ json_encode($response, JSON_PRETTY_PRINT) }}
        </div>
    </div>

    <div class="footer">
        This is an automated alert from {{ $company_name }}
    </div>

</div>

</body>
</html>