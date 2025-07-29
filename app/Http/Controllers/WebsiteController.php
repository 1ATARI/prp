<?php

namespace App\Http\Controllers;

use App\Models\Website;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
class WebsiteController extends Controller
{
    public function index(): JsonResponse
    {
        $websites = Website::withCount('subscriptions')->get();

        return response()->json([
            'message' => 'Websites retrieved successfully',
            'data' => $websites
        ]);
    }

    public function show(Website $website): JsonResponse
    {
        return response()->json([
            'message' => 'Website retrieved successfully',
            'data' => $website->load(['posts', 'subscriptions.user'])
        ]);
    }

    public function store(Request $request): JsonResponse
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'url' => 'required|url|unique:websites,url',
            'description' => 'nullable|string',
        ]);

        $website = Website::create($request->all());

        return response()->json([
            'message' => 'Website created successfully',
            'data' => $website
        ], 201);
    }
}
