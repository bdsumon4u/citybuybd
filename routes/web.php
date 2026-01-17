<?php

use App\Http\Controllers\Frontend\PagesController;
use App\Http\Controllers\PushSubscriptionController;
use App\Models\Order;
use App\Notifications\OrderNotification;
use Illuminate\Support\Facades\Route;

// Include modular route files
require __DIR__.'/admin.php';
require __DIR__.'/manager.php';
require __DIR__.'/employee.php';
require __DIR__.'/incomplete.php';

// Authenticated routes
Route::middleware('auth')->group(function (): void {
    Route::post('/push/subscribe', [PushSubscriptionController::class, 'store'])->name('push.subscribe');
    Route::post('/push/unsubscribe', [PushSubscriptionController::class, 'destroy'])->name('push.unsubscribe');
});

Route::get('/', [PagesController::class, 'index'])->name('homepage')->middleware('cache.response');
Route::get('/details/{id}', [PagesController::class, 'details'])->name('details')->middleware('cache.response');
Route::get('/checkout', [PagesController::class, 'checkout'])->name('checkout');

Route::post('/ajax_get_shipp_meth', [PagesController::class, 'ajax_get_shipp_meth'])->name('ajax.get.shipp.meth');

Route::post('/order', [PagesController::class, 'order'])->name('order');
Route::post('/landing-order', [PagesController::class, 'landingorder'])->name('landing.order');
Route::get('/search', [PagesController::class, 'search'])->name('search');
Route::get('/ajax_find_shipping/{id}', [PagesController::class, 'ajax_find_shipping']);

Route::get('category/{id}', [PagesController::class, 'category'])->name('category')->middleware('cache.response');
Route::get('subcategory/{id}', [PagesController::class, 'subcategory'])->name('subcategory')->middleware('cache.response');
Route::get('childcategory/{id}', [PagesController::class, 'childcategory'])->name('childcategory')->middleware('cache.response');

Route::get('contact', [PagesController::class, 'contact'])->name('front.contact')->middleware('cache.response');
Route::get('about', [PagesController::class, 'about'])->name('front.about')->middleware('cache.response');
Route::get('term-condition', [PagesController::class, 'termCondition'])->name('front.termCondition')->middleware('cache.response');

Route::get('landing/{id}', [PagesController::class, 'landing'])->name('front.landing')->middleware('cache.response');

Route::get('/notify', function () {
    Order::latest()->first()->notify(new OrderNotification('hello_world'));

    return 'Notification sent';
});

// Cache clear route
Route::get('/cache/clear', [App\Http\Controllers\CacheController::class, 'clear'])
    ->name('cache.clear')
    ->middleware('auth');

require __DIR__.'/auth.php';
