<?php

use Illuminate\Support\Facades\Route;

// Import Controllers dyal Admin
use App\Http\Controllers\Admin\AdminDashboardController;
use App\Http\Controllers\Admin\StoreController;
use App\Http\Controllers\Admin\SupplierController;
use App\Http\Controllers\Admin\OrderController;
use App\Http\Controllers\Admin\UserController;

// Import Controller dyal Supplier
use App\Http\Controllers\Supplier\SupplierDashboardController;

// Import Controller dyal Profile (mn Breeze)
use App\Http\Controllers\ProfileController;

// --- Public Routes ---
Route::redirect('/', '/login');

// --- General Authenticated Routes ---
Route::middleware(['auth', 'verified'])->group(function () {
    Route::get('/dashboard', function () {
        if (auth()->user()->role == 'admin') {
            return redirect()->route('admin.dashboard');
        } else {
            return redirect()->route('supplier.dashboard');
        }
    })->name('dashboard');

    // Profile Routes
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});


Route::middleware(['auth', 'role:admin'])->prefix('admin')->name('admin.')->group(function () {

    Route::get('/dashboard', [AdminDashboardController::class, 'index'])->name('dashboard');

    Route::resource('users', UserController::class);
    Route::resource('stores', StoreController::class);
    Route::resource('suppliers', SupplierController::class);
    Route::resource('orders', OrderController::class);
});


Route::middleware(['auth', 'role:supplier'])->prefix('supplier')->name('supplier.')->group(function () {

    Route::get('/dashboard', [SupplierDashboardController::class, 'index'])->name('dashboard');
});


// --- Breeze Auth Routes ---
require __DIR__.'/auth.php';
