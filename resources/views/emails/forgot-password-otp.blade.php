<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Password Reset OTP</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            line-height: 1.6;
            color: #333;
            max-width: 600px;
            margin: 0 auto;
            padding: 20px;
            background-color: #f4f4f4;
        }
        .container {
            background-color: #f9fafb;
            border-radius: 8px;
            padding: 30px;
            border: 1px solid #e5e7eb;
        }
        .header {
            text-align: center;
            margin-bottom: 30px;
        }
        .logo {
            font-size: 28px;
            font-weight: bold;
            color: #4f46e5;
            margin-bottom: 10px;
        }
        .content {
            background-color: white;
            padding: 30px;
            border-radius: 8px;
            box-shadow: 0 1px 3px rgba(0, 0, 0, 0.1);
        }
        .otp-box {
            background-color: #eef2ff;
            border: 2px dashed #4f46e5;
            border-radius: 8px;
            padding: 20px;
            text-align: center;
            margin: 25px 0;
        }
        .otp-code {
            font-size: 36px;
            font-weight: bold;
            color: #4f46e5;
            letter-spacing: 10px;
            margin: 10px 0;
            font-family: 'Courier New', monospace;
        }
        .warning {
            background-color: #fef3c7;
            border-left: 4px solid #f59e0b;
            padding: 15px;
            margin: 20px 0;
            border-radius: 4px;
        }
        .footer {
            text-align: center;
            margin-top: 30px;
            padding-top: 20px;
            border-top: 1px solid #e5e7eb;
            color: #6b7280;
            font-size: 14px;
        }
        h1 {
            color: #1f2937;
            margin-bottom: 20px;
            font-size: 24px;
        }
        p {
            margin-bottom: 15px;
            color: #374151;
        }
        .highlight {
            color: #4f46e5;
            font-weight: 600;
        }
        ul {
            padding-left: 20px;
            margin: 15px 0;
        }
        li {
            margin-bottom: 8px;
            color: #374151;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <div class="logo">EduAcademy</div>
        </div>

        <div class="content">
            <h1>Password Reset Request</h1>

            <p>Hello <strong>{{ $user->name }}</strong>,</p>

            <p>We received a request to reset your password for your EduAcademy account. Use the OTP code below to complete the password reset process:</p>

            <div class="otp-box">
                <div style="font-size: 14px; color: #6b7280; margin-bottom: 5px;">Your OTP Code</div>
                <div class="otp-code">{{ $otp }}</div>
                <div style="font-size: 12px; color: #6b7280; margin-top: 5px;">Valid for 10 minutes</div>
            </div>

            <p>Enter this code on the password reset page to verify your identity and set a new password.</p>

            <div class="warning">
                <strong>⚠️ Security Notice:</strong><br>
                If you didn't request this password reset, please ignore this email or contact our support team if you have concerns about your account security.
            </div>

            <p><strong>Important reminders:</strong></p>
            <ul>
                <li>This OTP will expire in <span class="highlight">10 minutes</span></li>
                <li>Do not share this code with anyone</li>
                <li>EduAcademy will never ask for your OTP via phone or email</li>
                <li>If you didn't request this, you can safely ignore this email</li>
            </ul>

            <p>If you have any questions or need assistance, please don't hesitate to contact our support team.</p>

            <p style="margin-top: 30px;">
                Best regards,<br>
                <strong>The EduAcademy Team</strong>
            </p>
        </div>

        <div class="footer">
            <p>&copy; {{ date('Y') }} EduAcademy. All rights reserved.</p>
            <p>This is an automated email. Please do not reply to this message.</p>
        </div>
    </div>
</body>
</html>
