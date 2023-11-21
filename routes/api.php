<?php

use App\Http\Controllers\DeliveryController;
use App\Http\Controllers\OrderController;
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

Route::post("/orders", [OrderController::class, "createOrder"]);

Route::get("/orders", [OrderController::class, "getAllOrders"]);

Route::post("/orders/confirm/{id}", [OrderController::class, "confirmOrder"]);

Route::get("/orders/findDriver", [DeliveryController::class, "findDriver"]);

Route::get("/orders/getDeliveries", [DeliveryController::class, "getDeliveries"]);
