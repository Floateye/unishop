<?php

use App\Http\Controllers\WelcomeController;
use App\Http\Controllers\LoginController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\RegisterController;
use Illuminate\Support\Facades\Route;

Route::get('/', WelcomeController::class)->name('welcome');

Route::get('/login',[LoginController::class,'create'])->name('login.create');
Route::post('/login',[LoginController::class,'store'])->name('login.store');
Route::get('/register',[RegisterController::class,'create'])->name('register.create');
Route::post('/register',[RegisterController::class,'store'])->name('register.store');

Route::get('/products',[ProductController::class,'index'] )->name('products.index');
Route::post('/cart',[CartController::class,'store'])->name('cart.store');
