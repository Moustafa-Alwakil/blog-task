<?php

namespace Tests\Feature;

use Tests\Traits\InteractsWithComment;
use App\Models\Comment;
use App\Models\Post;
use App\Models\User;
use Tests\TestCase;

class CommentTest extends TestCase
{
    use InteractsWithComment;

    public function test_that_only_logged_in_user_can_create_comment(): void
    {
        $this->storeComment()
            ->assertUnauthorized();

        $this->actingAs(User::factory()->create(), 'sanctum');

        $this->storeComment()
            ->assertSessionDoesntHaveErrors()
            ->assertSuccessful()
            ->assertJsonStructure([
                'message',
                'comment'
            ]);
    }

    public function test_that_only_logged_in_user_can_edit_his_comment(): void
    {
        $user = User::factory()->create();
        $comment = Comment::factory()->for($user, 'user')->create();

        $this->updateComment($comment)
            ->assertUnauthorized();

        $anotherUser = User::factory()->create();
        $this->actingAs($anotherUser, 'sanctum');

        $this->updateComment($comment)
            ->assertForbidden();

        $this->actingAs($user, 'sanctum');

        $this->updateComment($comment)
            ->assertSessionDoesntHaveErrors()
            ->assertJsonStructure([
                'message',
                'comment'
            ]);
    }

    public function test_that_only_comment_creator_can_delete_comment(): void
    {
        $postCreator = User::factory()->create();
        $post = Post::factory()->for($postCreator, 'user')->create();

        $commentCreator = User::factory()->create();
        $comments = Comment::factory(2)->for($commentCreator, 'user')->for($post, 'post')->create();

        $randomUser = User::factory()->create();

        $this->deleteComment($comments[0])
            ->assertUnauthorized();

        $this->actingAs($randomUser, 'sanctum');

        $this->deleteComment($comments[0])
            ->assertForbidden();

        $this->actingAs($commentCreator, 'sanctum');

        $this->deleteComment($comments[0])
            ->assertSuccessful()
            ->assertJsonStructure([
                'message',
                'id'
            ]);

        $this->assertDatabaseMissing(Comment::class, $comments[0]->toArray());
    }
}
