<?php

namespace Tests\Traits;

use Illuminate\Testing\TestResponse;
use App\Models\Comment;
use App\Models\Post;

trait InteractsWithComment
{
    public function storeComment(): TestResponse
    {
        return $this->json('POST', 'api/comments', [
            'body' => fake()->sentence(),
            'post_id' => Post::factory()->create()->id
        ]);
    }

    public function updateComment(Comment $comment): TestResponse
    {
        return $this->json('PUT', "api/comments/$comment->id", [
            'body' => fake()->sentence(),
        ]);
    }

    public function deleteComment(Comment $comment): TestResponse
    {
        return $this->json('DELETE', "api/comments/$comment->id");
    }
}
