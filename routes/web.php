<?php

use App\Http\Controllers\RestaurantSettingsController;
use App\Http\Middleware\EnsureRestaurantContext;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Route::middleware(['auth', EnsureRestaurantContext::class])->group(function () {
    Route::get('/dashboard', function () {
        return view('dashboard.index');
    })->name('dashboard');

    Route::get('/settings/restaurant', [RestaurantSettingsController::class, 'edit'])
        ->name('settings.restaurant.edit');
    Route::put('/settings/restaurant', [RestaurantSettingsController::class, 'update'])
        ->name('settings.restaurant.update');
});

require __DIR__.'/auth.php';
