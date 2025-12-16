<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use HasFactory, Notifiable;

    protected $fillable = [
        'user_name',
        'email'
    ];

    public function emailVerifyCodes(): HasMany
    {
        return $this->hasMany(EmailVerifyCode::class);
    }

    public function clips(): HasMany
    {
        return $this->hasMany(Clip::class);
    }
}
