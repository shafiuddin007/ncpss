<?php

use Illuminate\Support\Facades\Route;
use Inertia\Inertia;
use App\Http\Controllers\MemberController;
use App\Http\Controllers\RelationshipController;
use App\Http\Controllers\DivisionController;
use App\Http\Controllers\DistrictController;
use App\Http\Controllers\ProductController;

Route::middleware('auth',)->group(function () {
    Route::get('/members/create', [MemberController::class, 'create'])->name('member.create');
    Route::get('/members', [MemberController::class, 'list'])->name('member.list');
    Route::post('/members', [MemberController::class, 'store'])->name('member.add');
    Route::get('/members/{id}', [MemberController::class, 'show'])->name('members.show');
    Route::get('/members/{id}/edit', [MemberController::class, 'edit'])->name('members.edit');
    Route::put('/members/{id}', [MemberController::class, 'update'])->name('members.update');
    Route::delete('/members/{id}', [MemberController::class, 'destroy'])->name('member.destroy');


    Route::get('/products', [ProductController::class, 'index'])->name('products.index');
    Route::get('realationships', [RelationshipController::class, 'list'])->name('relationship.list');
});

Route::get('/divisions/{division}/districts', [DivisionController::class, 'getDistricts'])->name('api.divisions.districts');
Route::get('/districts/{district}/thanas', [DistrictController::class, 'getThanas'])->name('api.districts.thanas');
