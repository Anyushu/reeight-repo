<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class ReportSendMail extends Model
{
    /**
     * モデルと関連しているテーブル
     *
     * @var string
     */
    protected $table = 'report_send_mail';

    /**
     * 属性に対するモデルのデフォルト値
     *
     * @var array
     */
    protected $attributes = [
        'mailaddress' => '',
        'to_cc' => 0,
    ];
}
