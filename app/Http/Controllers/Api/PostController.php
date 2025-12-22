<?php

namespace App\Http\Controllers\Api;

use App\Models\Post;
use Illuminate\Http\Request;

class PostController
{
    public function index(Request $request)
    {
        $validated = $request->validate(['q' => 'string']);

        $query = Post::query();
        $q = $validated['q'] ?? null;
        if ($q) {
            $query->where('title', 'ilike', "%{$q}%");
        }

        $posts = $query->with('user')->withCount('comments')->get();

        return response()->json([
            'posts' => $posts
        ]);

    }

    public function store(Request $request)
    {
        $validated = $request->validate(['file' => 'required|file|max:102400|mimetypes:video/mp4,video/webm', 'title' => 'required|string']);
        $file = $request->file('file');
        $path = $file->store('posts', 'public');

        Post::create(['user_id' => $request->user()->id, 'title' => $validated['title'], 'file_path' => $path, 'file_type' => $file->getMimeType()]);

        return response()->json([
            'success' => true,
            'message' => 'Post stored successfully'
        ]);
    }

}
