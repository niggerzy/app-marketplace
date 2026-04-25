<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\ProductController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

// Auth routes
Route::get('/register', [AuthController::class, 'showRegisterForm'])->name('register');
Route::post('/register', [AuthController::class, 'register']);

Route::get('/login', [AuthController::class, 'showLoginForm'])->name('login');
Route::post('/login', [AuthController::class, 'login']);

Route::post('/logout', [AuthController::class, 'logout'])->middleware('auth')->name('logout');

// Customer routes
Route::middleware('auth')->group(function () {
    Route::get('/dashboard', function () {
        return view('customer.dashboard');
    })->name('dashboard');
});

// Public product routes
Route::get('/products', [ProductController::class, 'index'])->name('products.index');
Route::get('/products/{product}', [ProductController::class, 'show'])->name('products.show');

// Admin routes
Route::middleware(['auth', 'admin'])->prefix('admin')->name('admin.')->group(function () {
    Route::get('/dashboard', function () {
        return view('admin.dashboard');
    })->name('dashboard');

    Route::resource('products', ProductController::class);
    Route::resource('categories', CategoryController::class);
});

// Cart routes
Route::get('/cart', 'CartController@index')->name('cart.index');
Route::post('/cart/add', 'CartController@add')->name('cart.add');
Route::patch('/cart/{productId}', 'CartController@update')->name('cart.update');
Route::delete('/cart/{productId}', 'CartController@remove')->name('cart.remove');
Route::delete('/cart', 'CartController@clear')->name('cart.clear');

// Checkout & Order routes
Route::middleware('auth')->group(function () {
    Route::get('/checkout', 'OrderController@checkout')->name('checkout.index');
    Route::post('/orders', 'OrderController@store')->name('orders.store');
    Route::get('/orders', 'OrderController@index')->name('orders.index');
    Route::get('/orders/{order}', 'OrderController@show')->name('orders.show');
    Route::post('/orders/{order}/cancel', 'OrderController@cancel')->name('orders.cancel');

    // Reviews routes
    Route::post('/products/{product}/reviews', 'ReviewController@store')->name('reviews.store');
    Route::delete('/reviews/{review}', 'ReviewController@destroy')->name('reviews.destroy');

    // Wishlist routes
    Route::get('/wishlists', 'WishlistController@index')->name('wishlists.index');
    Route::post('/products/{product}/wishlist', 'WishlistController@add')->name('wishlists.add');
    Route::delete('/wishlists/{wishlist}', 'WishlistController@remove')->name('wishlists.remove');
});
