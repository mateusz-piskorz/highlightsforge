<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Facades\Auth;

class Post extends Model
{
    protected $appends = ['upvoted'];

    protected $fillable = [
        'user_id',
        'title',
        'file_path',
        'file_type'
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

    public function upvotedModel(): PostUpvote | null
    {
        if (!Auth::check()) {
            return null;
        }

        return $this->upvotes()->where('user_id', Auth::id())->first();
    }

    public function upvotes(): HasMany
    {
        return $this->hasMany(PostUpvote::class);
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function comments(): HasMany
    {
        return $this->hasMany(Comment::class);
    }
}
