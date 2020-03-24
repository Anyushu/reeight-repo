<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class TrialStartSendmail extends Mailable implements ShouldQueue
{
    use Queueable, SerializesModels;

    private $user;
    private $add_sites;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($user, $add_sites)
    {
        $this->user = $user;
        $this->add_sites = $add_sites;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this
        ->subject('アクセス解析ツール「KAGARI」｜トライアル開始のご連絡')
        ->view('mail.trial-start')
        ->with([
            'user' => $this->user,
            'add_sites' => $this->add_sites
        ]);
    }
}
