<?php

namespace App\Http\Controllers\Api;

use App\Models\User;
use Illuminate\Http\Request;
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
