<?php

use App\Http\Controllers\ContactController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\UserManagementController;
use App\Http\Controllers\DiscountController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\OrderInvoiceController;
use App\Http\Controllers\WelcomeController;
use App\Http\Controllers\LoginController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\RegisterController;
use App\Http\Controllers\ReviewController;
use App\Http\Controllers\ProfileController;
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

// Reviews (read is public, write requires auth)
Route::get('/products/{product}/reviews', [ReviewController::class, 'index'])->name('reviews.index');
Route::post('/products/{product}/reviews', [ReviewController::class, 'store'])->name('reviews.store')->middleware('auth');
Route::delete('/reviews/{review}', [ReviewController::class, 'destroy'])->name('reviews.destroy')->middleware('auth');
Route::get('/products/{product}/review-summary', [ReviewController::class, 'summarize'])->name('reviews.summarize')->middleware('auth');

// Profile picture upload (auth only)
Route::post('/profile/picture', [ProfileController::class, 'updatePicture'])->name('profile.picture')->middleware('auth');

Route::get('/discount/create', [DiscountController::class, 'create'])->name('discount.create');
Route::post('/discounts', [DiscountController::class, 'store'])->name('discounts.store')->middleware('auth');
Route::get('/discount/apply', [DiscountController::class, 'apply'])->name('discount.apply');

Route::get('/dashboard', DashboardController::class)->name('dashboard');

// User / Admin management (admin only)
Route::post('/users', [UserManagementController::class, 'storeUser'])->name('users.store')->middleware('auth');
Route::post('/users/{user}/promote', [UserManagementController::class, 'promote'])->name('users.promote')->middleware('auth');
Route::post('/users/{user}/demote', [UserManagementController::class, 'demote'])->name('users.demote')->middleware('auth');
Route::delete('/users/{user}', [UserManagementController::class, 'destroy'])->name('users.destroy')->middleware('auth');
Route::post('/orders', [OrderController::class, 'store'])->name('orders.store')->middleware('auth');
Route::get('/orders/{order}', [OrderController::class, 'show'])->name('orders.show');
Route::get('orders/{order}/invoice', OrderInvoiceController::class)->name('orders.invoice');
