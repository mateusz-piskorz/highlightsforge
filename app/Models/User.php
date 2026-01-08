<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use HasFactory, Notifiable;

    protected $hidden = [
        'remember_token'
    ];

    protected $fillable = [
        'user_name',
        'email',
        'role'
    ];

    public function emailVerifyCodes(): HasMany
    {
        return $this->hasMany(EmailVerifyCode::class);
    }

    public function posts(): HasMany
    {
        return $this->hasMany(Post::class);
    }
}
