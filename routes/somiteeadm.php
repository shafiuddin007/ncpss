<?php

use Illuminate\Support\Facades\Route;
use Inertia\Inertia;
use App\Http\Controllers\MemberController;
use App\Http\Controllers\RelationshipController;

Route::middleware('auth')->group(function () {
    Route::get('members', [MemberController::class, 'list'])->name('member.list');
    Route::get('members/create', [MemberController::class, 'create'])->name('member.create');

    Route::get('realationships', [RelationshipController::class, 'list'])->name('relationship.list');

});
