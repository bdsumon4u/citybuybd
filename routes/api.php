<?php

use App\Http\Controllers\Api\ForwardingController;
use App\Http\Controllers\LandingOrderController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::post('/landing/order', LandingOrderController::class);

Route::post('/forwarding/orders', [ForwardingController::class, 'receiveOrder']);
Route::post('/forwarding/status', [ForwardingController::class, 'receiveStatus']);

Route::middleware('auth:sanctum')->get('/user', fn (Request $request) => $request->user());
