<!DOCTYPE html>
<html>

<head>
    <meta charset="UTF-8">
    <title>{{ $error_message ?? 'Unknown error occurred' }} - {{ $company_name }}</title>
    <style>
        body {
            font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, Arial, sans-serif;
            /* background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); */
            margin: 0;
            padding: 40px 20px;
        }

        .container {
            max-width: 650px;
            margin: auto;
            background: #fff;
            border-radius: 16px;
            overflow: hidden;
            box-shadow: 0 20px 60px rgba(0, 0, 0, 0.3);
        }

        .header {
            background: linear-gradient(135deg, #e74c3c 0%, #c0392b 100%);
            color: #fff;
            padding: 30px 20px;
            text-align: center;
        }

        .header h2 {
            margin: 0;
            font-size: 24px;
            font-weight: 600;
        }

        .header p {
            margin: 8px 0 0;
            font-size: 14px;
            opacity: 0.9;
        }

        .content {
            padding: 30px;
        }

        .section {
            margin-bottom: 25px;
        }

        .label {
            font-weight: 600;
            margin-bottom: 8px;
            color: #2c3e50;
            font-size: 13px;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }

        .error-text {
            background: #fff5f5;
            color: #c0392b;
            padding: 12px 15px;
            border-radius: 10px;
            font-size: 14px;
            font-weight: 500;
            border-left: 4px solid #e74c3c;
        }

        .code-box {
            background: #1e1e1e;
            color: #d4d4d4;
            padding: 15px;
            border-radius: 10px;
            font-size: 12px;
            white-space: pre-wrap;
            word-break: break-word;
            font-family: 'Courier New', 'Monaco', monospace;
            line-height: 1.5;
            max-height: 250px;
            overflow-y: auto;
            border: 1px solid #333;
        }

        .code-box::-webkit-scrollbar {
            width: 6px;
            height: 6px;
        }

        .code-box::-webkit-scrollbar-track {
            background: #2d2d2d;
            border-radius: 3px;
        }

        .code-box::-webkit-scrollbar-thumb {
            background: #666;
            border-radius: 3px;
        }

        .code-box::-webkit-scrollbar-thumb:hover {
            background: #888;
        }

        .footer {
            text-align: center;
            font-size: 12px;
            color: #999;
            padding: 20px;
            border-top: 1px solid #eee;
            background: #fefefe;
        }

        .footer p {
            margin: 5px 0;
        }

        .status-badge {
            display: inline-block;
            background: rgba(255, 255, 255, 0.2);
            padding: 4px 12px;
            border-radius: 20px;
            font-size: 12px;
            margin-top: 10px;
        }

        @media (max-width: 600px) {
            body {
                padding: 20px 10px;
            }

            .content {
                padding: 20px;
            }

            .header h2 {
                font-size: 20px;
            }
        }
    </style>
</head>

<body>

    <div class="container">
        <div class="header">
            <h2>❌ {{ $error_message ?? 'Unknown error occurred' }}</h2>
            <p>{{ $company_name }}</p>
            <div class="status-badge">⚠️ Action Required</div>
        </div>

        <div class="content">
            <div class="section">
                <div class="label">🔴 Error Message</div>
                <div class="error-text">{{ $error_message ?? 'Unknown error occurred' }}</div>
            </div>

            <div class="section">
                <div class="label">📦 Request Payload</div>
                <div class="code-box">
                    {{ json_encode($payload, JSON_PRETTY_PRINT) }}
                </div>
            </div>

            <div class="section">
                <div class="label">🔄 API Response</div>
                <div class="code-box">
                    {{ json_encode($response, JSON_PRETTY_PRINT) }}
                </div>
            </div>
        </div>

        <div class="footer">
            <p>🔔 This is an automated alert from {{ $company_name }}</p>
            <p style="font-size: 11px;">Please check the issue and take necessary action</p>
        </div>
    </div>

</body>

</html>
