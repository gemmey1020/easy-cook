<div
    style="font-family: Arial, sans-serif; padding: 20px; color: #333; background-color: #f9f9f9; text-align: center; border-radius:8px;">
    <h2 style="color: #0c7a7a;">Easy Cook</h2>

    <p style="font-size: 16px;">Hello, <strong>{{ $user->first_name }}</strong>,</p>

    <p style="font-size: 15px;">
        You requested to reset your password. Click the button below to create a new password:
    </p>

    <a href="{{ route('password.reset', $token) }}"
        style="display:inline-block;margin-top:15px;padding:12px 25px;background-color:#0c7a7a;color:white;text-decoration:none;border-radius:4px;font-weight:bold;">
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
        <a href="https://www.facebook.com/easycook.vip" style="text-decoration:none;">
            <img src="https://cdn-icons-png.flaticon.com/512/733/733547.png" alt="Facebook"
                style="width:30px;height:30px;margin:0 5px;">
        </a>
        <a href="https://www.instagram.com/easycook.vip/" style="text-decoration:none;">
            <img src="https://cdn-icons-png.flaticon.com/512/2111/2111463.png" alt="Instagram"
                style="width:30px;height:30px;margin:0 5px;">
        </a>
        <a href="https://www.snapchat.com/add/easycook.vip" style="text-decoration:none;">
            <img src="https://cdn-icons-png.flaticon.com/512/3670/3670166.png" alt="Snapchat"
                style="width:30px;height:30px;margin:0 5px;">
        </a>
        <a href="https://www.youtube.com/@easycookvip" style="text-decoration:none;">
            <img src="https://cdn-icons-png.flaticon.com/512/1384/1384060.png" alt="YouTube"
                style="width:30px;height:30px;margin:0 5px;">
        </a>
        <a href="https://www.tiktok.com/@easycook.vip" style="text-decoration:none;">
            <img src="https://cdn-icons-png.flaticon.com/512/3046/3046121.png" alt="TikTok"
                style="width:30px;height:30px;margin:0 5px;">
        </a>
    </div>

    <p style="margin-top:25px;font-size:13px;color:#888;">
        Thanks,<br>
        <strong>The Easy Cook Team</strong>
    </p>
</div>
