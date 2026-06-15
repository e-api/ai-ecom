<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Frontend\HomeController;
use App\Http\Controllers\Frontend\CategoryController;
use App\Http\Controllers\Frontend\ProductController;
use App\Http\Controllers\Frontend\CartController;
use App\Http\Controllers\Frontend\RegisterController;

// Route::get('/', function () {
//     // return view('welcome');
//     return view('frontend.home');
// });

Route::get('/', [HomeController::class, 'Index']);
/*
NEW: Display Register Form Route
*/
Route::get('/register', [RegisterController::class, 'register'])->name('register');
/*
NEW: Store Registered User Route
*/
Route::post('/register', [RegisterController::class, 'store'])->name('register.store');
/*
NEW: Logout Registered User Route
*/
Route::post('/logout', [LoginController::class, 'logout'])->name('logout');
/*
New: Product Detail Route
*/
Route::get('/product/{slug}', [ProductController::class, 'detail']);
/*
NEW: SEO Friendly Category Route
*/
Route::get('/{slug}', [CategoryController::class, 'listing'])
    ->where('slug', '^[A-Za-z0-9-_]+$');
/*
NEW: Add to cart Route
*/
Route::post('/cart/add', [CartController::class, 'add'])
    ->name('cart.add');
Route::get('/cart/count', [CartController::class, 'count'])
    ->name('cart.count');