<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class PreOrderMail extends Mailable
{
    use Queueable, SerializesModels;

    public $order, $dish, $student, $school;

    public function __construct($order, $dish, $student, $school)
    {
        $this->order   = $order;
        $this->dish    = $dish;
        $this->student = $student;
        $this->school  = $school;
    }

    public function build()
    {
        $ref = $this->order->transaction_no ?? ('#' . $this->order->id);
        return $this->subject('New Pre-Order - ' . $ref)
                    ->view('emails.preorder');
    }
}
