<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Parent Account Invitation</title>
</head>
<body style="font-family: Arial, sans-serif; line-height: 1.6;">

    <h2>Hello {{ $name }},</h2>

    <p>
        You have been invited to join the Parent App.
    </p>

    <p>
        Please use the details below to complete your registration in the mobile application:
    </p>

    <table cellpadding="8" cellspacing="0" border="1" style="border-collapse: collapse;">
        <tr>
            <td><strong>Name</strong></td>
            <td>{{ $name }}</td>
        </tr>
        <tr>
            <td><strong>Email</strong></td>
            <td>{{ $email }}</td>
        </tr>
        <tr>
            <td><strong>Mobile Number</strong></td>
            <td>{{ $mobile }}</td>
        </tr>
        <tr>
            <td><strong>Invite Code</strong></td>
            <td>{{ $inviteCode }}</td>
        </tr>
    </table>

    <br>

    <p>
        Open the mobile application and enter your Invite Code to create your password and activate your account.
    </p>

    <p>
        If you did not expect this invitation, please ignore this email.
    </p>

    <br>

    <p>
        Regards,<br>
        School Administration Team
    </p>

</body>
</html>