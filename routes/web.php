<?php

use App\Http\Controllers\BookingController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\RestaurantSettingsController;
use App\Http\Controllers\MenuCategoryController;
use App\Http\Controllers\MenuItemController;
use App\Http\Controllers\PublicMenuController;
use App\Http\Middleware\EnsureRestaurantContext;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/m/{publicSlug}', [PublicMenuController::class, 'show'])->name('public.menu.show');

Route::middleware(['auth', EnsureRestaurantContext::class])->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    Route::resource('bookings', BookingController::class);
    Route::resource('menu-categories', MenuCategoryController::class)->except(['show']);
    Route::resource('menu-items', MenuItemController::class)->except(['show']);
    Route::patch('/bookings/{booking}/quick-status', [BookingController::class, 'quickStatus'])->name('bookings.quick-status');

    Route::get('/settings/restaurant', [RestaurantSettingsController::class, 'edit'])
        ->name('settings.restaurant.edit');
    Route::put('/settings/restaurant', [RestaurantSettingsController::class, 'update'])
        ->name('settings.restaurant.update');
});

require __DIR__.'/auth.php';
