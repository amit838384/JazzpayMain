<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>You're invited to JAZZ SMART PAY</title>
</head>
<body style="font-family: Arial, sans-serif; background-color:#f8f9fa; padding:30px;">
    <div style="max-width:600px; margin:auto; background:white; border-radius:10px; padding:30px; box-shadow:0 3px 10px rgba(0,0,0,0.1);">
        <h2 style="color:#333;">👋 Hello {{ $name ?? 'User' }},</h2>

        <p>You're invited to join <strong>JAZZ SMART PAY</strong>! We're excited to have you on board.</p>

        <hr style="margin:20px 0;">

        @if(!empty($inviteCode))
        <p><strong>🎟️ Invite Code:</strong> <code style="background:#eee; padding:3px 6px; border-radius:4px;">{{ $inviteCode }}</code></p>
        @endif

        <p><strong>📩 Email:</strong> {{ $email }}</p>

        <div style="margin:25px 0; text-align:center;">
            <a href="{{ $signupUrl }}" style="background:#007bff; color:white; padding:12px 25px; text-decoration:none; border-radius:6px; font-weight:bold;">Join JAZZ SMART PAY</a>
        </div>

        <div style="text-align:center;">
            <p><strong>📱 QR Code:</strong></p>
            <img src="cid:qrcode.png" alt="QR Code" style="max-width:180px; margin-top:10px;">
        </div>

        <hr style="margin:30px 0;">

        <p><em>Important:</em> Please use the same email address (<strong>{{ $email }}</strong>) during signup.</p>

        <p style="margin-top:25px;">Looking forward to welcoming you to <strong>JAZZ SMART PAY</strong>!</p>

        <p>Best regards,<br><strong>The JAZZ SMART PAY Team</strong></p>
    </div>
</body>
</html>
