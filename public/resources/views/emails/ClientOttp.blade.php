<!DOCTYPE html>
<html>
<head>
    <title>Client Registration Invite</title>
</head>
<body>
    <h1>Hi, {{ $email }}</h1>
    <p>This is an invite to join our platform.</p>
    <p>Please click the link below to register as a Client:</p>
    <a href="{{ $appLink }}">Go to Registration Link</a>
    <br>
    <p>Thank you!</p>
</body>
</html>