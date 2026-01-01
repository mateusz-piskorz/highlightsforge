<?php

use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\CommentController;
use App\Http\Controllers\Api\PostController;
use App\Http\Controllers\Api\ProfileController;
use Illuminate\Support\Facades\Route;

// posts
Route::get('/posts', [PostController::class, 'index']);
Route::post('/posts', [PostController::class, 'store'])->middleware(['auth:sanctum']);
Route::put('/posts/{post}', [PostController::class, 'update'])->middleware(['auth:sanctum']);
Route::post('/posts/{post}/upvote', [PostController::class, 'upvote'])->middleware(['auth:sanctum']);

// comments
Route::get('/comments', [CommentController::class, 'index']);
Route::post('/comments', [CommentController::class, 'store'])->middleware(['auth:sanctum']);
Route::put('/comments/{comment}', [CommentController::class, 'update'])->middleware(['auth:sanctum']);
Route::delete('/comments/{comment}', [CommentController::class, 'delete'])->middleware(['auth:sanctum']);
Route::post('/comments/{comment}/upvote', [CommentController::class, 'upvote'])->middleware(['auth:sanctum']);

// profile
Route::post('/upload-avatar', [ProfileController::class, 'uploadAvatar'])->middleware(['auth:sanctum']);
Route::delete('/remove-avatar', [ProfileController::class, 'removeAvatar'])->middleware(['auth:sanctum']);
Route::post('/update-profile', [ProfileController::class, 'updateProfile'])->middleware(['auth:sanctum']);

// auth
Route::post('/register', [AuthController::class, 'register']);
Route::post('/login-step-1', [AuthController::class, 'loginStep1']);
Route::post('/login-step-2', [AuthController::class, 'loginStep2']);
Route::post('/verify-email-step-1', [AuthController::class, 'VerifyEmailStep1'])->middleware(['auth:sanctum']);
Route::post('/verify-email-step-2', [AuthController::class, 'VerifyEmailStep2'])->middleware(['auth:sanctum']);
Route::delete('/remove-email', [AuthController::class, 'removeEmail'])->middleware(['auth:sanctum']);
Route::delete('/remove-account', [AuthController::class, 'removeAccount'])->middleware(['auth:sanctum']);
