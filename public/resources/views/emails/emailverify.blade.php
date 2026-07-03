<div>
    <h1>{{ config('app.name') }}</h1>
    <hr />
    <p><strong>Sender E-mail:</strong> <a href="mailto:{{ $senderEmail }}">{{ $senderEmail }}</a></p>
    <hr />
    <p><strong>Message:</strong></p>
	<p>Your OTP is:- {{ $otp }}</p>
</div>

