<?php

use App\Http\Controllers\Cart\CartController;
use App\Http\Controllers\Orders\OrderController;
use Illuminate\Support\Facades\Route;

use App\Http\Controllers\Session\{SessionController, ForgotPasswordController, ResetPasswordController};

Route::middleware(['guest:api'])->group(function () {
    Route::post('/login', [SessionController::class, 'login']);
    Route::post('/register', [SessionController::class, 'register']);
    Route::post('/forgot', [ForgotPasswordController::class, 'forgot']);
    Route::get('/reset/{token}/{email}', [ResetPasswordController::class, 'formReset'])->name('password.reset');
    Route::post('/reset', [ResetPasswordController::class, 'reset']);

    Route::post('/cart/add', [CartController::class, 'addProducts']);
    Route::get('/cart/clear', [CartController::class, 'clearProducts']);
    Route::get('/cart/quantity', [CartController::class, 'getQuantity']);
    Route::post('/cart', [CartController::class, 'getCart']);
});

Route::middleware(['auth:sanctum'])->group(function () {
    Route::get('/perfil', [SessionController::class, 'perfil']);
    Route::get('/logout', [SessionController::class, 'logout']);

    Route::post('/checkout', [OrderController::class, 'checkout']);
});
