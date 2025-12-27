<?php

namespace App\Http\Controllers\Api;

use App\Models\Comment;
use App\Models\Post;
use App\Models\PostUpvote;
use Illuminate\Http\Request;

class PostController
{
    public function index(Request $request)
    {
        $validated = $request->validate(['q' => 'string|nullable', 'sorting' => 'in:featured,new-posts,latest-activity|nullable']);

        $query = Post::query();
        $q = $validated['q'] ?? null;
        $sorting = $validated['sorting'] ?? null;
        if ($q) {
            $query->where('title', 'ilike', "%{$q}%");
        }

        $query->with('user')->withCount('comments')->withCount('upvotes');

        if ($sorting) {
            if ($sorting === 'featured') {
                $query->orderBy('upvotes_count', 'desc');
            } else if ($sorting === 'new-posts') {
                $query->latest();
            } else if ($sorting === 'latest-activity') {
                $query->orderByRaw(
                    '(' . Comment::select('created_at')
                        ->whereColumn('post_id', 'posts.id')
                        ->latest()
                        ->limit(1)
                        ->toSql() . ') DESC NULLS LAST'
                );
            }
        }

        $posts = $query->get();

        return response()->json([
            'posts' => $posts
        ]);
    }

    public function upvote(Request $request, Post $post)
    {
        $upvote = $post->upvotedModel();

        if ($upvote) {
            $upvote->delete();
        } else {
            PostUpvote::create(['user_id' => $request->user()->id, 'post_id' => $post->id]);
        }

        return response()->json([
            'success' => true,
            'message' => 'Post upvoted successfully'
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
