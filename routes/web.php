<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\BookingController;


Route::get('/', function () {
    return redirect()->route('login');
});


Route::middleware(['auth'])->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
    Route::post('logout', [AuthController::class, 'logout'])->name('logout');
    Route::get('/products/create', [ProductController::class, 'create'])->name('products.create');
});

Route::middleware(['guest'])->group(function () {
    Route::get('login', [AuthController::class, 'showLoginForm'])->name('login');
    Route::post('login', [AuthController::class, 'login']);
    Route::get('register', [AuthController::class, 'showRegistrationForm'])->name('register');
    Route::post('register', [AuthController::class, 'register']);

});

Route::middleware(['auth', 'can:pembeli'])->group(function () {
    Route::get('/products/{product}', [ProductController::class, 'show'])->name('products.show');
    Route::post('/bookings/add-user/{booking}', [BookingController::class, 'addUser'])->name('bookings.add-user');
    Route::get('/my-bookings', [BookingController::class, 'myBookings'])->name('my-bookings');
});


Route::middleware(['auth', 'can:penjual'])->group(function () {
    Route::get('/bookings/create', [BookingController::class, 'create'])->name('bookings.create');
    Route::post('/bookings/store', [BookingController::class, 'store'])->name('bookings.store');
    Route::patch('/bookings/complete/{booking}', [BookingController::class, 'complete'])->name('bookings.complete');
    Route::get('/bookings/{booking}', [BookingController::class, 'show'])->name('bookings.show');
    Route::post('/bookings/register', [BookingController::class, 'register'])->name('bookings.register');

    Route::post('/products', [ProductController::class, 'store'])->name('products.store');
    Route::get('/products/{product}/edit', [ProductController::class, 'edit'])->name('products.edit');
    Route::put('/products/{product}', [ProductController::class, 'update'])->name('products.update');
    Route::get('/bookings', [BookingController::class, 'index'])->name('bookings.index');
    Route::get('/seller-bookings', [BookingController::class, 'sellerBookings'])->name('seller-bookings');
});



