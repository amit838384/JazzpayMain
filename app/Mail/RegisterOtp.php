<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class RegisterOtp extends Mailable
{
    use Queueable, SerializesModels;

    public $professional;

    
    public function __construct($professional)
    {
        $this->professional = $professional;
    }

    public function build()
    {
        return $this->subject('Professional Registration Invite')
                    ->view('emails.RegisterOtp')
                    ->with([
                        'email' => $this->professional->email,
                        'appLink' => 'https://yourapp.com/download', 
                    ]);
    }
}
