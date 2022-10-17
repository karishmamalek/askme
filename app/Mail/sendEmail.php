<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class sendEmail extends Mailable
{
    use Queueable, SerializesModels;
    public $data;
    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($data)
    {
        $this->data = $data;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        
        $address = 'sahkaritech@gmail.com';
        $subjec = 'This is a demo of askme';
        $name = 'Sahil Hamiranii';
        return $this->view('emails.test')
        ->from($address,$name)
        ->cc($address,$name)
        ->bcc($address,$name)
        ->replyTo($address,$name)
        ->subject($subjec)
        ->with(['test_message'=>$this->data['message']]);

    }
}
