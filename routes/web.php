<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Frontend\HomeController;
use App\Http\Controllers\Frontend\CategoryController;
use App\Http\Controllers\Frontend\ProductController;
use App\Http\Controllers\Frontend\CartController;
use App\Http\Controllers\Frontend\RegisterController;
use App\Http\Controllers\Frontend\LoginController;
use App\Http\Controllers\Frontend\SearchController;
use App\Http\Controllers\Frontend\CouponController;
use App\Http\Controllers\Frontend\CheckoutController;
use App\Http\Controllers\Frontend\DeliveryAddressController;
use App\Http\Controllers\Frontend\OrderController;
use App\Http\Controllers\Frontend\AccountController;
use App\Http\Controllers\Frontend\ChangePasswordController;

// Route::get('/', function () {
//     // return view('welcome');
//     return view('frontend.home');
// });

Route::get('/', [HomeController::class, 'Index']);
/*
| User Login Form Route
*/
Route::get('/login',[LoginController::class, 'login'])->name('login');
/*
| Store Logged in User Route
*/
Route::post('/login', [LoginController::class, 'store'])->name('login.store');
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
NEW: Add to cart Route
*/
Route::post('/cart/add', [CartController::class, 'add'])->name('cart.add');
/*
NEW: Count Cart Items
*/
Route::get('/cart/count', [CartController::class, 'count'])->name('cart.count');
/*
| Shopping Cart |
*/
Route::get('/cart', [CartController::class, 'index'])->name('cart.index');
/*
| Shopping Cart Update |
*/
Route::get('/cart/update', [CartController::class, 'update'])->name('cart.update');
/*
| Shopping Cart Delete |
*/
Route::post('/cart/delete', [CartController::class, 'delete'])->name('cart.delete');
/*
| Product Search |
*/
Route::get(
    '/search',
    [SearchController::class, 'index'])->name('search');
/*
| Coupon Routes
*/
Route::post(
    '/cart/apply-coupon',
    [CouponController::class, 'applyCoupon']
)->name('cart.applyCoupon');

Route::post(
    '/cart/remove-coupon',
    [CouponController::class, 'removeCoupon']
)->name('cart.removeCoupon');
/*
| Checkout Midddleware
*/
Route::middleware('auth')->group(function () {
    /*
    |--------------------------------------------------------------------------
    | Checkout
    |--------------------------------------------------------------------------
    */
    Route::get('/checkout', [CheckoutController::class, 'index'])
        ->name('checkout.index');
    /*
    |--------------------------------------------------------------------------
    | Delivery Addresses
    |--------------------------------------------------------------------------
    */
    Route::get(
        '/delivery-addresses',
        [DeliveryAddressController::class, 'index']
    )->name('delivery-addresses.index');

    Route::post(
        '/delivery-addresses',
        [DeliveryAddressController::class, 'store']
    )->name('delivery-addresses.store');

    Route::put(
        '/delivery-addresses/{deliveryAddress}',
        [DeliveryAddressController::class, 'update']
    )->name('delivery-addresses.update');

    Route::delete(
        '/delivery-addresses/{deliveryAddress}',
        [DeliveryAddressController::class, 'destroy']
    )->name('delivery-addresses.destroy');

    Route::post(
        '/delivery-addresses/{deliveryAddress}/default',
        [DeliveryAddressController::class, 'makeDefault']
    )->name('delivery-addresses.make-default');

    /*
    |--------------------------------------------------------------------------
    | Orders
    |--------------------------------------------------------------------------
    */

    Route::get(
        '/orders',
        [OrderController::class, 'index']
    )->name('orders.index');

    Route::get(
        '/orders/{order}',
        [OrderController::class, 'show']
    )->name('orders.show');

    Route::post(
        '/orders',
        [OrderController::class, 'store']
    )->name('orders.store');

    Route::get(
        '/order-success/{order}',
        [OrderController::class, 'success']
    )->name('orders.success');

    Route::get(
        '/orders/{order}/invoice',
        [OrderController::class, 'invoice']
    )->name('orders.invoice');

    /*
    |--------------------------------------------------------------------------
    | Paypal
    |--------------------------------------------------------------------------
    */

    Route::get(
        '/checkout/paypal/{order}',
        [OrderController::class, 'paypalCheckout']
    )->middleware('auth')->name('paypal.checkout');

    Route::post(
        '/checkout/paypal/{order}/create',
        [OrderController::class, 'createPaypalOrder']
    )->middleware('auth')->name('paypal.order.create');

    // Add this route alongside our other PayPal routes.
    Route::post(
        '/checkout/paypal/{order}/capture',
        [OrderController::class, 'capturePaypalOrder']
    )->name('paypal.order.capture');

    /*
    |--------------------------------------------------------------------------
    | Customer Account
    |--------------------------------------------------------------------------
    */

    Route::get(
        '/account',
        [AccountController::class, 'edit']
    )->name('account.edit');

    Route::post(
        '/account',
        [AccountController::class, 'update']
    )->name('account.update');

    // Change password
    Route::get(
        '/account/change-password',
        [ChangePasswordController::class, 'edit']
    )->name('account.password.edit');

    Route::post(
        '/account/change-password',
        [ChangePasswordController::class, 'update']
    )->name('account.password.update');

});
/*
NEW: SEO Friendly Category Route
*/
Route::get('/{slug}', [CategoryController::class, 'listing'])
    ->where('slug', '^[A-Za-z0-9-_]+$');