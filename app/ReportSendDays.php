<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class ReportSendDays extends Model
{
    /**
     * モデルと関連しているテーブル
     *
     * @var string
     */
    protected $table = 'report_send_days';

    /**
     * 属性に対するモデルのデフォルト値
     *
     * @var array
     */
    protected $attributes = [
        'days' => 0,
    ];
}
