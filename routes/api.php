<?php

use Illuminate\Support\Facades\Route;

use App\Http\Controllers\Api\CustomerController;
use App\Http\Controllers\Api\ServiceController;
use App\Http\Controllers\Api\ProductController;
use App\Http\Controllers\Api\OrderController;
use App\Http\Controllers\Api\AuthController;


Route::apiResource('customers', CustomerController::class);
Route::apiResource('services', ServiceController::class);
Route::apiResource('products', ProductController::class);
Route::apiResource('orders', OrderController::class);
Route::get(
    '/orders/{id}/invoice',
    [OrderController::class, 'invoice']
);

Route::post('/register', [AuthController::class, 'register']);
Route::post('/login', [AuthController::class, 'login']);

Route::middleware('auth:sanctum')->group(function () {
    Route::get('/profile', [AuthController::class, 'profile']);
    Route::post('/profile/avatar', [AuthController::class, 'updateAvatar']);
    Route::post('/logout', [AuthController::class, 'logout']);
});