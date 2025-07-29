<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Website;
use App\Models\Subscription;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Validation\Rule;
class SubscriptionController extends Controller
{
    public function store(Request $request): JsonResponse
    {
        $request->validate([
            'user_email' => 'required|email',
            'user_name' => 'required|string|max:255',
            'website_id' => 'required|exists:websites,id',
        ]);

        // Get or create user
        $user = User::firstOrCreate(
            ['email' => $request->user_email],
            ['name' => $request->user_name]
        );

        // Check if subscription already exists
        $existingSubscription = Subscription::where('user_id', $user->id)
            ->where('website_id', $request->website_id)
            ->first();

        if ($existingSubscription) {
            return response()->json([
                'message' => 'User is already subscribed to this website',
                'data' => $existingSubscription->load(['user', 'website'])
            ], 409);
        }

        // Create subscription
        $subscription = Subscription::create([
            'user_id' => $user->id,
            'website_id' => $request->website_id,
        ]);

        return response()->json([
            'message' => 'Subscription created successfully',
            'data' => $subscription->load(['user', 'website'])
        ], 201);
    }

    public function index(): JsonResponse
    {
        $subscriptions = Subscription::with(['user', 'website'])->get();

        return response()->json([
            'message' => 'Subscriptions retrieved successfully',
            'data' => $subscriptions
        ]);
    }

    public function destroy(Request $request): JsonResponse
    {
        $request->validate([
            'user_email' => 'required|email|exists:users,email',
            'website_id' => 'required|exists:websites,id',
        ]);

        $user = User::where('email', $request->user_email)->first();

        $subscription = Subscription::where('user_id', $user->id)
            ->where('website_id', $request->website_id)
            ->first();

        if (!$subscription) {
            return response()->json([
                'message' => 'Subscription not found'
            ], 404);
        }

        $subscription->delete();

        return response()->json([
            'message' => 'Subscription deleted successfully'
        ]);
    }
}
