<?php

use Illuminate\Support\Facades\Route;
use Inertia\Inertia;

Route::get('/', fn() => Inertia::render('page'));
Route::get('/settings', fn() => Inertia::render('settings/page'));
Route::get('/upload', fn() => Inertia::render('upload/page'));
