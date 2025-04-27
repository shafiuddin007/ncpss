<?php

use Illuminate\Support\Facades\Route;
use Inertia\Inertia;
use App\Http\Controllers\DivisionController;

Route::get('/', function () {
    return Inertia::render('Welcome');
})->name('home');

Route::get('dashboard', function () {
    return Inertia::render('Dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::get('/divisions/{division}/districts', [DivisionController::class, 'getDistricts']);
require __DIR__.'/settings.php';
require __DIR__.'/auth.php';
require __DIR__.'/somiteeadm.php';
