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
Route::get('/', function () {
    return view('welcome');
});

// --- General Authenticated Routes ---
Route::middleware(['auth', 'verified'])->group(function () {
    // Redirect l-dashboard l-s7i7 3la 7ssab l-role
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


// --- ADMIN ROUTES (Gher Admin li idkhl hna) ---
Route::middleware(['auth', 'role:admin'])->prefix('admin')->name('admin.')->group(function () {

    // Admin Dashboard
    Route::get('/dashboard', [AdminDashboardController::class, 'index'])->name('dashboard');

    // CRUDs dyal Admin
    Route::resource('users', UserController::class); // Tzid/Tms7 users
    Route::resource('stores', StoreController::class); // Tzid/Tms7 stores
    Route::resource('suppliers', SupplierController::class); // Tzid/Tms7 Suppliers
    Route::resource('orders', OrderController::class); // Tzid/Tms7 orders
});


// --- Supplier ROUTES (Gher Supplier li idkhl hna) ---
Route::middleware(['auth', 'role:supplier'])->prefix('supplier')->name('supplier.')->group(function () {

    // Supplier Dashboard
    Route::get('/dashboard', [SupplierDashboardController::class, 'index'])->name('dashboard');
    // Hna ghadi ichof gher les orders dyalo
});


// --- Breeze Auth Routes ---
require __DIR__.'/auth.php';
