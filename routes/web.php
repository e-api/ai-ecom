<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Frontend\HomeController;
use App\Http\Controllers\Frontend\CategoryController;

// Route::get('/', function () {
//     // return view('welcome');
//     return view('frontend.home');
// });

Route::get('/', [HomeController::class, 'Index']);
/*
NEW: SEO Friendly Category Route
*/
Route::get('/{slug}', [CategoryController::class, 'listing'])
    ->where('slug', '^[A-Za-z0-9-_]+$');