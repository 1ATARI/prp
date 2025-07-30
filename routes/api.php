<?php

use App\Http\Controllers\PostController;
use App\Http\Controllers\SubscriptionController;
use App\Http\Controllers\WebsiteController;
use Illuminate\Support\Facades\Route;

Route::prefix('v1')->group(function () {

    Route::get('/websites', [WebsiteController::class, 'index']);
    Route::get('/websites/{website}', [WebsiteController::class, 'show']);
    Route::post('/websites', [WebsiteController::class, 'store']);
    Route::delete('/websites/{website}', [WebsiteController::class, 'destroy']);
    Route::put('/websites/{website}', [WebsiteController::class, 'update']);


    Route::get('/posts', [PostController::class, 'index']);
    Route::get('/posts/{post}', [PostController::class, 'show']);
    Route::post('website/{website}/posts', [PostController::class, 'store']);
    Route::put('/posts/{post}', [PostController::class, 'update']);
    Route::delete('/posts/{post}', [PostController::class, 'destroy']);

    Route::get('/subscriptions', [SubscriptionController::class, 'index']);
    Route::get('/website/{website}/subscriptions', [SubscriptionController::class, 'websiteSubscriptions']);
    Route::post('/website/{website}/subscriptions', [SubscriptionController::class, 'store']);
    Route::delete('/website/{website}/subscriptions', [SubscriptionController::class, 'destroy']);
});
