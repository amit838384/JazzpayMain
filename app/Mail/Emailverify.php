<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Http\Request;
use Illuminate\Mail\Mailable;
use Illuminate\Support\Facades\DB;
use Illuminate\Queue\SerializesModels;

class Emailverify extends Mailable
{
    use Queueable, SerializesModels; 

    public function build(Request $request){	
		$otp 			= random_int(111111,999999);	
        $senderEmail 	= $request->get('email');	
		
		DB::table('professionals_users_emailverify')
		->where('email', $senderEmail)
		->orderBy('created_at', 'desc')
		->limit(1)
		->update(['otp' => $otp]);
		
		$data = [
            'senderEmail' 	=> $senderEmail,
			'otp' 			=> $otp,
        ];
        return $this
            ->from(config('mail.contact.address'))
			->subject('Taxjunction Email Verify')
            ->replyTo($senderEmail)
            ->view('emails.emailverify')
            ->with($data);
    }
}