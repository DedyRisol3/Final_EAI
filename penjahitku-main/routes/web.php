<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\CartController;
use App\Http\Controllers\CheckoutController;
use App\Http\Controllers\Admin\ProductController as AdminProductController;
use App\Http\Controllers\Admin\OrderController as AdminOrderController;
use App\Http\Controllers\Admin\DashboardController as AdminDashboardController;
use App\Http\Controllers\Auth\GoogleAuthController;
use App\Http\Controllers\Auth\LoyaltyAuthController;
use App\Http\Controllers\RajaOngkirController;
use Illuminate\Support\Facades\Route;
use Illuminate\Http\Request;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
*/

// === Halaman utama ===
Route::get('/', [ProductController::class, 'index'])->name('home');

// === Dashboard user biasa (bukan admin) ===
Route::get('/dashboard', function () {
    return redirect('/');
})->middleware(['auth', 'verified'])->name('dashboard');

// === Profil pengguna ===
Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

// === Google Login ===
Route::get('/auth/google/redirect', [GoogleAuthController::class, 'redirect'])->name('google.redirect');
Route::get('/auth/google/callback', [GoogleAuthController::class, 'callback'])->name('google.callback');

// === Loyalty SSO Support ===
Route::get('/loyalty/authorize', [LoyaltyAuthController::class, 'authorize'])->name('loyalty.authorize');

// === Auth bawaan Breeze ===
require __DIR__ . '/auth.php';

// === Produk (depan) ===
Route::get('/produk/{product:slug}', [ProductController::class, 'show'])->name('produk.show');

// === Keranjang & Checkout (harus login) ===
Route::middleware('auth')->group(function () {
    Route::get('/keranjang', [CartController::class, 'index'])->name('keranjang.index');
    Route::post('/keranjang/tambah', [CartController::class, 'store'])->name('keranjang.store');
    Route::patch('/keranjang/update/{cartId}', [CartController::class, 'update'])->name('keranjang.update');
    Route::delete('/keranjang/hapus/{cartId}', [CartController::class, 'destroy'])->name('keranjang.destroy');
    Route::get('/keranjang/data', [CartController::class, 'data'])->name('keranjang.data');

    Route::get('/checkout', [CheckoutController::class, 'index'])->name('checkout.index');
    Route::post('/checkout', [CheckoutController::class, 'store'])->name('checkout.store');

    Route::get('/checkout/success', function () {
        return view('checkout.success');
    })->name('checkout.success');

    Route::get('/checkout/failed', function () {
        return view('checkout.failed');
    })->name('checkout.failed');
});

// ✅ Tambahan baru: Halaman sukses & gagal (untuk Xendit)
// (Opsional) masih bisa simpan halaman terimakasih untuk non-Xendit
Route::get('/terimakasih', function () {
    return view('terimakasih');
})->name('checkout.thanks');

// === Grup Route Admin ===
Route::middleware(['auth', 'admin'])->prefix('admin')->name('admin.')->group(function () {
    // Dashboard Admin
    Route::get('/dashboard', [AdminDashboardController::class, 'index'])->name('dashboard');

    // Produk Admin
    Route::get('/produk', [AdminProductController::class, 'index'])->name('produk.index');
    Route::get('/produk/create', [AdminProductController::class, 'create'])->name('produk.create');
    Route::post('/produk', [AdminProductController::class, 'store'])->name('produk.store');
    Route::get('/produk/{productId}/edit', [AdminProductController::class, 'edit'])->name('produk.edit');
    Route::patch('/produk/{productId}', [AdminProductController::class, 'update'])->name('produk.update');
    Route::delete('/produk/{productId}', [AdminProductController::class, 'destroy'])->name('produk.destroy');

    // Pesanan Admin
    Route::get('/pesanan', [AdminOrderController::class, 'index'])->name('pesanan.index');
    Route::get('/pesanan/{orderId}', [AdminOrderController::class, 'show'])->name('pesanan.show');
    Route::get('/pesanan/{orderId}/edit', [AdminOrderController::class, 'edit'])->name('pesanan.edit');
    Route::patch('/pesanan/{orderId}', [AdminOrderController::class, 'update'])->name('pesanan.update');
    Route::delete('/pesanan/{orderId}', [AdminOrderController::class, 'destroy'])->name('pesanan.destroy');
});
Route::get('/cities/{provinceId}', [RajaOngkirController::class, 'getCities']);
Route::get('/districts/{cityId}', [RajaOngkirController::class, 'getDistricts']);
Route::post('/check-ongkir', [RajaOngkirController::class, 'checkOngkir']);
