<?php

use Illuminate\Support\Facades\Route;
use Inertia\Inertia;
use App\Http\Controllers\MemberController;
use App\Http\Controllers\RelationshipController;
use App\Http\Controllers\DivisionController;
use App\Http\Controllers\DistrictController;

Route::middleware('auth')->group(function () {
    Route::get('members', [MemberController::class, 'list'])->name('member.list');
    Route::post('/members', [MemberController::class, 'store'])->name('member.add');
    
    Route::get('members/create', [MemberController::class, 'create'])->name('member.create');

    Route::get('realationships', [RelationshipController::class, 'list'])->name('relationship.list');

});

Route::get('/divisions/{division}/districts', [DivisionController::class, 'getDistricts'])->name('api.divisions.districts');
Route::get('/districts/{district}/thanas', [DistrictController::class, 'getThanas'])->name('api.districts.thanas');