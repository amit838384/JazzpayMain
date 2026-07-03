<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use SimpleSoftwareIO\QrCode\Facades\QrCode;
use Illuminate\Support\Facades\Mail;
class SchoolUserInviteMail extends Mailable
{
    use Queueable, SerializesModels;

    public $school;
    public $signupUrl;
    public $qrCodePath;
    public $password;
    public $loginUrl;

    // ✅ Use the fixed constructor here
    public function __construct($school, $password = null)
    {
        $this->school = $school;
        $this->password = $password;
        $this->signupUrl = url('/invitelink/code/signup?id=' . $school->invite_code);
        $this->loginUrl = url('/login');

        $qrCode = QrCode::format('png')
                        ->size(300)
                        ->generate($this->signupUrl);

        $tempPath = tempnam(sys_get_temp_dir(), 'qr_') . '.png';
        file_put_contents($tempPath, $qrCode);
        $this->qrCodePath = $tempPath;
    }

    public function build()
    {
        return $this->subject('You are invited to JAZZ SMART PAY')
                    ->view('emails.invite')
                    ->with([
                        'name'       => $this->school->name ?? '',
                        'email'      => $this->school->email ?? '',
                        'signupUrl'  => $this->signupUrl,
                        'inviteCode' => $this->school->invite_code ?? '',
                    ])
                    ->attach($this->qrCodePath, [
                        'as'   => 'qrcode.png',
                        'mime' => 'image/png',
                    ]);
    }

    public function __destruct()
    {
        // Clean up temporary file
        if (file_exists($this->qrCodePath)) {
            unlink($this->qrCodePath);
        }
    }
}
