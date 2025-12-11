<?php

use Illuminate\Support\Facades\Route;
use Inertia\Inertia;
use Laravel\Fortify\Features;

Route::get('/settings', fn () => Inertia::render('settings/page'));

Route::get('/', function () {
    return Inertia::render('Welcome', [
        'canRegister' => Features::enabled(Features::registration()),
    ]);
})->middleware(['api'])->name('home');

Route::get('/upload', function () {
    return Inertia::render('upload-page');
})->name('upload');

Route::get('dashboard', function () {
    return Inertia::render('Dashboard');
})->name('dashboard');
// ->middleware([])
// auth', 'verified
require __DIR__.'/settings.php';
