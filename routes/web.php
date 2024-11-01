<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\CustomerController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\NotificationController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\OrderController;

Route::get('/login', [AuthController::class, 'showLogin'])->name('login');

Route::get('/register', [AuthController::class, 'showRegister']);

Route::post('/login', [AuthController::class, 'login']);

Route::post('/register', [AuthController::class, 'register']);

Route::middleware(['auth'])->group(function () {
    Route::get('/home', [HomeController::class, 'index']);
    Route::get('/statistify', [HomeController::class, 'statistify']);
    Route::post('/logout', [AuthController::class, 'logout']);
    Route::get('/notifications', [NotificationController::class, 'index']);
    Route::delete('/notifications/{id}', [NotificationController::class, 'destroy']);



    Route::middleware('permission:customers')->group(function () {
        Route::get('/customers', [CustomerController::class, 'index']);
        Route::post('/customers', [CustomerController::class, 'store']);
        Route::put('/customers/{id}', [CustomerController::class, 'update']);
        Route::delete('/customers/{id}', [CustomerController::class, 'destroy']);
    });

    Route::middleware('permission:products')->group(function () {
        Route::get('/products', [ProductController::class, 'index']);
        Route::post('/products', [ProductController::class, 'store']);
        Route::put('/products/{id}', [ProductController::class, 'update']);
        Route::delete('/products/{id}', [ProductController::class, 'destroy']);
    });

    Route::middleware('permission:orders')->group(function () {
        Route::get('/orders', [OrderController::class, 'index']);
        Route::delete('/orders/{id}', [OrderController::class, 'destroy']);
        Route::get('/orders/newOrder', [OrderController::class, 'showCreateNewOrder']);
        Route::get('/orders/newOrder/search_customer_by_phone', [OrderController::class, 'searchCustomerByPhone']);
        Route::get('/orders/newOrder/search_product_by_name', [OrderController::class, 'searchProductByName']);
        Route::post('/orders/newOrder', [OrderController::class, 'createNewOrder']);
        Route::get('/orders/detail', [OrderController::class, 'getOrderDetail']);
        Route::get('/orders/pdf', [OrderController::class, 'generateInvoice']);
    });
});
