<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Frontend\HomeController;
use App\Http\Controllers\Frontend\CategoryController;
use App\Http\Controllers\Frontend\ProductController;
use App\Http\Controllers\Frontend\CartController;

// Route::get('/', function () {
//     // return view('welcome');
//     return view('frontend.home');
// });

Route::get('/', [HomeController::class, 'Index']);
/*
New: Product Detail Route
*/
Route::get('/product/{slug}', [ProductController::class, 'detail']);
/*
NEW: SEO Friendly Category Route
*/
Route::get('/{slug}', [CategoryController::class, 'listing'])
    ->where('slug', '^[A-Za-z0-9-_]+$');

Route::post('/cart/add', [CartController::class, 'add'])
    ->name('cart.add');
Route::get('/cart/count', [CartController::class, 'count'])
    ->name('cart.count');