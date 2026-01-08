<?php

namespace App\Policies;

use App\Models\Post;
use App\Models\User;
use Illuminate\Auth\Access\Response;

class PostPolicy
{
    public function update(User $user, Post $post): bool
    {
        return $user->id === $post->user_id;
    }

    public function delete(User $user, Post $post): bool
    {
        return $user->id === $post->user_id;
    }

    public function create(User $user): Response
    {

        $lastPost = $user->posts()->where('created_at', '>', now()->subMinutes(2))->first();

        if ($lastPost) {return Response::deny('You need to wait 2 minutes before creating another post');}

        return Response::allow();
    }
}
