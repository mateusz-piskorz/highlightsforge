<?php

namespace App\Http\Controllers\Api;

use App\Models\Clip;
use Illuminate\Http\Request;

class ClipController
{
    public function index(Request $request)
    {
        $clips = Clip::query()->with('user')->get();
        // Clip::create(['user_id' => $request->user()->id, 'title' => $validated['title'], 'clip_path' => $path]);
        return response()->json([
            'clips' => $clips
        ]);
    }

    public function store(Request $request)
    {
        $validated = $request->validate(['clip' => 'required|file|max:102400|mimetypes:video/mp4,video/webm', 'title' => 'required|string']);
        $file = $request->file('clip');
        $path = $file->store('clips', 'public');

        Clip::create(['user_id' => $request->user()->id, 'title' => $validated['title'], 'file_path' => $path, 'file_type' => $file->getMimeType()]);

        return response()->json([
            'success' => true,
            'message' => 'Clip stored successfully'
        ]);
    }
}
