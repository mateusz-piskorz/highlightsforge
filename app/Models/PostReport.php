<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PostReport extends Model
{

    public $timestamps = false;
    protected $fillable = [
        'user_id',
        'post_id'
    ];
}
