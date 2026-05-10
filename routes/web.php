<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Frontend\HomeController;

// Route::get('/', function () {
//     // return view('welcome');
//     return view('frontend.home');
// });

Route::get('/', [HomeController::class, 'Index']);