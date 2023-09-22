<?php

namespace App\Http\Controllers\Api;

use App\Models\Comment;
use App\Http\Controllers\Controller;
use App\Http\Resources\CommentResource;
use App\Http\Requests\Api\Comment\StoreCommentRequest;
use App\Http\Requests\Api\Comment\UpdateCommentRequest;

class CommentResourceController extends Controller
{
    public function __construct()
    {
        parent::__construct();

        $this->authorizeResource(Comment::class, 'comment');
    }

    public function store(StoreCommentRequest $request)
    {
        $comment = new Comment;

        $comment->body = $request->get('body');
        $comment->post_id = $request->get('post_id');
        $comment->user_id = auth()->id();

        $comment->save();

        return response()->json([
            'message' => 'Your Comment Has Been Added Successfully.',
            'comment' => new CommentResource($comment->load('post', 'user'))
        ]);
    }

    public function update(UpdateCommentRequest $request, Comment $comment)
    {
        $comment->body = $request->get('body');

        $comment->save();

        return response()->json([
            'message' => 'Your Comment Has Been Updated Successfully.',
            'comment' => new CommentResource($comment->load('post', 'user'))
        ]);
    }

    public function destroy(Comment $comment)
    {
        $id = $comment->id;
        $comment->delete();

        return response()->json([
            'message' => 'Your Comment Has Been Deleted Successfully.',
            'id' => $id
        ]);
    }
}
