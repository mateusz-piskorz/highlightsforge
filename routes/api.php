<?php

use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\ClipController;
use App\Http\Controllers\Api\ProfileController;
use Illuminate\Support\Facades\Route;

// clips
Route::get('/clip', [ClipController::class, 'index']);
Route::post('/clip', [ClipController::class, 'store']);
Route::post('/sendSomething', [ClipController::class, 'sendSomething']);

// profile
Route::post('/register', [ProfileController::class, 'register']);
Route::post('/upload-avatar', [ProfileController::class, 'uploadAvatar']);
Route::delete('/remove-avatar', [ProfileController::class, 'removeAvatar']);
Route::post('/update-profile', [ProfileController::class, 'updateProfile'])->middleware(['auth:sanctum']);

// auth
Route::post('/login-step-1', [AuthController::class, 'loginStep1']);
Route::post('/login-step-2', [AuthController::class, 'loginStep2']);
Route::post('/verify-email-step-1', [AuthController::class, 'VerifyEmailStep1'])->middleware(['auth:sanctum']);
Route::post('/verify-email-step-2', [AuthController::class, 'VerifyEmailStep2'])->middleware(['auth:sanctum']);
Route::delete('/remove-email', [AuthController::class, 'removeEmail'])->middleware(['auth:sanctum']);
Route::delete('/remove-account', [AuthController::class, 'removeAccount'])->middleware(['auth:sanctum']);
