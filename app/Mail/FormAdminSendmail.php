<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class FormAdminSendmail extends Mailable implements ShouldQueue
{
    use Queueable, SerializesModels;

    private $email;
    private $name;
    private $cause;
    private $enquete;
    private $user;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($inputs, $user)
    {
        $this->email = $inputs['email'];
        $this->name = $inputs['name'];
        $this->cause = $inputs['cause'];
        $this->enquete = $inputs['enquete'];
        $this->user = $user;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this
        ->subject('【KAGARI】退会申請がありました')
        ->view('mail.unsubsc-admin-mail')
        ->with([
            'user' => $this->user,
            'email' => $this->email,
            'name' => $this->name,
            'cause' => $this->cause,
            'enquete' => $this->enquete
        ]);
    }
}
