<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Category extends Model
{
    protected $table = 'site_categories';
    protected $guarded = ['id'];
    public $timestamps = true;

    protected $fillable = [
        'cat', 'created_at', 'updated_at'
    ];
}
