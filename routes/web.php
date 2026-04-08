<?php

use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
})->name('welcome');

Route::view('/store', 'store.index')->name('store');
Route::view('/login', 'auth.login')->name('login');
Route::view('/register', 'auth.register')->name('register');
Route::view('/checkout', 'checkout')->name('checkout');
Route::view('/admin/login', 'admin-login')->name('admin.login');
Route::view('/admin', 'admin')->name('admin');
