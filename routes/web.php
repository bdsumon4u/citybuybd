<?php

use App\Http\Controllers\Backend\OrderController;
use App\Http\Controllers\Backend\PathaoController;
use App\Http\Controllers\Backend\RedXController;
use App\Http\Controllers\Backend\ReportController;
use App\Http\Controllers\Frontend\CartController;
use App\Http\Controllers\Frontend\PagesController;
use App\Http\Controllers\PushSubscriptionController;
use App\Models\Childcategory;
use App\Models\City;
use App\Models\Order;
use App\Models\Product;
use App\Models\Settings;
use App\Models\Subcategory;
use App\Models\Zone;
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

// Report routes
Route::get('total-order-list', [OrderController::class, 'total_order_list'])->name('total_order_list');
Route::get('qc_report/{number}', [OrderController::class, 'qc_report'])->name('qc_report');
Route::get('total-order-custom-date/{date_from}/{date_to}', [OrderController::class, 'total_order_custom_date'])->name('total_order_custom_date');
Route::get('total-order-fixed-date/{count}', [OrderController::class, 'total_order_fixed_date'])->name('total_order_fixed_date');
Route::get('total-order-product/{date_from}/{date_to}/{prd}', [ReportController::class, 'total_order_product'])->name('total_order_product');
Route::get('total-order-employee', [ReportController::class, 'total_order_employee'])->name('total_order_employee');

// Hot deals and products
Route::get('hot_deals', function () {
    $products = Product::whereNotNull('offer_price')->where('status', 1)->orderBy('id', 'desc')->paginate(18);
    $settings = Settings::first();

    return view('frontend.pages.hot_deal', compact('products', 'settings'));
});

Route::get('all-Products', function () {
    $products = Product::where('status', 1)->orderBy('id', 'desc')->get();
    $settings = Settings::first();

    return view('frontend.pages.allProducts', compact('products', 'settings'));
});

Route::get('confirm-order', fn () => view('frontend.pages.c_order'))->name('c_order');

// Category/Subcategory AJAX
Route::get('/get-subcategory/{id}', fn ($id) => json_encode(Subcategory::where('category_id', $id)->get()));
Route::get('/get-childcategory/{id}', fn ($id) => json_encode(Childcategory::where('subcategory_id', $id)->get()));

// Cart routes
Route::post('/o_store', [CartController::class, 'o_store'])->name('o_cart.store');
Route::get('/cart_plus', [CartController::class, 'cart_plus']);
Route::post('update/{id}', [CartController::class, 'update'])->name('cart.update');
Route::get('destroy/{id}', [CartController::class, 'destroy'])->name('cart.destroy');
Route::get('admin_cart_dlt/{id}/{order}', [CartController::class, 'admin_cart_dlt'])->name('admin_cart_dlt');

// Pathao webhook
Route::post('pathao-status-update', [PathaoController::class, 'pathaoStatusUpdate'])->name('pathao.status.update');

// RedX webhook
Route::get('redx/areas', [RedXController::class, 'getAreas'])->name('redx.areas');
Route::post('redx-status-update', [RedXController::class, 'redxStatusUpdate'])->name('redx.status.update');

// Pathao API routes
Route::prefix('pathao')->name('pathao.')->group(function (): void {
    Route::get('get-stores', [PathaoController::class, 'GetStores'])->name('get.stores');
    Route::get('get-cities', [PathaoController::class, 'GetCities'])->name('get.cities');
    Route::get('get-zones', [PathaoController::class, 'GetZones'])->name('get.zones');
    Route::get('get-areas', [PathaoController::class, 'GetAreas'])->name('get.areas');
});

// City/Zone AJAX
Route::post('/get_city', [OrderController::class, 'get_city']);
Route::post('/get_zone', [OrderController::class, 'get_zone']);
Route::get('/get-city/{id}', fn ($id) => json_encode(City::where('courier_id', $id)->get()));
Route::get('/get-zone/{id}', fn ($id) => json_encode(Zone::where('city', $id)->get()));

// Admin order management AJAX
Route::get('/images', fn () => view('backend.pages.image.manage'))->middleware('auth', 'admin');
Route::get('/ajax_find_product/{id}', [OrderController::class, 'ajax_find_product']);
Route::get('/ajax_find_courier/{id}', [OrderController::class, 'ajax_find_courier']);
Route::get('/admin_cart/{id}', [OrderController::class, 'admin_cart']);
Route::get('/admin_cart_update/{id}/{order}', [OrderController::class, 'admin_cart_update']);

require __DIR__.'/auth.php';
