<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Facades\Auth;

class Comment extends Model
{

    protected $appends = ['upvoted'];

    protected $fillable = [
        'user_id',
        'parent_id',
        'content',
        'deleted'
    ];

    protected function upvoted(): Attribute
    {
        return Attribute::make(
            get: function () {
                if (!Auth::check()) {
                    return false;
                }

                return $this->upvotes()->where('user_id', Auth::id())->exists();
            },
        );
    }

    public function upvotedModel(): CommentUpvote | null
    {
        if (!Auth::check()) {
            return null;
        }

        return $this->upvotes()->where('user_id', Auth::id())->first();
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function upvotes(): HasMany
    {
        return $this->hasMany(CommentUpvote::class);
    }

    public function parent(): BelongsTo
    {
        return $this->belongsTo(Comment::class, 'parent_id');
    }

    public function replies(): HasMany
    {
        return $this->hasMany(Comment::class, 'parent_id');
    }

    public function deleteParentRecursively()
    {

        if ($this->deleted && $this->replies()->count() === 0) {
            $parent = $this->parent;
            $this->delete();

            if ($parent) {
                $parent->deleteParentRecursively();
            }
        }
    }

}
