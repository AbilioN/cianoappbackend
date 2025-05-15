<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Password Reset</title>
</head>
<body>
    <div style="font-family: Arial, sans-serif; max-width: 600px; margin: 0 auto; padding: 20px;">
        <img src="{{ config('app.url') }}/main_logo.png" alt="Ciano Logo" style="max-width: 200px; margin-bottom: 20px;">
        {{-- <img src="{{ asset('main_logo.png') }}" alt="Ciano Logo" style="max-width: 100px; margin-bottom: 20px;"> --}}
        
        <h2 style="color: #0693e3;">Password Reset</h2>
        
        <p>Hello,</p>
        
        <p>We received a request to reset your account password.</p>
        
        <p>To reset your password, click the button below:</p>
        
        <div style="text-align: center; margin: 30px 0;">
            <a href="{{ route('auth.password.reset', ['token' => $token, 'language' => 'en']) }}" 
               style="background-color: #0693e3; color: white; padding: 12px 24px; text-decoration: none; border-radius: 4px;">
                Reset Password
            </a>
        </div>
        
        <p>If you did not request a password reset, you can ignore this email.</p>
        
        <p>This reset link will expire in 60 minutes.</p>
        
        <p>Thank you,<br>The Ciano Team</p>
        
        <hr style="margin: 30px 0; border: none; border-top: 1px solid #eee;">
        
        <p style="color: #666; font-size: 12px;">
            © 2025 CIANO. All rights reserved.
        </p>
    </div>
</body>
</html>