<!DOCTYPE html>
<html>
<head>
    <title>{{ $data['subject'] ?? 'Important Information' }}</title>
</head>
<body>
    <div style="font-family: Arial, sans-serif; line-height: 1.6; max-width: 600px; margin: 0 auto;">
        <h2 style="background-color: #4CAF50; color: white; padding: 10px; text-align: center;">
            {{ $data['subject'] ?? 'Notification from Our Service' }}
        </h2>

        <p>Dear {{ $data['name'] }},</p>

        <p>We are pleased to inform you that your account has been created successfully.</p>

        <p><strong>User:</strong> {{ $data['email'] }}</p>
        <p><strong>Password:</strong> {{ $data['password'] }}</p>

        <p>Please keep these details safe and secure. You can now log in using the credentials above.</p>

        <p>Thank you for choosing our service. If you have any questions, feel free to reach out to us.</p>

        <p>Best regards,</p>
        <p><strong>Tax Junction</strong></p>
    </div>
</body>
</html>
