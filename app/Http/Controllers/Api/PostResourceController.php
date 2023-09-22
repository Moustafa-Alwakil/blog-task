<?php

namespace App\Http\Controllers\Api;

use App\Models\Post;
use App\Models\User;
use App\Http\Controllers\Controller;
use App\Http\Requests\Api\Post\StorePostRequest;
use App\Http\Requests\Api\Post\UpdatePostRequest;
use App\Http\Resources\PostResource;

class PostResourceController extends Controller
{
    public function __construct()
    {
        parent::__construct();

        $this->authorizeResource(Post::class, 'post');
    }

    public function index()
    {
        return PostResource::collection(
            Post::query()
                ->where('user_id', auth('sanctum')->id())
                ->with('comments')
                ->withCount('comments')
                ->paginate()
        );
    }

    public function store(StorePostRequest $request)
    {
        /** @var User $user */
        $user = auth('sanctum')->user();

        $post = $user->posts()->create([
            'title' => $request->get('title'),
            'body' => $request->get('body'),
        ]);

        return response()->json([
            'message' => 'Your Post Has Been Created Successfully.',
            'post' => new PostResource($post)
        ]);
    }

    public function update(UpdatePostRequest $request, Post $post)
    {
        $post->title = $request->get('title');
        $post->body = $request->get('body');

        $post->save();

        return response()->json([
            'message' => 'Your Post Has Been Updated Successfully.',
            'post' => new PostResource($post)
        ]);
    }

    public function destroy(Post $post)
    {
        $id = $post->id;
        $post->delete();

        return response()->json([
            'message' => 'Your Post Has Been Deleted Successfully.',
            'id' => $id
        ]);
    }
}
