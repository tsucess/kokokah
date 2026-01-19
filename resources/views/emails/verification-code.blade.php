<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <style>
        body {
            font-family: Arial, sans-serif;
            line-height: 1.6;
            color: #333;
            background-color: #f4f4f4;
        }
        .container {
            max-width: 600px;
            margin: 0 auto;
            background-color: #ffffff;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 2px 4px rgba(0,0,0,0.1);
        }
        .header {
            text-align: center;
            margin-bottom: 30px;
            border-bottom: 2px solid #f0f0f0;
            padding-bottom: 20px;
        }
        .logo {
            max-width: 150px;
            height: auto;
            margin-bottom: 10px;
        }
        .content {
            margin: 20px 0;
        }
        .greeting {
            font-size: 18px;
            font-weight: bold;
            margin-bottom: 15px;
        }
        .code-section {
            background-color: #f9f9f9;
            border-left: 4px solid #007bff;
            padding: 15px;
            margin: 20px 0;
            border-radius: 4px;
        }
        .verification-code {
            font-size: 32px;
            font-weight: bold;
            color: #007bff;
            text-align: center;
            letter-spacing: 2px;
            font-family: 'Courier New', monospace;
        }
        .expiry-info {
            font-size: 14px;
            color: #666;
            margin-top: 10px;
            text-align: center;
        }
        .action-button {
            display: inline-block;
            background-color: #007bff;
            color: white;
            padding: 12px 30px;
            text-decoration: none;
            border-radius: 4px;
            margin: 20px 0;
            text-align: center;
        }
        .footer {
            margin-top: 30px;
            padding-top: 20px;
            border-top: 1px solid #f0f0f0;
            font-size: 12px;
            color: #999;
            text-align: center;
        }
        .warning {
            background-color: #fff3cd;
            border: 1px solid #ffc107;
            color: #856404;
            padding: 10px;
            border-radius: 4px;
            margin: 15px 0;
            font-size: 13px;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <img src="{{ urlencode($logoUrl) }}" alt="Kokokah Logo" class="logo" style="display: block; margin: 0 auto;">
            <h1 style="margin: 10px 0; color: #333;">Email Verification</h1>
        </div>

        <div class="content">
            <div class="greeting">Hello {{ $user->first_name }}!</div>

            <p>Thank you for registering with Kokokah LMS. To complete your registration and verify your email address, please use the verification code below:</p>

            <div class="code-section">
                <div class="verification-code">{{ $code }}</div>
                <div class="expiry-info">This code will expire in {{ $expiresIn }} minutes</div>
            </div>

            <p><strong>How to verify your email:</strong></p>
            <ol>
                <li>Go to the verification page on Kokokah LMS</li>
                <li>Enter the code above: <strong>{{ $code }}</strong></li>
                <li>Click "Verify" to complete your registration</li>
            </ol>

            <div class="warning">
                <strong>⚠️ Security Notice:</strong> If you did not request this code, please ignore this email. Do not share this code with anyone.
            </div>

            <p style="margin-top: 20px;">If you have any questions or need assistance, please contact our support team.</p>
        </div>

        <div class="footer">
            <p>&copy; {{ date('Y') }} Kokokah Learning Management System. All rights reserved.</p>
            <p>This is an automated email. Please do not reply to this message.</p>
        </div>
    </div>
</body>
</html>

