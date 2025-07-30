<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Website;
use App\Models\Subscription;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;

class SubscriptionController extends Controller
{
    public function store( Website $website, Request $request): JsonResponse
    {
        $request->validate([
            'user_email' => 'required|email',
            'user_name' => 'required|string|max:255',

        ]);

        $user = User::firstOrCreate(
            ['email' => $request->user_email],
            [
                'name' => $request->user_name,
                'password' => Hash::make(Str::random(10))
            ]
        );

        $existingSubscription = $website->subscriptions()
            ->where('user_id', $user->id)
            ->first();

        if ($existingSubscription) {
            return response()->json([
                'message' => 'User is already subscribed to this website',
                'data' => $existingSubscription->load(['user', 'website'])
            ], 409);
        }

        $subscription = $website->subscriptions()->create([
            'user_id' => $user->id
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

    public function destroy( Website $website,Request $request): JsonResponse
    {
        $request->validate([
            'user_email' => 'required|email|exists:users,email',
        ]);

        $user = User::where('email', $request->user_email)->first();

        $subscription = $website->subscriptions()->where('user_id', $user->id)->first();

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
    public function websiteSubscriptions(Website $website ): JsonResponse
    {
        $subscriptions = $website->subscriptions()->with(['user', 'website'])->get();

        return response()->json([
            'message' => 'Subscriptions for website retrieved successfully',
            'data' => $subscriptions
        ]);

    }
}
