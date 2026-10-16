<div
    style="font-family: Arial, sans-serif; padding: 20px; color: #333; background-color: #f9f9f9; text-align: center; border-radius:8px;">
    <h2 style="color: #002459;">Password Reset</h2>

    <p style="font-size: 16px;">Hello, <strong>{{ $user->name }}</strong>,</p>

    <p style="font-size: 15px;">
        You requested to reset your password. Click the button below to create a new password:
    </p>

    <a href="{{ route('password.reset', $token) }}"
       style="display:inline-block;margin-top:15px;padding:12px 25px;background-color:#002459;color:white;text-decoration:none;border-radius:4px;font-weight:bold;">
        Reset Password
    </a>

    <p style="font-size: 14px; margin-top: 20px;">
        If you didn't request a password reset, please ignore this email or contact our support team.
    </p>

    <hr style="margin: 25px 0; border-color:#ddd;">

    <p style="font-size: 14px;">
        Stay connected with us on social media!
    </p>

    <div style="margin-top:10px;">
        <a href="https://www.instagram.com/posh_center_kw/" style="text-decoration:none;">
            <img src="https://cdn-icons-png.flaticon.com/512/2111/2111463.png" alt="Instagram"
                 style="width:30px;height:30px;margin:0 5px;">
        </a>
    </div>

    <p style="margin-top:25px;font-size:13px;color:#888;">
        Thanks,<br>
        <strong>The Posh Car Team</strong>
    </p>
</div>
