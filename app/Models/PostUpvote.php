<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PostUpvote extends Model
{

    public $timestamps = false;
    protected $fillable = [
        'user_id',
        'post_id'
    ];
}
