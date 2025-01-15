<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\CartController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\FavoriteController;
use App\Http\Controllers\AddressController;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

Route::get('/categories', [CategoryController::class, 'index']);
Route::get('/categories/{id}', [CategoryController::class, 'show']);
Route::post('/categories', [CategoryController::class, 'store']);
Route::put('/categories/{id}', [CategoryController::class, 'update']);
Route::delete('/categories/{id}', [CategoryController::class, 'destroy']);

// products endpoint
Route::get('/categories/{id}/products', [CategoryController::class, 'getProductsByCategory']);
Route::get('/products', [ProductController::class, 'index']);
Route::get('products/{id}', [ProductController::class, 'show']);

//cart endpoints
Route::middleware('auth:sanctum')->group(function () {
    Route::post('/cart', [CartController::class, 'store']);
    Route::get('/cart', [CartController::class, 'index']);
    Route::post('/favorites', [FavoriteController::class, 'store']);
    Route::get('/favorites', [FavoriteController::class, 'index']);
    Route::post('/addresses', [AddressController::class, 'store']);
    Route::get('/addresses', [AddressController::class, 'index']); 
    Route::delete('/addresses/{id}', [AddressController::class, 'destroy']);
    Route::post('/cart/pay', [CartController::class, 'payforthecart']);
    Route::delete('/cart/{id}', [CartController::class, 'deleteCartItem']);
});

//Auth endpoints
Route::post('/register', [AuthController::class, 'register']);
Route::post('/login', [AuthController::class, 'login']);


