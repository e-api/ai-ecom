<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Frontend\HomeController;
use App\Http\Controllers\Frontend\CategoryController;

// Route::get('/', function () {
//     // return view('welcome');
//     return view('frontend.home');
// });

Route::get('/', [HomeController::class, 'Index']);
Route::get('category/{slug}', [CategoryController::class, 'listing']);