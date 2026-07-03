<!DOCTYPE html>
<html>
<head>
    <title>Account Credentials</title>
</head>
<body>
    <h2>Welcome {{ $user->name }}</h2>

    <p>Your account has been created successfully.</p>

    <p>
        <strong>Email:</strong> {{ $user->email }}
    </p>

    <p>
        <strong>Password:</strong> {{ $password }}
    </p>

    <p>Please change your password after your first login.</p>
</body>
</html>