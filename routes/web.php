<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\ShopController;
use App\Http\Controllers\CategoryController;

// ==================== PUBLIC ROUTES (Anyone can access) ====================

Route::get('/', [HomeController::class, 'index'])->name('home');

// Shop browsing
Route::get('/shops', [ShopController::class, 'index'])->name('shops.index');
Route::get('/shops/{shop:slug}', [ShopController::class, 'show'])->name('shops.show');

// Category browsing
Route::get('/categories', [CategoryController::class, 'index'])->name('categories.index');
Route::get('/categories/{category:slug}', [CategoryController::class, 'show'])->name('categories.show');

// Gown browsing (public)
Route::get('/gowns', [App\Http\Controllers\Customer\GownController::class, 'index'])->name('gowns.index');
Route::get('/gowns/{slug}', [App\Http\Controllers\Customer\GownController::class, 'show'])->name('gowns.show');
Route::get('/search', [App\Http\Controllers\Customer\GownController::class, 'search'])->name('gowns.search');

// ==================== AUTHENTICATION ROUTES (Laravel UI) ====================
Auth::routes();

// ==================== REDIRECT AFTER LOGIN ====================
Route::get('/dashboard', function () {
    $user = Auth::user();
    
    if ($user->role === 'admin') {
        return redirect()->route('admin.dashboard');
    } elseif ($user->role === 'shop_owner') {
        return redirect()->route('shop.dashboard');
    } else {
        return redirect()->route('customer.dashboard');
    }
})->middleware('auth')->name('dashboard');

// ==================== ADMIN ROUTES ====================
Route::prefix('admin')->middleware(['auth', 'admin'])->name('admin.')->group(function () {
    
    // Dashboard
    Route::get('/dashboard', [App\Http\Controllers\Admin\DashboardController::class, 'index'])->name('dashboard');
    
    // Shop Management
    Route::get('/shops', [App\Http\Controllers\Admin\ShopManagementController::class, 'index'])->name('shops.index');
    Route::get('/shops/{shop}', [App\Http\Controllers\Admin\ShopManagementController::class, 'show'])->name('shops.show');
    Route::post('/shops/{shop}/approve', [App\Http\Controllers\Admin\ShopManagementController::class, 'approve'])->name('shops.approve');
    Route::post('/shops/{shop}/reject', [App\Http\Controllers\Admin\ShopManagementController::class, 'reject'])->name('shops.reject');
    Route::post('/shops/{shop}/toggle', [App\Http\Controllers\Admin\ShopManagementController::class, 'toggleStatus'])->name('shops.toggle');
    Route::delete('/shops/{shop}', [App\Http\Controllers\Admin\ShopManagementController::class, 'destroy'])->name('shops.destroy');
    
    // User Management
    Route::get('/users', [App\Http\Controllers\Admin\UserManagementController::class, 'index'])->name('users.index');
    Route::get('/users/{user}', [App\Http\Controllers\Admin\UserManagementController::class, 'show'])->name('users.show');
    Route::post('/users/{user}/role', [App\Http\Controllers\Admin\UserManagementController::class, 'changeRole'])->name('users.role');
    Route::delete('/users/{user}', [App\Http\Controllers\Admin\UserManagementController::class, 'destroy'])->name('users.destroy');
    

    // Customer routes
Route::prefix('customer')->middleware(['auth', 'customer'])->name('customer.')->group(function () {
    Route::get('/dashboard', [App\Http\Controllers\Customer\DashboardController::class, 'index'])->name('dashboard');
    Route::get('/gowns', [App\Http\Controllers\Customer\GownController::class, 'index'])->name('gowns.index');
    Route::get('/gowns/{slug}', [App\Http\Controllers\Customer\GownController::class, 'show'])->name('gowns.show');
    Route::get('/bookings', [App\Http\Controllers\Customer\BookingController::class, 'index'])->name('bookings.index');
    Route::get('/bookings/{booking}', [App\Http\Controllers\Customer\BookingController::class, 'show'])->name('bookings.show');
});
    // Review Moderation
    Route::get('/reviews/pending', [App\Http\Controllers\Admin\ReviewModerationController::class, 'pending'])->name('reviews.pending');
    Route::get('/reviews', [App\Http\Controllers\Admin\ReviewModerationController::class, 'index'])->name('reviews.index');
    Route::post('/reviews/{review}/approve', [App\Http\Controllers\Admin\ReviewModerationController::class, 'approve'])->name('reviews.approve');
    Route::delete('/reviews/{review}/reject', [App\Http\Controllers\Admin\ReviewModerationController::class, 'reject'])->name('reviews.reject');
});

