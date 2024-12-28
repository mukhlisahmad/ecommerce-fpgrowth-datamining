<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\CustomerAuthController;
use App\Http\Controllers\CustomerDashboardController;
use App\Http\Controllers\CartController;
use App\Http\Controllers\CheckoutController;
use App\Http\Controllers\OrderController;
Route::get('/', [App\Http\Controllers\Landingpage::class, 'index'])->name('landingpage-index');

Route::get('/login', [CustomerAuthController::class, 'viewLogin'])->name('customer.login');
Route::post('/login', [CustomerAuthController::class, 'login'])->name('customer.login.submit');
Route::get('/logout', [CustomerAuthController::class, 'logout'])->name('customer.logout');
Route::get('/customer/dashboard', [CustomerDashboardController::class, 'index'])
    ->name('customer.view')
    ->middleware('auth:customer');
Route::get('/register', [CustomerAuthController::class, 'viewRegister'])->name('customer.register');
Route::post('/register', [CustomerAuthController::class, 'register'])->name('customer.register.submit');

Route::post('/customer/dashboard/add-to-cart', [CartController::class, 'addToCart'])->name('customer.add.to.cart');
Route::get('/customer/cart', [CartController::class, 'index'])->name('customer.cart');
Route::delete('/customer/cart/{id}', [CartController::class, 'destroy'])->name('customer.cart.delete');
Route::get('/customer/checkout', [CheckoutController::class, 'index'])->name('customer.checkout');
Route::post('/customer/checkout', [CheckoutController::class, 'processCheckout'])->name('customer.checkout.process');
Route::get('/customer/orders', [OrderController::class, 'index'])->name('customer.orders');
