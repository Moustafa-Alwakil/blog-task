<?php

namespace Tests\Traits;

use Illuminate\Testing\TestResponse;
use App\Models\Post;

trait InteractsWithPost
{
    public function assertSeeAllPosts($count): void
    {
        $this->json('GET', '/api')
            ->assertOk()
            ->assertJsonFragment([
                'total' => $count
            ]);
    }

    public function requestSinglePostApi(Post $post): TestResponse
    {
        return $this->json('GET', "/api/post/$post->slug");
    }

    public function storePost(): TestResponse
    {
        return $this->json('POST', '/api/posts', [
            'body' => fake()->realTextBetween(50, 100),
            'title' => fake()->realTextBetween(5, 10),
        ]);
    }

    public function updatePost(Post $post): TestResponse
    {
        return $this->json('PUT', "/api/posts/$post->id", [
            'body' => fake()->realTextBetween(50, 100),
            'title' => fake()->realTextBetween(5, 10),
        ]);
    }

    public function deletePost(Post $post): TestResponse
    {
        return $this->json('DELETE', "/api/posts/$post->id");
    }

    public function requestMyPostsApi(): TestResponse
    {
        return $this->json('GET', '/api/posts');
    }
}
