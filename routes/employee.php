<?php

use App\Http\Controllers\Employee\OrderController as EmployeeOrderController;
use App\Http\Controllers\Employee\PagesController as EmployeePagesController;
use Illuminate\Support\Facades\Route;

Route::group(['prefix' => 'employee', 'middleware' => ['auth', 'employee']], function (): void {
    // dashboard
    Route::get('/dashboard', [EmployeePagesController::class, 'dashboard'])->name('employee.dashboard');

    Route::get('reset', fn () => view('employee.pages.reset'))->name('employee.reset');
    Route::post('r_store', [EmployeePagesController::class, 'r_store'])->name('employee.r_store');

    // Order Management Route
    Route::group(['prefix' => '/order-management'], function (): void {
        Route::get('/manage-old', [EmployeeOrderController::class, 'index'])->name('employee.order.manage');
        Route::get('/manage/{status}', [EmployeeOrderController::class, 'management'])->name('employee.order.management');

        // new update
        Route::get('/manage', [EmployeeOrderController::class, 'newIndex'])->name('employee.order.newmanage');
        Route::get('/filter-data', [EmployeeOrderController::class, 'FilterData'])->name('employee.filter-data');
        Route::get('/new-manage-action', [EmployeeOrderController::class, 'newIndexAction'])->name('employee.new-manage-action');
        Route::get('emp-total-order-list', [EmployeeOrderController::class, 'total_order_list'])->name('emp_total_order_list');
        Route::get('/parcel-handover', [EmployeeOrderController::class, 'parcelHandover'])->name('employee.order.parcelHandover');
        Route::post('/scan-parcel-handover', [EmployeeOrderController::class, 'scanParcelHandover'])->name('employee.order.scanParcelHandover');
        Route::get('/return-received', [EmployeeOrderController::class, 'returnReceived'])->name('employee.order.returnReceived');
        Route::post('/scan-return-received', [EmployeeOrderController::class, 'scanReturnReceived'])->name('employee.order.scanReturnReceived');
        Route::get('/get-scanned-orders', [EmployeeOrderController::class, 'getScannedOrders'])->name('employee.order.getScannedOrders');
        Route::get('/print-scanned-orders', [EmployeeOrderController::class, 'printScannedOrders'])->name('employee.order.printScannedOrders');
        Route::post('/delete-scanned-order', [EmployeeOrderController::class, 'deleteScannedOrder'])->name('employee.order.deleteScannedOrder');
        Route::get('order-details/{id}', [EmployeeOrderController::class, 'show'])->name('employee.order.details');
        Route::get('create', [EmployeeOrderController::class, 'create'])->name('employee.order.create');
        Route::post('store', [EmployeeOrderController::class, 'store'])->name('employee.order.store');
        Route::get('/edit/{id}', [EmployeeOrderController::class, 'edit'])->name('employee.order.edit');
        Route::post('/update/{id}', [EmployeeOrderController::class, 'update'])->name('employee.order.update');

        // order status change
        Route::get('/order/{status}/{id}', [EmployeeOrderController::class, 'statusChange'])->name('employee.order.statusChange');

        // order export & optional
        Route::post('/update_s/{id}', [EmployeeOrderController::class, 'update_s'])->name('employee.order.update_s');
        Route::post('update_auto', [EmployeeOrderController::class, 'update_auto']);
        Route::get('order-export', [EmployeeOrderController::class, 'orderexport'])->name('employee.order.export');
        Route::get('/searchInput', [EmployeeOrderController::class, 'search_order_input'])->name('order.search.input.employee');

        Route::post('noted_edit/{id}', [EmployeeOrderController::class, 'noted_edit'])->name('employee.order.noted_edit');
    });
});
