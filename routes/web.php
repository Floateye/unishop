<?php

use App\Http\Controllers\ContactController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\DiscountController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\OrderInvoiceController;
use App\Http\Controllers\WelcomeController;
use App\Http\Controllers\LoginController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\RegisterController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AdminLoginController;

Route::get('/', WelcomeController::class)->name('welcome');

Route::get('/login', [LoginController::class, 'create'])->name('login.create');
Route::post('/login', [LoginController::class, 'store'])->name('login.store');
Route::get('/register', [RegisterController::class, 'create'])->name('register.create');
Route::post('/register', [RegisterController::class, 'store'])->name('register.store');
Route::post('/admin-login',[AdminLoginController::class,'store'])->name('admin-login.store');
Route::get('/admin-login',[AdminLoginController::class,'create'])->name('admin-login.create');
Route::get('/contact', ContactController::class)->name('contact');

Route::get('/products', [ProductController::class, 'index'])->name('products.index');
Route::get('/products/{product}', [ProductController::class, 'show'])->name('products.show');
Route::get('/products/{product}/edit', [ProductController::class, 'edit'])->name('products.edit');
Route::post('/products', [ProductController::class, 'store'])->name('products.store');
Route::delete('/products/{product}', [ProductController::class, 'destroy'])->name('products.destroy');

Route::get('/discount/create' , [DiscountController ::class, 'create'])->name('discount.create');






Route::get('/dashboard', DashboardController::class)->name('dashboard');
Route::get('/orders/{order}', [OrderController::class, 'show'])->name('orders.show');
Route::get('orders/{order}/invoice', OrderInvoiceController::class)->name('orders.invoice');
