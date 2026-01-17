<?php

use App\Http\Controllers\CacheController;
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

Route::get('/', 'App\Http\Controllers\Frontend\PagesController@index')->name('homepage')->middleware('cache.response');
Route::get('/details/{id}', 'App\Http\Controllers\Frontend\PagesController@details')->name('details')->middleware('cache.response');
Route::get('/checkout', 'App\Http\Controllers\Frontend\PagesController@checkout')->name('checkout');

Route::post('/ajax_get_shipp_meth', 'App\Http\Controllers\Frontend\PagesController@ajax_get_shipp_meth')->name('ajax.get.shipp.meth');

Route::post('/order', 'App\Http\Controllers\Frontend\PagesController@order')->name('order');
Route::post('/landing-order', 'App\Http\Controllers\Frontend\PagesController@landingorder')->name('landing.order');
Route::get('/search', 'App\Http\Controllers\Frontend\PagesController@search')->name('search');
Route::get('/ajax_find_shipping/{id}', 'App\Http\Controllers\Frontend\PagesController@ajax_find_shipping');

Route::get('category/{id}', 'App\Http\Controllers\Frontend\PagesController@category')->name('category')->middleware('cache.response');
Route::get('subcategory/{id}', 'App\Http\Controllers\Frontend\PagesController@subcategory')->name('subcategory')->middleware('cache.response');
Route::get('childcategory/{id}', 'App\Http\Controllers\Frontend\PagesController@childcategory')->name('childcategory')->middleware('cache.response');

Route::get('contact', 'App\Http\Controllers\Frontend\PagesController@contact')->name('front.contact')->middleware('cache.response');
Route::get('about', 'App\Http\Controllers\Frontend\PagesController@about')->name('front.about')->middleware('cache.response');
Route::get('term-condition', 'App\Http\Controllers\Frontend\PagesController@termCondition')->name('front.termCondition')->middleware('cache.response');

Route::get('landing/{id}', 'App\Http\Controllers\Frontend\PagesController@landing')->name('front.landing')->middleware('cache.response');

Route::get('/notify', function () {
    Order::latest()->first()->notify(new OrderNotification('hello_world'));

    return 'Notification sent';
});

// Cache clear route
Route::get('/cache/clear', [App\Http\Controllers\CacheController::class, 'clear'])
    ->name('cache.clear')
    ->middleware('auth');

require __DIR__.'/auth.php';
