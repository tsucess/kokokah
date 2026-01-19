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
            max-width: 200px;
            height: auto;
            margin-bottom: 10px;
            display: block;
            margin-left: auto;
            margin-right: auto;
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
            <img src="data:image/jpeg;base64,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" alt="Kokokah Logo" class="logo">
            {{-- <img src="{{ $message->embed($logoPath) }}" alt="Kokokah Logo" class="logo"> --}}
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

