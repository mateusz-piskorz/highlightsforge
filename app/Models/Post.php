<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Facades\Auth;

class Post extends Model
{

    protected $appends = ['upvoted', 'reported', 'report_threshold'];
    protected $fillable = [
        'user_id',
        'title',
        'status',
        'approved_x_times',
        'description',
        'file_path',
        'file_type'
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function comments(): HasMany
    {
        return $this->hasMany(Comment::class);
    }

    // upvotes
    public function upvotes(): HasMany
    {
        return $this->hasMany(PostUpvote::class);
    }

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

    //reports
    public function reports(): HasMany
    {
        return $this->hasMany(PostReport::class);
    }

    protected function reported(): Attribute
    {
        return Attribute::make(
            get: function () {
                if (!Auth::check()) {
                    return false;
                }

                return $this->reports()->where('user_id', Auth::id())->exists();
            },
        );
    }

    public function reportedModel(): PostReport | null
    {
        if (!Auth::check()) {
            return null;
        }

        return $this->reports()->where('user_id', Auth::id())->first();
    }

    protected function reportThreshold(): Attribute
    {
        return Attribute::make(
            get: function (mixed $value, array $attributes) {
                $approvals = $attributes['approved_x_times'] ?? 0;
                if ($approvals == 0) {return 5;}
                return 50 * $approvals;
            },
        );
    }
}
