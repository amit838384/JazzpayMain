<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class ParentInviteMail extends Mailable
{
    use Queueable, SerializesModels;

    public $parent;

    /**
     * Create a new message instance.
     */
    public function __construct($parent)
    {
        $this->parent = $parent;
    }

    /**
     * Build the message.
     */
    public function build()
    {
        return $this->subject('Parent Account Invitation')
                    ->view('emails.parent_invite')
                    ->with([
                        'name'       => $this->parent->name,
                        'email'      => $this->parent->email,
                        'mobile'     => $this->parent->mobile,
                        'inviteCode' => $this->parent->invite_code,
                    ]);
    }
}