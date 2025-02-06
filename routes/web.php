<?php

use App\Http\Controllers\ProfileController;
use App\Http\Middleware\DevelopmentModeOnly;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

/**
 * Show phpinfo() and xdebug_info() in local environment
 */
Route::middleware(DevelopmentModeOnly::class)->group(function () {
    Route::get('/phpinfo', fn () => phpinfo());
    Route::get('/xdebug', fn () => xdebug_info());
});
