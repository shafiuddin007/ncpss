<?php

use Illuminate\Support\Facades\Route;
use Inertia\Inertia;
use App\Http\Controllers\MemberController;
use App\Http\Controllers\RelationshipController;
use App\Http\Controllers\DivisionController;
use App\Http\Controllers\DistrictController;
use App\Http\Controllers\SellProductController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\ApplicationController;

//Route::middleware('auth',)->group(function () {
Route::group(['middleware' => ['role:admin']], function () {
    Route::get('/members/create', [MemberController::class, 'create'])->name('member.create');
    Route::get('/members', [MemberController::class, 'list'])->name('member.list');
    Route::post('/members', [MemberController::class, 'store'])->name('member.add');
    Route::get('/members/{id}', [MemberController::class, 'show'])->name('members.show');
    Route::get('/members/{id}/edit', [MemberController::class, 'edit'])->name('members.edit');
    Route::put('/members/{id}', [MemberController::class, 'update'])->name('members.update');
    Route::delete('/members/{id}', [MemberController::class, 'destroy'])->name('member.destroy');

    Route::get('/members/{member}/sell-product', [SellProductController::class, 'sellProduct'])->name('members.sell-product');
    Route::post('/members/{member}/sell-product', [SellProductController::class, 'storeSoldProduct'])->name('members.sell-product.store');

    Route::get('/applications', [ApplicationController::class, 'index'])->name('applications.index');
    Route::get('/applications/{application}/show', [ApplicationController::class, 'show'])->name('applications.show');
    Route::get('/applications/{application}/approval-history', [ApplicationController::class, 'approvalHistory'])->name('applications.approval-history');
    Route::post('/applications/{application}/approval-action', [ApplicationController::class, 'approvalAction'])->name('applications.approval-action');

    Route::get('/products', [ProductController::class, 'index'])->name('products.index');
    Route::get('/products/create', [ProductController::class, 'create'])->name('products.create');
    Route::post('/products', [ProductController::class, 'store'])->name('products.store');
    Route::get('/products/{product}/edit', [ProductController::class, 'edit'])->name('products.edit');
    Route::put('/products/{product}', [ProductController::class, 'update'])->name('products.update');
    Route::delete('/products/{product}', [ProductController::class, 'destroy'])->name('products.destroy');

    Route::get('realationships', [RelationshipController::class, 'list'])->name('relationship.list');
});

Route::get('/divisions/{division}/districts', [DivisionController::class, 'getDistricts'])->name('api.divisions.districts');
Route::get('/districts/{district}/thanas', [DistrictController::class, 'getThanas'])->name('api.districts.thanas');
