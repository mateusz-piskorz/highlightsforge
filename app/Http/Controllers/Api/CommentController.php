<?php

namespace App\Http\Controllers\Api;

use App\Models\Comment;
use App\Models\CommentUpvote;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;

class CommentController
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $validated = $request->validate(['limit' => 'nullable|integer', "parentId" => "nullable|integer", "postId" => "integer"]);
        $parentId = $validated['parentId'] ?? null;
        $limit = $request->input('limit', 25);
        return response()->json(Comment::query()->where('post_id', $validated['postId'])->where('parent_id', $parentId)->with(['user'])->withCount('replies')->withCount('upvotes')->paginate($limit));
    }

    public function store(Request $request)
    {
        $validated = $request->validate(['content' => 'required|string|min:3|max:250', 'parent_id' => 'nullable|integer', "post_id" => 'required|integer']);
        Comment::create([ ...$validated, 'user_id' => $request->user()->id]);
        return response()->json([
            'success' => true,
            'message' => 'Comment added successfully'
        ]);
    }

    public function update(Request $request, Comment $comment)
    {
        Gate::authorize('update', $comment);
        $validated = $request->validate(['content' => 'required|string|min:3|max:250']);
        $comment->content = $validated['content'];
        $comment->save();

        return response()->json([
            'success' => true,
            'message' => 'Comment updated successfully'
        ]);
    }

    public function upvote(Request $request, Comment $comment)
    {
        $upvote = $comment->upvotedModel();

        if ($upvote) {
            $upvote->delete();
        } else {
            CommentUpvote::create(['user_id' => $request->user()->id, 'comment_id' => $comment->id]);
        }

        return response()->json([
            'success' => true,
            'message' => 'Comment upvoted successfully'
        ]);
    }

    public function delete(Comment $comment)
    {
        Gate::authorize('delete', $comment);

        if ($comment->replies()->count() === 0) {
            $parentId = $comment->parent_id;
            $comment->delete();

            if ($parentId) {
                $parent = Comment::find($parentId);
                if ($parent) {
                    $parent->deleteParentRecursively();
                }
            }
        } else {
            $comment->update(['deleted' => true]);
        }

        return response()->json([
            'success' => true,
            'message' => 'Comment deleted successfully'
        ]);

    }
}
