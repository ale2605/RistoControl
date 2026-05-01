<?php

use App\Http\Controllers\BookingController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\RestaurantSettingsController;
use App\Http\Controllers\MenuCategoryController;
use App\Http\Controllers\MenuItemController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\PublicMenuController;
use App\Http\Controllers\DiningAreaController;
use App\Http\Controllers\KitchenMonitorController;
use App\Http\Controllers\TableController;
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
    Route::resource('dining-areas', DiningAreaController::class)->except(['show']);
    Route::resource('tables', TableController::class)->except(['show']);
    Route::resource('orders', OrderController::class)->only(['index','create','store','show']);
    Route::patch('/orders/{order}/send', [OrderController::class, 'send'])->name('orders.send');
    Route::patch('/orders/{order}/items/{item}/status', [OrderController::class, 'updateItemStatus'])->name('orders.items.status');
    Route::patch('/tables/{table}/quick-status', [TableController::class, 'quickStatus'])->name('tables.quick-status');
    Route::get('/room/dashboard', [TableController::class, 'roomDashboard'])->name('room.dashboard');
    Route::patch('/bookings/{booking}/quick-status', [BookingController::class, 'quickStatus'])->name('bookings.quick-status');
    Route::get('/kitchen-monitor', [KitchenMonitorController::class, 'index'])->name('kitchen-monitor.index');
    Route::patch('/kitchen-monitor/items/{item}/transition', [KitchenMonitorController::class, 'transition'])->name('kitchen-monitor.items.transition');

    Route::get('/settings/restaurant', [RestaurantSettingsController::class, 'edit'])
        ->name('settings.restaurant.edit');
    Route::put('/settings/restaurant', [RestaurantSettingsController::class, 'update'])
        ->name('settings.restaurant.update');
});

require __DIR__.'/auth.php';
