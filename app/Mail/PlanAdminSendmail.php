<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class PlanAdminSendmail extends Mailable implements ShouldQueue
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
        if ($this->inputs['plan_name'] == '解約') {
            $subject = '【KAGARI】プラン解約申請がありました';
        } else {
            $subject = '【KAGARI】プラン変更申請がありました';
        }
        return $this
        ->subject($subject)
        ->view('mail.changeplan-admin-mail')
        ->with([
            'inputs' => $this->inputs,
            'user' => $this->user,
        ]);
    }
}
