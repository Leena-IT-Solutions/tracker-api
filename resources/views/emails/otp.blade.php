<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Password Reset OTP</title>
    <style>
        body {
            background-color: #020617;
            font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, Helvetica, Arial, sans-serif;
            color: #f1f5f9;
            margin: 0;
            padding: 40px 20px;
            text-align: center;
        }
        .container {
            max-width: 500px;
            margin: 0 auto;
            background-color: #0f172a;
            border: 1px solid #1e293b;
            border-radius: 24px;
            padding: 40px;
            box-shadow: 0 20px 25px -5px rgba(0, 0, 0, 0.5), 0 10px 10px -5px rgba(0, 0, 0, 0.5);
        }
        .logo {
            font-size: 24px;
            font-weight: 900;
            background: linear-gradient(to right, #8b5cf6, #6366f1);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            margin-bottom: 30px;
            display: inline-block;
            letter-spacing: -0.025em;
        }
        h1 {
            font-size: 20px;
            font-weight: 700;
            color: #ffffff;
            margin-bottom: 12px;
        }
        p {
            font-size: 14px;
            color: #94a3b8;
            line-height: 1.6;
            margin-bottom: 30px;
        }
        .otp-container {
            background-color: #020617;
            border: 1px solid #334155;
            border-radius: 16px;
            padding: 20px;
            margin-bottom: 30px;
            display: inline-block;
            min-width: 200px;
        }
        .otp-code {
            font-size: 32px;
            font-weight: 800;
            font-family: 'Courier New', Courier, monospace;
            letter-spacing: 6px;
            color: #8b5cf6;
            margin: 0;
            padding-left: 6px; /* Offset the letter-spacing */
        }
        .footer {
            font-size: 11px;
            color: #64748b;
            border-top: 1px solid #1e293b;
            padding-top: 20px;
            margin-top: 20px;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="logo">Tracker</div>
        <h1>Reset Your Password</h1>
        <p>We received a request to reset your password. Use the verification code below to proceed. This OTP is valid for 15 minutes.</p>
        
        <div class="otp-container">
            <div class="otp-code">{{ $otp }}</div>
        </div>

        <p style="font-size: 12px; color: #64748b;">If you did not request a password reset, please ignore this email or contact support.</p>
        
        <div class="footer">
            &copy; {{ date('Y') }} Tracker. All rights reserved.
        </div>
    </div>
</body>
</html>
