<?php

use App\Http\Controllers\brandController;
use App\Http\Controllers\categoryController;
use App\Http\Controllers\paymentController;
use App\Http\Controllers\productController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

// Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
//     return $request->user();
// });

Route::apiResource('brands' , brandController::class);
Route::apiResource('categories' , categoryController::class);
Route::apiResource('products' , productController::class);

Route::get('categories/parent/{category}', [categoryController::class ,'parent']);
Route::get('categories/children/{category}', [categoryController::class ,'children']);

Route::post('payment/send', [paymentController::class ,'send']);
Route::post('payment/verify', [paymentController::class ,'verify']);
