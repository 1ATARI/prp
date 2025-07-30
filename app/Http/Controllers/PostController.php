<?php

namespace App\Http\Controllers;

use App\Jobs\SendPostNotificationJob;
use App\Models\Post;
use App\Models\Website;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
class PostController extends Controller
{
    public function store(Website $website , Request $request ): JsonResponse
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'content' => 'nullable|string',
        ]);

        $post =  $website->posts()->create([
            'title' => $request->title,
            'content' => $request['content'],
        ]);


        //SendPostNotificationJob::dispatch($post);

        return response()->json([
            'message' => 'Post created successfully',
            'post' => $post->load('website')
        ], 201);
    }

    public function index(): JsonResponse
    {
        $posts = Post::with('website')->latest()->get();

        return response()->json([
            'message' => 'Posts retrieved successfully',
            'data' => $posts
        ]);
    }

    public function show(Post $post): JsonResponse
    {
        return response()->json([
            'message' => 'Post retrieved successfully',
            'data' => $post->load('website')
        ]);
    }
    public function destroy(Post $post): JsonResponse
    {
        $post->delete();

        return response()->json([
            'message' => 'Post deleted successfully',
        ], 204);
    }
    public function update(Post $post, Request $request): JsonResponse
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'content' => 'nullable|string',
        ]);

        $post->update($request->all());

        return response()->json([
            'message' => 'Post updated successfully',
            'data' => $post->load('website')
        ]);
    }
}
