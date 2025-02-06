<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

Route::get('/users/authenticated', [\App\Http\Controllers\UserController::class, 'showAuth'])->name('users.authenticated');
Route::apiResource('users', App\Http\Controllers\UserController::class);
