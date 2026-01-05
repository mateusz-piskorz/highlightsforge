<?php

namespace App\Http\Controllers\Api;

use App\Jobs\ProcessPost;
use App\Models\Comment;
use App\Models\Post;
use App\Models\PostUpvote;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Storage;

class PostController
{
    public function index(Request $request)
    {
        $validated = $request->validate(['status' => 'nullable|in:pending,draft,published', 'authorId' => 'nullable|integer', 'limit' => 'nullable|integer', 'q' => 'string|nullable', 'sorting' => 'in:featured,new-posts,latest-activity|nullable']);
        $limit = $request->input('limit', 20);

        $query = Post::query();
        $q = $validated['q'] ?? null;
        $sorting = $validated['sorting'] ?? null;
        $status = $validated['status'] ?? null;

        if ($authorId = $validated['authorId'] ?? null) {$query->where('user_id', $authorId);}
        if ($q) {$query->where('title', 'ilike', "%{$q}%");}
        if ($status) {$query->where('status', $status);}

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

        return response()->json($query->paginate($limit));
    }

    public function store(Request $request)
    {

        $validated = $request->validate(['file' => 'required|file|max:102400|mimetypes:video/mp4,video/webm,image/jpeg,image/png,image/webp', 'title' => 'required|string', 'description' => 'nullable|string']);
        $file = $request->file('file');
        $path = $file->store('posts', 'public');

        $post = Post::create(['user_id' => $request->user()->id, 'title' => $validated['title'], 'description' => $validated['description'], 'file_path' => $path, 'file_type' => $file->getMimeType()]);

        ProcessPost::dispatch($post);

        return response()->json([
            'success' => true,
            'message' => 'Post stored successfully'
        ]);
    }

    public function update(Request $request, Post $post)
    {
        Gate::authorize('update', $post);

        $validated = $request->validate(['file' => 'sometimes|nullable|file|max:102400|mimetypes:video/mp4,video/webm,image/jpeg,image/png,image/webp', 'title' => 'nullable|string', 'description' => 'nullable|string']);

        if ($request->hasFile('file')) {
            $file = $request->file('file');
            Storage::delete($post->file_path);
            $path = $file->store('posts', 'public');
            $post->file_path = $path;
            $post->file_type = $file->getMimeType();
            $post->status = 'pending';
        }

        if ($validated['title']) {
            $post->title = $validated['title'];
        }

        if ($validated['description']) {
            $post->description = $validated['description'];
        }

        $post->save();

        if ($request->hasFile('file')) {ProcessPost::dispatch($post);}

        return response()->json([
            'success' => true,
            'message' => 'Post updated successfully'
        ]);
    }

    public function delete(Post $post)
    {
        Gate::authorize('delete', $post);

        Storage::delete($post->file_path);

        $post->delete();

        return response()->json([
            'success' => true,
            'message' => 'Post deleted successfully'
        ]);

    }

    public function status(Request $request, Post $post)
    {
        Gate::authorize('update', $post);

        $validated = $request->validate(['status' => 'required|in:draft,published']);

        if ($post->status === 'pending') {
            return response()->json([
                'success' => false,
                'message' => 'Post is in pending status'
            ]);
        }

        $post->status = $validated['status'];
        $post->save();

        return response()->json([
            'success' => true,
            'message' => 'Post status updated successfully'
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

}
