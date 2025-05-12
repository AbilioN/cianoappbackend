<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Password Recovery - Ciano</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            line-height: 1.6;
            color: #333333;
            margin: 0;
            padding: 0;
        }
        .container {
            max-width: 600px;
            margin: 0 auto;
            padding: 20px;
        }
        .header {
            text-align: center;
            padding: 20px 0;
        }
        .logo {
            max-width: 200px;
            height: auto;
        }
        .content {
            background-color: #ffffff;
            padding: 30px;
            border-radius: 5px;
        }
        .button {
            display: inline-block;
            padding: 12px 30px;
            background-color: #0693e3;
            color: #ffffff !important;
            text-decoration: none;
            border-radius: 30px;
            margin: 20px 0;
        }
        .footer {
            text-align: center;
            padding: 20px;
            font-size: 12px;
            color: #666666;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            {{-- <img src="https://app.ciano.pt/main_logo.png" alt="Ciano Logo" class="logo"> --}}
        </div>
        <div class="content">
            <h2>Password Recovery</h2>
            <p>Hello,</p>
            <p>We received a request to reset your Ciano account password. If you made this request, click the button below to create a new password:</p>
            
            <div style="text-align: center;">
                <a href="{{ url('/reset/'.$token) }}" class="button">Reset Password</a>
            </div>

            <p>This link is valid for 60 minutes.</p>
            
            <p>If you did not request a password reset, please ignore this email or contact our support if you have any questions.</p>
            
            <p>Best regards,<br>Ciano Team</p>
        </div>
        <div class="footer">
            <p>This is an automated email, please do not reply.</p>
            <p>&copy; {{ date('Y') }} Ciano. All rights reserved.</p>
        </div>
    </div>
</body>
</html>