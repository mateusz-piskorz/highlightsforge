<?php

use Illuminate\Support\Facades\Route;
use Inertia\Inertia;

Route::get('/', fn() => Inertia::render('page'));
Route::get('/settings', fn() => Inertia::render('settings/page'));
Route::get('/profile', fn() => Inertia::render('profile/page'));
Route::get('/reports', fn() => Inertia::render('reports/page'));
