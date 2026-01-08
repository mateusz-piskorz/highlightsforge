<?php

namespace App\Http\Controllers\Api;

use App\Mail\MailVerifyCode;
use App\Models\EmailVerifyCode;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;

class AuthController
{

    public function register(Request $request)
    {
        $user_name = $request->validate(['user_name' => 'required|string|min:3|max:255'])['user_name'];
        $user = User::create(['user_name' => $user_name]);

        if (!Auth::loginUsingId($user->id, true)) {
            return response()->json([
                'success' => false,
                'message' => 'The provided credentials are incorrect'
            ]);
        }
        $request->session()->regenerate();

        return response()->json([
            'message' => 'singed in successfully',
            'success' => true,
            'user'    => $request->user()->only(['id', 'name', 'email'])
        ]);
    }

    public function loginStep1(Request $request)
    {
        $email = $request->validate(['email' => 'required|email|max:250'])['email'];
        $user = User::where('email', $email)->first();
        if (!$user) {
            return response()->json([
                'success' => false,
                'message' => 'incorrect E-mail'
            ]);
        }

        Gate::authorize('sendVerifyCode', [EmailVerifyCode::class, $email]);

        $code = Str::random(6);
        $codeRecord = $user->emailVerifyCodes->firstWhere('email', $email);
        if ($codeRecord) {
            $codeRecord->code = $code;
            $codeRecord->save();
        } else {
            EmailVerifyCode::create(['code' => $code, 'email' => $email, 'user_id' => $user->id]);
        }

        Mail::to($email)->queue(new MailVerifyCode($code));

        return response()->json([
            'success' => true,
            'message' => 'verify code send successfully'
        ]);
    }

    public function loginStep2(Request $request)
    {

        $validated = $request->validate(['code' => 'required|array|size:6']);
        $code = implode($validated['code']);
        $codeRecord = EmailVerifyCode::query()->where('code', $code)->where('updated_at', '>=', Carbon::now()->subMinutes(5))->first();

        if (!$codeRecord) {
            return response()->json([
                'success' => false,
                'message' => 'verify code invalid'
            ]);
        }

        if (!Auth::loginUsingId($codeRecord->user_id, true)) {
            return response()->json([
                'success' => false,
                'message' => 'The provided credentials are incorrect'
            ]);

        }

        $request->session()->regenerate();

        return response()->json([
            'success' => true,
            'message' => 'Logged in successfully'
        ]);
    }

    public function VerifyEmailStep1(Request $request)
    {
        $email = $request->validate(['email' => 'required|email|max:250|unique:users,email'])['email'];
        $code = Str::random(6);
        Gate::authorize('sendVerifyCode', [EmailVerifyCode::class, $email]);
        $codeRecord = $request->user()->emailVerifyCodes->firstWhere('email', $email);
        if ($codeRecord) {
            $codeRecord->code = $code;
            $codeRecord->save();
        } else {
            EmailVerifyCode::create(['code' => $code, 'email' => $email, 'user_id' => $request->user()->id]);
        }

        Mail::to($email)->queue(new MailVerifyCode($code));

        return response()->json([
            'success' => true,
            'message' => 'verify code send successfully'
        ]);
    }

    public function VerifyEmailStep2(Request $request)
    {
        $validated = $request->validate(['code' => 'required|array|size:6']);
        $code = implode($validated['code']);
        $codeRecord = $request->user()->emailVerifyCodes->where('code', $code)->where('updated_at', '>=', Carbon::now()->subMinutes(5))->first();

        if (!$codeRecord) {
            return response()->json([
                'success' => false,
                'message' => 'verify code invalid'
            ]);
        }

        $request->user()->email = $codeRecord->email;
        $request->user()->save();

        return response()->json([
            'success' => true,
            'message' => 'Email verified successfully'
        ]);

    }

    public function removeEmail(Request $request)
    {
        $request->user()->email = null;
        $request->user()->save();

        return response()->json([
            'success' => true,
            'message' => 'Email removed successfully'
        ]);

    }

    public function removeAccount(Request $request)
    {
        $request->user()->delete();

        return response()->json([
            'success' => true,
            'message' => 'Account removed successfully'
        ]);

    }
}
