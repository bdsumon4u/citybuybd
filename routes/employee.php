<?php

use App\Http\Controllers\Employee\OrderController as EmployeeOrderController;
use App\Http\Controllers\Employee\PagesController as EmployeePagesController;
use Illuminate\Support\Facades\Route;

Route::group(['prefix' => 'employee'], function (): void {
    // dashboard
    Route::get('/dashboard', [EmployeePagesController::class, 'dashboard'])->name('employee.dashboard')->middleware('auth', 'employee');

    Route::get('reset', fn () => view('employee.pages.reset'))->name('employee.reset')->middleware('auth', 'employee');
    Route::post('r_store', [EmployeePagesController::class, 'r_store'])->name('employee.r_store')->middleware('auth', 'employee');

    // Order Management Route
    Route::group(['prefix' => '/order-management'], function (): void {
        Route::get('/manage-old', [EmployeeOrderController::class, 'index'])->name('employee.order.manage')->middleware('auth', 'employee');
        Route::get('/manage/{status}', [EmployeeOrderController::class, 'management'])->name('employee.order.management')->middleware('auth', 'employee');

        // new update
        Route::get('/manage', [EmployeeOrderController::class, 'newIndex'])->name('employee.order.newmanage')->middleware('auth', 'employee');
        Route::get('/filter-data', [EmployeeOrderController::class, 'FilterData'])->name('employee.filter-data')->middleware('auth', 'employee');
        Route::get('/new-manage-action', [EmployeeOrderController::class, 'newIndexAction'])->name('employee.new-manage-action')->middleware('auth', 'employee');
        Route::get('emp-total-order-list', [EmployeeOrderController::class, 'total_order_list'])->name('emp_total_order_list')->middleware('auth', 'employee');
        Route::get('/parcel-handover', [EmployeeOrderController::class, 'parcelHandover'])->name('employee.order.parcelHandover')->middleware('auth');
        Route::post('/scan-parcel-handover', [EmployeeOrderController::class, 'scanParcelHandover'])->name('employee.order.scanParcelHandover')->middleware('auth');
        Route::get('/return-received', [EmployeeOrderController::class, 'returnReceived'])->name('employee.order.returnReceived')->middleware('auth');
        Route::post('/scan-return-received', [EmployeeOrderController::class, 'scanReturnReceived'])->name('employee.order.scanReturnReceived')->middleware('auth');
        Route::get('/get-scanned-orders', [EmployeeOrderController::class, 'getScannedOrders'])->name('employee.order.getScannedOrders')->middleware('auth');
        Route::get('/print-scanned-orders', [EmployeeOrderController::class, 'printScannedOrders'])->name('employee.order.printScannedOrders')->middleware('auth');
        Route::post('/delete-scanned-order', [EmployeeOrderController::class, 'deleteScannedOrder'])->name('employee.order.deleteScannedOrder')->middleware('auth');
        Route::get('order-details/{id}', [EmployeeOrderController::class, 'show'])->name('employee.order.details')->middleware('auth', 'employee');
        Route::get('create', [EmployeeOrderController::class, 'create'])->name('employee.order.create')->middleware('auth', 'employee');
        Route::post('store', [EmployeeOrderController::class, 'store'])->name('employee.order.store')->middleware('auth', 'employee');
        Route::get('/edit/{id}', [EmployeeOrderController::class, 'edit'])->name('employee.order.edit')->middleware('auth', 'employee');
        Route::post('/update/{id}', [EmployeeOrderController::class, 'update'])->name('employee.order.update')->middleware('auth', 'employee');

        // order status change
        Route::get('/order/{status}/{id}', [EmployeeOrderController::class, 'statusChange'])->name('employee.order.statusChange')->middleware('auth', 'employee');

        // order export & optional
        Route::post('/update_s/{id}', [EmployeeOrderController::class, 'update_s'])->name('employee.order.update_s')->middleware('auth', 'employee');
        Route::post('update_auto', [EmployeeOrderController::class, 'update_auto'])->middleware('auth', 'employee');
        Route::get('order-export', [EmployeeOrderController::class, 'orderexport'])->name('employee.order.export')->middleware('auth', 'employee');
        Route::get('/searchInput', [EmployeeOrderController::class, 'search_order_input'])->name('order.search.input.employee')->middleware('auth', 'employee');

        Route::post('noted_edit/{id}', [EmployeeOrderController::class, 'noted_edit'])->name('employee.order.noted_edit')->middleware('auth');
    });
});
