<?php

use App\Http\Controllers\Backend\IncompleteOrderController;
use App\Http\Controllers\Frontend\IncompleteOrder\IncompleteOrderController as IncompleteOrderFrontendController;
use Illuminate\Support\Facades\Route;

// Incomplete Order Routes
Route::get('/admin/incomplete', [IncompleteOrderController::class, 'index'])
    ->middleware('auth')->name('order.incomplete.admin');

Route::get('/incomplete', [IncompleteOrderController::class, 'index'])
    ->middleware('auth')->name('order.incomplete');

Route::get('/incomplete/{id}', [IncompleteOrderController::class, 'show'])
    ->middleware('auth')->name('order.incomplete.show');

Route::get('/incomplete/{id}/edit', [IncompleteOrderController::class, 'edit'])
    ->middleware('auth')->name('order.incomplete.edit');

Route::put('/incomplete/{id}', [IncompleteOrderController::class, 'update'])
    ->middleware('auth')->name('order.incomplete.update');

Route::delete('/incomplete/{id}', [IncompleteOrderController::class, 'destroy'])
    ->middleware('auth')->name('order.incomplete.destroy');

// Delete incomplete bulk select
Route::delete('/incomplete-orders/bulk-delete', [IncompleteOrderController::class, 'bulkDelete'])
    ->middleware('auth')
    ->name('order.incomplete.bulk-delete');

// Convert incomplete order to completed order
Route::post('/incomplete-orders/{id}/convert', [IncompleteOrderFrontendController::class, 'convertToOrder'])
    ->middleware('auth')
    ->name('order.incomplete.convert');

Route::post('/incomplete-order/auto-save', [IncompleteOrderFrontendController::class, 'autoSave'])
    ->name('incomplete-order.auto-save');

// Incomplete order auto-save
Route::prefix('incomplete-order')->group(function (): void {
    Route::post('/auto-save', [IncompleteOrderFrontendController::class, 'autoSave'])
        ->name('incomplete-order.auto-save');
});
