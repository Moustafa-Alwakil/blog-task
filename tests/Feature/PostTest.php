<?php

namespace Tests\Feature;

use Tests\Traits\InteractsWithPost;
use App\Models\Post;
use App\Models\User;
use Tests\TestCase;

class PostTest extends TestCase
{
    use InteractsWithPost;

    public function test_that_users_or_guests_can_see_all_posts(): void
    {
        Post::factory($count = 10)->create();

        $this->assertSeeAllPosts($count);

        $this->actingAs(User::factory()->create(), 'sanctum');

        $this->assertSeeAllPosts($count);
    }

    public function test_that_users_or_can_see_any_single_post(): void
    {
        $posts = Post::factory(10)->create();

        $this->requestSinglePostApi($posts->shuffle()->first())
            ->assertOk();

        $this->actingAs(User::factory()->create(), 'sanctum');

        $this->requestSinglePostApi($posts->shuffle()->first())
            ->assertOk();
    }

    public function test_that_only_logged_in_user_can_create_post(): void
    {
        $this->storePost()
            ->assertUnauthorized();

        $this->actingAs(User::factory()->create(), 'sanctum');

        $this->storePost()
            ->assertSessionDoesntHaveErrors()
            ->assertJsonStructure([
                'message',
                'post',
            ]);

        $this->assertDatabaseHas(Post::class, Post::query()->latest()->first()->toArray());
    }

    public function test_that_only_logged_in_user_can_update_his_post(): void
    {
        $user = User::factory()->create();

        $post = Post::factory()->for($user, 'user')->create();

        $this->updatePost($post)
            ->assertUnauthorized();

        $anotherUser = User::factory()->create();
        $this->actingAs($anotherUser, 'sanctum');

        $this->updatePost($post)
            ->assertForbidden();

        $this->actingAs($user, 'sanctum');

        $this->updatePost($post)
            ->assertSessionDoesntHaveErrors()
            ->assertJsonStructure([
                'message',
                'post',
            ]);
    }

    public function test_that_only_logged_in_user_can_delete_his_post(): void
    {
        $user = User::factory()->create();

        $post = Post::factory()->for($user, 'user')->create();

        $this->deletePost($post)
            ->assertUnauthorized();

        $anotherUser = User::factory()->create();
        $this->actingAs($anotherUser, 'sanctum');

        $this->deletePost($post)
            ->assertForbidden();

        $this->actingAs($user, 'sanctum');

        $this->deletePost($post)
            ->assertSuccessful()
            ->assertJsonStructure([
                'message',
                'id'
            ]);

        $this->assertDatabaseMissing(Post::class, $post->toArray());
    }

    public function test_that_only_logged_in_user_can_see_his_posts_in_my_posts(): void
    {
        $user = User::factory()->create();
        Post::factory($countForUser = 10)->for($user, 'user')->create();


        $anotherUser = User::factory()->create();
        Post::factory(10)->for($anotherUser, 'user')->create();

        $this->actingAs($user);

        $this->requestMyPostsApi()
            ->assertOk()
            ->assertJsonFragment([
                'total' => $countForUser,
            ]);
    }
}
