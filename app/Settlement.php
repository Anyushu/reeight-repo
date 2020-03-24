<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Settlement extends Model
{
    /**
     * モデルと関連しているテーブル
     *
     * @var string
     */
    protected $table = 'settlement';

    /**
     * テーブルの主キー
     *
     * @var string
     */
    protected $primaryKey = 'id';
}
