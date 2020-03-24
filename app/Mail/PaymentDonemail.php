<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class PaymentDonemail extends Mailable implements ShouldQueue
{
    use Queueable, SerializesModels;

    private $inputs;
    private $user;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($inputs, $user)
    {
        $this->inputs = $inputs;
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
        ->subject('アクセス解析ツール 「KAGARI」｜有料プランお申し込みのご連絡')
        ->view('mail.payment-done-mail')
        ->with([
            'inputs' => $this->inputs,
            'user' => $this->user,
        ]);
    }
}
