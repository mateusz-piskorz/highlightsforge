<?php

use App\Http\Controllers\Api\ClipController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

Route::get('/clip', [ClipController::class, 'index'])->name('clip-index');
Route::post('/clip', [ClipController::class, 'store'])->name('clip-store');
