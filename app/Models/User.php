<?php

namespace App\Models;

use App\Models\EmailVerifyCode;
use App\Models\Session;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Fortify\TwoFactorAuthenticatable;

class User extends Authenticatable
{
    use HasFactory, Notifiable, TwoFactorAuthenticatable;

    protected $fillable = [
        'user_name',
        'email'
    ];

    protected $hidden = [
        'two_factor_secret',
        'two_factor_recovery_codes',
        'remember_token'
    ];

    protected function casts(): array
    {
        return [
            'email_verified_at'       => 'datetime',
            'two_factor_confirmed_at' => 'datetime'
        ];
    }

    public function sessions(): HasMany
    {
        return $this->hasMany(Session::class);
    }

    public function emailVerifyCodes(): HasMany
    {
        return $this->hasMany(EmailVerifyCode::class);
    }
}
