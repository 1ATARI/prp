<?php

use App\Http\Controllers\PostController;
use App\Http\Controllers\SubscriptionController;
use App\Http\Controllers\WebsiteController;
use Illuminate\Support\Facades\Route;

Route::prefix('v1')->group(function () {

    Route::get('/websites', [WebsiteController::class, 'index']);
    Route::get('/websites/{website}', [WebsiteController::class, 'show']);
    Route::post('/websites', [WebsiteController::class, 'store']);


    Route::get('/posts', [PostController::class, 'index']);
    Route::get('/posts/{post}', [PostController::class, 'show']);
    Route::post('/posts/{website}', [PostController::class, 'store']);


    Route::get('/subscriptions', [SubscriptionController::class, 'index']);
    Route::post('/subscriptions', [SubscriptionController::class, 'store']);
    Route::delete('/subscriptions/{website}', [SubscriptionController::class, 'destroy']);
});
