<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ProductsController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\DiscountController;

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

//Route::middleware('auth:api')->get('/user', function (Request $request) {
//    return $request->user();
//});

// Products
Route::get('/products', [ProductsController::class, 'index']);
Route::get('/products/{id}', [ProductsController::class, 'show']);

// Order
Route::get('/orders', [OrderController::class, 'index']);
Route::post('/orders', [OrderController::class, 'add']);
Route::get('/orders/{id}', [OrderController::class, 'show']);
Route::delete('/orders/{id}', [OrderController::class, 'delete']);


Route::post('/calculate-discounts', [DiscountController::class, 'calculate']);



