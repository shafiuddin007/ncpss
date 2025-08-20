<?php

use Illuminate\Support\Facades\Route;
use Inertia\Inertia;
use App\Http\Controllers\DivisionController;
use App\Http\Controllers\ShareAccountController;
use App\Http\Controllers\SavingsAccountController;

Route::get('/', function () {
    return Inertia::render('auth/Login');
})->name('home');

Route::get('dashboard', function () {
    return Inertia::render('Dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::get('/divisions/{division}/districts', [DivisionController::class, 'getDistricts']);
Route::get('/share-accounts', [ShareAccountController::class, 'index'])->middleware(['auth', 'verified'])->name('share-accounts.index');
Route::get('/share-accounts/create', [ShareAccountController::class, 'create'])->middleware(['auth', 'verified'])->name('share-accounts.create');
Route::post('/share-accounts', [ShareAccountController::class, 'store'])->middleware(['auth', 'verified'])->name('share-accounts.store');
Route::get('/share-accounts/{member}/create', [ShareAccountController::class, 'share_application'])
    ->middleware(['auth', 'verified'])
    ->name('share-accounts.create');

    
Route::get('/savings-accounts', [SavingsAccountController::class, 'index'])->middleware(['auth', 'verified'])->name('savings-accounts.index');
Route::get('/savings-accounts/create', [SavingsAccountController::class, 'create'])->middleware(['auth', 'verified'])->name('savings-accounts.create');
Route::post('/savings-accounts', [SavingsAccountController::class, 'store'])->middleware(['auth', 'verified'])->name('savings-accounts.store');
Route::get('/savings-accounts/{member}/create', [SavingsAccountController::class, 'savings_application'])
    ->middleware(['auth', 'verified'])
    ->name('savings-accounts.create');

require __DIR__.'/settings.php';
require __DIR__.'/auth.php';
require __DIR__.'/somiteeadm.php';
