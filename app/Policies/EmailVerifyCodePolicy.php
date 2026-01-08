<?php

namespace App\Policies;

use App\Models\EmailVerifyCode;
use App\Models\User;
use Illuminate\Auth\Access\Response;

class EmailVerifyCodePolicy
{
    public function sendVerifyCode(?User $user, string $email): Response
    {
        $codeRecord = EmailVerifyCode::where('email', $email)
            ->where('updated_at', '>', now()->subMinutes(2))
            ->first();

        if ($codeRecord) {
            return Response::deny('You need to wait 2 minutes before sending another verify code');
        }

        return Response::allow();
    }
}
