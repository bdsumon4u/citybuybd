<?php

use App\Http\Controllers\Manager\CityController as ManagerCityController;
use App\Http\Controllers\Manager\CourierController as ManagerCourierController;
use App\Http\Controllers\Manager\OrderController as ManagerOrderController;
use App\Http\Controllers\Manager\PagesController as ManagerPagesController;
use App\Http\Controllers\Manager\ProductController as ManagerProductController;
use App\Http\Controllers\Manager\UserController as ManagerUserController;
use App\Http\Controllers\Manager\ZoneController as ManagerZoneController;
use Illuminate\Support\Facades\Route;

Route::group(['prefix' => 'manager', 'middleware' => ['auth', 'manager']], function (): void {
    // dashboard
    Route::get('/dashboard', [ManagerPagesController::class, 'dashboard'])->name('manager.dashboard');

    Route::get('managerreset', fn () => view('manager.pages.reset'))->name('manager.reset');
    Route::post('r_store', [ManagerPagesController::class, 'r_store'])->name('manager.r_store');

    // courier group
    Route::group(['prefix' => 'courier'], function (): void {
        Route::get('/manage', [ManagerCourierController::class, 'index'])->name('manager.courier.manage');
        Route::get('/create', [ManagerCourierController::class, 'create'])->name('manager.courier.create');
        Route::post('/store', [ManagerCourierController::class, 'store'])->name('manager.courier.store');
        Route::get('/edit/{id}', [ManagerCourierController::class, 'edit'])->name('manager.courier.edit');
        Route::post('/update/{id}', [ManagerCourierController::class, 'update'])->name('manager.courier.update');
        Route::post('/destroy/{id}', [ManagerCourierController::class, 'destroy'])->name('manager.courier.destroy');
    });

    // City group
    Route::group(['prefix' => 'city'], function (): void {
        Route::get('/manage', [ManagerCityController::class, 'index'])->name('manager.city.manage');
        Route::get('/create', [ManagerCityController::class, 'create'])->name('manager.city.create');
        Route::post('/store', [ManagerCityController::class, 'store'])->name('manager.city.store');
        Route::get('/edit/{id}', [ManagerCityController::class, 'edit'])->name('manager.city.edit');
        Route::post('/update/{id}', [ManagerCityController::class, 'update'])->name('manager.city.update');
        Route::post('/destroy/{id}', [ManagerCityController::class, 'destroy'])->name('manager.city.destroy');
    });

    // Zone group
    Route::group(['prefix' => 'zone'], function (): void {
        Route::get('/manage', [ManagerZoneController::class, 'index'])->name('manager.zone.manage');
        Route::get('/create', [ManagerZoneController::class, 'create'])->name('manager.zone.create');
        Route::post('/store', [ManagerZoneController::class, 'store'])->name('manager.zone.store');
        Route::get('/edit/{id}', [ManagerZoneController::class, 'edit'])->name('manager.zone.edit');
        Route::post('/update/{id}', [ManagerZoneController::class, 'update'])->name('manager.zone.update');
        Route::post('/destroy/{id}', [ManagerZoneController::class, 'destroy'])->name('manager.zone.destroy');
    });

    // product group
    Route::group(['prefix' => '/product'], function (): void {
        Route::get('/manage', [ManagerProductController::class, 'index'])->name('manager.product.manage');
        Route::get('/create', [ManagerProductController::class, 'create'])->name('manager.product.create');
        Route::post('/store', [ManagerProductController::class, 'store'])->name('manager.product.store');
        Route::get('/edit/{id}', [ManagerProductController::class, 'edit'])->name('manager.product.edit');
        Route::post('/update/{id}', [ManagerProductController::class, 'update'])->name('manager.product.update');
        Route::get('/destroy/{id}', [ManagerProductController::class, 'destroy'])->name('manager.product.destroy');
        Route::get('/assign_dlt/{id}', [ManagerProductController::class, 'assign_dlt'])->name('manager.assign_dlt');
        Route::get('product-export', [ManagerProductController::class, 'exportIntoExcel'])->name('manager.product.export');
        Route::post('/selected-products', [ManagerProductController::class, 'deleteChecketProducts'])->name('manager.deleteSelected');
        Route::post('/p-selected-status', [ManagerProductController::class, 'p_selected_status'])->name('manager.p_selected_status');
    });

    // user group
    Route::group(['prefix' => '/user'], function (): void {
        Route::get('/manage', [ManagerUserController::class, 'index'])->name('manager.user.manage');
        Route::get('/create', [ManagerUserController::class, 'create'])->name('manager.user.create');
        Route::post('/store', [ManagerUserController::class, 'store'])->name('manager.user.store');
        Route::get('/edit/{id}', [ManagerUserController::class, 'edit'])->name('manager.user.edit');
        Route::post('/update/{id}', [ManagerUserController::class, 'update'])->name('manager.user.update');
        Route::post('/destroy/{id}', [ManagerUserController::class, 'destroy'])->name('manager.user.destroy');
        Route::get('user-export', [ManagerUserController::class, 'exportIntoExcel'])->name('manager.user.export');
        Route::post('/selected-products', [ManagerUserController::class, 'deleteChecketProducts'])->name('manager.deleteSelectedU');
    });

    Route::get('stock', fn () => view('manager.pages.product.stock'))->name('manager.product.stock');

    // Order Management Route
    Route::group(['prefix' => '/order-management'], function (): void {
        Route::get('/manage', [ManagerOrderController::class, 'index'])->name('manager.order.manage');
        Route::get('/manage/{status}', [ManagerOrderController::class, 'management'])->name('manager.order.management');
        Route::get('order-details/{id}', [ManagerOrderController::class, 'show'])->name('manager.order.details');
        Route::get('create', [ManagerOrderController::class, 'create'])->name('manager.order.create');
        Route::post('store', [ManagerOrderController::class, 'store'])->name('manager.order.store');
        Route::get('/edit/{id}', [ManagerOrderController::class, 'edit'])->name('manager.order.edit');

        // new update
        Route::get('/new-manage', [ManagerOrderController::class, 'newIndex'])->name('manager.order.newmanage');
        Route::get('/filter-data', [ManagerOrderController::class, 'FilterData'])->name('manager.filter-data');
        Route::get('/new-manage-action', [ManagerOrderController::class, 'newIndexAction'])->name('manager.new-manage-action');
        Route::get('manager-total-order-list', [ManagerOrderController::class, 'total_order_list'])->name('manager_total_order_list');
        Route::get('/parcel-handover', [ManagerOrderController::class, 'parcelHandover'])->name('manager.order.parcelHandover');
        Route::post('/scan-parcel-handover', [ManagerOrderController::class, 'scanParcelHandover'])->name('manager.order.scanParcelHandover');
        Route::get('/return-received', [ManagerOrderController::class, 'returnReceived'])->name('manager.order.returnReceived');
        Route::post('/scan-return-received', [ManagerOrderController::class, 'scanReturnReceived'])->name('manager.order.scanReturnReceived');
        Route::get('/get-scanned-orders', [ManagerOrderController::class, 'getScannedOrders'])->name('manager.order.getScannedOrders');
        Route::get('/print-scanned-orders', [ManagerOrderController::class, 'printScannedOrders'])->name('manager.order.printScannedOrders');
        Route::post('/delete-scanned-order', [ManagerOrderController::class, 'deleteScannedOrder'])->name('manager.order.deleteScannedOrder');
        // order status change
        Route::get('/order/{status}/{id}', [ManagerOrderController::class, 'statusChange'])->middleware('auth')->name('manager.order.statusChange');

        Route::post('/update/{id}', [ManagerOrderController::class, 'update'])->name('manager.order.update');
        Route::post('/update_s/{id}', [ManagerOrderController::class, 'update_s'])->name('manager.order.update_s');
        Route::post('update_auto', [ManagerOrderController::class, 'update_auto']);

        Route::post('/destroy/{id}', [ManagerOrderController::class, 'destroy'])->name('manager.order.destroy');
        Route::get('order-export', [ManagerOrderController::class, 'orderexport'])->name('manager.order.export');
        Route::post('/selected-orders', [ManagerOrderController::class, 'deleteChecketorders'])->middleware('auth', 'manager')->name('manager.deleteChecketorders');

        Route::post('/selected-status', [ManagerOrderController::class, 'selected_status'])->name('manager.selected_status');

        Route::get('/searchInput', [ManagerOrderController::class, 'search_order_input'])->middleware('auth', 'manager')->name('order.search.input.manager');
        Route::get('/paginate/{count}/{status}', [ManagerOrderController::class, 'paginate'])->middleware('auth', 'admin')->name('order.paginate.manager');
        Route::get('/search-Date/{count}', [ManagerOrderController::class, 'searchByPastDate'])->middleware('auth', 'admin')->name('order.searchByPastDate.manager');
        Route::get('/search-Date/{count}/{status}', [ManagerOrderController::class, 'searchByPastDateStatus'])->middleware('auth', 'admin')->name('order.searchByPastDateStatus.manager');
        Route::get('/order/searchInput', [ManagerOrderController::class, 'search_order_input'])->middleware('auth')->name('order.search.input.manager');
        Route::get('/order/search', [ManagerOrderController::class, 'search_order'])->middleware('auth')->name('order.search.manager');
    });
});
