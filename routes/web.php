<?php

use App\Http\Middleware\EnsureRestaurantContext;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Route::middleware(['auth', EnsureRestaurantContext::class])->group(function () {
    Route::get('/dashboard', function () {
        return view('dashboard.index');
    })->name('dashboard');
});

require __DIR__.'/auth.php';