// ==================== SHOP OWNER ROUTES ====================
Route::prefix('shop')->middleware(['auth', 'shop.owner'])->name('shop.')->group(function () {
    
    // Dashboard
    Route::get('/dashboard', [App\Http\Controllers\ShopOwner\DashboardController::class, 'index'])->name('dashboard');
    
    // Shop Setup & Management
    Route::get('/setup', [App\Http\Controllers\ShopOwner\ShopController::class, 'setup'])->name('setup');
    Route::post('/setup', [App\Http\Controllers\ShopOwner\ShopController::class, 'store'])->name('setup.store');
    Route::get('/edit', [App\Http\Controllers\ShopOwner\ShopController::class, 'edit'])->name('edit');
    Route::put('/update', [App\Http\Controllers\ShopOwner\ShopController::class, 'update'])->name('update');
    
    // Gown Management
    Route::resource('gowns', App\Http\Controllers\ShopOwner\GownController::class)->except(['show']);
    Route::post('/gowns/{gown}/toggle', [App\Http\Controllers\ShopOwner\GownController::class, 'toggleAvailability'])->name('gowns.toggle');
    
    // Booking Management
    Route::get('/bookings', [App\Http\Controllers\ShopOwner\BookingController::class, 'index'])->name('bookings.index');
    Route::get('/bookings/{booking}', [App\Http\Controllers\ShopOwner\BookingController::class, 'show'])->name('bookings.show');
    Route::post('/bookings/{booking}/confirm', [App\Http\Controllers\ShopOwner\BookingController::class, 'confirm'])->name('bookings.confirm');
    Route::post('/bookings/{booking}/pickup', [App\Http\Controllers\ShopOwner\BookingController::class, 'markPickup'])->name('bookings.pickup');
    Route::post('/bookings/{booking}/return', [App\Http\Controllers\ShopOwner\BookingController::class, 'markReturn'])->name('bookings.return');
    Route::post('/bookings/{booking}/complete', [App\Http\Controllers\ShopOwner\BookingController::class, 'complete'])->name('bookings.complete');
    Route::post('/bookings/{booking}/cancel', [App\Http\Controllers\ShopOwner\BookingController::class, 'cancel'])->name('bookings.cancel');
});

// ==================== CUSTOMER ROUTES ====================
Route::prefix('customer')->middleware(['auth', 'customer'])->name('customer.')->group(function () {
    
    // Dashboard
    Route::get('/dashboard', [App\Http\Controllers\Customer\DashboardController::class, 'index'])->name('dashboard');
    
    // Gown Browsing (Customer specific with booking context)
    Route::get('/gowns', [App\Http\Controllers\Customer\GownController::class, 'index'])->name('gowns.index');
    Route::get('/gowns/{slug}', [App\Http\Controllers\Customer\GownController::class, 'show'])->name('gowns.show');
    Route::get('/search', [App\Http\Controllers\Customer\GownController::class, 'search'])->name('gowns.search');
    
    // Booking Management
    Route::get('/bookings', [App\Http\Controllers\Customer\BookingController::class, 'index'])->name('bookings.index');
    Route::get('/bookings/create/{gown}', [App\Http\Controllers\Customer\BookingController::class, 'create'])->name('bookings.create');
    Route::post('/bookings', [App\Http\Controllers\Customer\BookingController::class, 'store'])->name('bookings.store');
    Route::get('/bookings/{booking}', [App\Http\Controllers\Customer\BookingController::class, 'show'])->name('bookings.show');
    Route::post('/bookings/{booking}/cancel', [App\Http\Controllers\Customer\BookingController::class, 'cancel'])->name('bookings.cancel');
    Route::post('/bookings/check-availability', [App\Http\Controllers\Customer\BookingController::class, 'checkAvailability'])->name('bookings.check');
    
    // Reviews
    Route::get('/reviews/create/{booking}', [App\Http\Controllers\Customer\ReviewController::class, 'create'])->name('reviews.create');
    Route::post('/reviews/{booking}', [App\Http\Controllers\Customer\ReviewController::class, 'store'])->name('reviews.store');
    Route::get('/my-reviews', [App\Http\Controllers\Customer\ReviewController::class, 'myReviews'])->name('reviews.index');
    
    // Profile Management
    Route::get('/profile', [App\Http\Controllers\Customer\ProfileController::class, 'edit'])->name('profile.edit');
    Route::put('/profile', [App\Http\Controllers\Customer\ProfileController::class, 'update'])->name('profile.update');
});

Route::get('/home', [App\Http\Controllers\HomeController::class, 'index']);
