<?php

namespace App\Http\Controllers;

use App\Jobs\SendPostNotificationJob;
use App\Models\Post;
use App\Models\Website;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
class PostController extends Controller
{
    public function store(Request $request , Website $website): JsonResponse
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'required|string',
            'content' => 'nullable|string',
        ]);

        $post =  $website->posts()->create([
            'title' => $request->title,
            'description' => $request->description,
            'content' => $request['content'],
            'published_at' => now(),
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
}
