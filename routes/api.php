<?php

use App\Http\Controllers\Api\ClipController;
use App\Http\Controllers\Api\ProfileController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::get('/user', function (Request $request) {
    return response()->json([
        'user'    => $request->user(),
        'session' => $request->session()
    ]);
})->middleware('auth:sanctum');

Route::get('/user-sessions', fn(Request $request) => $request->user()->sessions)->middleware('auth:sanctum');

Route::get('/clip', [ClipController::class, 'index'])->name('clip-index');
Route::post('/clip', [ClipController::class, 'store'])->name('clip-store');
Route::post('/sendSomething', [ClipController::class, 'sendSomething'])->name('sendSomething');

// profile & auth
Route::post('/register', [ProfileController::class, 'register']);
Route::post('/loginStep1', [ProfileController::class, 'loginStep1']);
Route::post('/loginStep2', [ProfileController::class, 'loginStep2']);
Route::post('/updateProfile', [ProfileController::class, 'updateProfile'])->middleware(['auth:sanctum']);
Route::post('/verifyEmailStep1', [ProfileController::class, 'VerifyEmailStep1'])->middleware(['auth:sanctum']);
Route::post('/verifyEmailStep2', [ProfileController::class, 'VerifyEmailStep2'])->middleware(['auth:sanctum']);
