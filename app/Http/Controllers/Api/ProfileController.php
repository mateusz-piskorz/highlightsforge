<?php

namespace App\Http\Controllers\Api;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class ProfileController
{
    public function updateProfile(Request $request)
    {
        $validated = $request->validate(['user_name' => 'required|string|min:3|max:250']);
        $request->user()->update($validated);

        return response()->json([
            'success' => true,
            'message' => 'Profile updated successfully'
        ]);

    }

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
            'user'    => $request->user()->only(['id', 'name', 'email'])
        ]);
    }

    public function uploadAvatar(Request $request)
    {

        $file = $request->file('avatar');
        $path = $file->store('avatars', 'public');

        $request->user()->avatar = "storage/$path";
        $request->user()->save();

        return response()->json([
            'success' => true,
            'message' => 'Avatar updated successfully'
        ]);
    }

    public function removeAvatar(Request $request)
    {
        Storage::delete($request->user()->avatar);

        $request->user()->avatar = null;
        $request->user()->save();

        return response()->json([
            'success' => true,
            'message' => 'Avatar removed successfully'
        ]);
    }

}
