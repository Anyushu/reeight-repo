<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class PlanSendmail extends Mailable implements ShouldQueue
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
            $view_name = 'mail.cancell-mail';
            $subject = 'アクセス解析ツール 「KAGARI」｜有料プラン解約ご連絡';
        } else {
            $view_name = 'mail.changeplan-mail';
            $subject = 'アクセス解析ツール 「KAGARI」｜プラン変更を受け付けました。';
        }
        return $this
        ->subject($subject)
        ->view($view_name)
        ->with([
            'inputs' => $this->inputs,
            'user' => $this->user,
        ]);
    }
}
