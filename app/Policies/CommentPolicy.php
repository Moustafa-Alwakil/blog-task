<?php

namespace App\Policies;

use App\Models\Comment;
use App\Models\User;
use Illuminate\Auth\Access\Response;

class CommentPolicy
{
    public function create(User $user): bool
    {
        return true;
    }

    public function update(User $user, Comment $comment): bool
    {
        return (int)$comment->user_id === (int)$user->id;
    }

    public function delete(User $user, Comment $comment): bool
    {
        return (int)$comment->user_id === (int)$user->id;
    }
}
