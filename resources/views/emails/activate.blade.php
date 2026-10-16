<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Activate Your Account</title>
</head>
<body style="font-family: Arial, sans-serif; background-color: #f8f8f8; padding: 30px;">
    <div style="max-width: 600px; margin: auto; background-color: #ffffff; padding: 20px; border-radius: 8px; box-shadow: 0 0 10px rgba(0,0,0,0.1);">
        <h2 style="color: #333;">Hello {{ $user->name }},</h2>

        <p>Thank you for registering with us!</p>

        <p>Please click the button below to activate your account:</p>

        <p style="text-align: center;">
            <a href="{{ $activationLink }}" style="display: inline-block; padding: 12px 25px; background-color: #4CAF50; color: white; text-decoration: none; border-radius: 5px;">
                Activate My Account
            </a>
        </p>

        <p>If the button doesn't work, copy and paste the link below into your browser:</p>
        <p style="word-break: break-word;">
            {{ $activationLink }}
        </p>

        <hr style="margin: 20px 0;">
        <p style="color: #999;">If you did not create an account, please ignore this email.</p>

        <p style="text-align: center; color: #bbb; font-size: 12px;">
            &copy; {{ date('Y') }} {{ config('app.name') }}. All rights reserved.
        </p>
    </div>
</body>
</html>
