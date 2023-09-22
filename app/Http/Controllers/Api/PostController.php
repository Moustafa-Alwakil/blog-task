<?php

namespace App\Http\Controllers\Api;

use App\Models\Post;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Resources\PostResource;

class PostController extends Controller
{
    public function all(Request $request)
    {
        return PostResource::collection(
            Post::query()
                ->with('user', 'comments.user')
                ->withCount('comments')
                ->paginate()
        );
    }

    public function single(Post $post)
    {
        return new PostResource($post->load('user', 'comments.user')->loadCount('comments'));
    }
}
