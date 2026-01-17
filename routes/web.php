<?php

use App\Http\Controllers\KategoriController;
use App\Http\Controllers\MenuController;
use App\Http\Controllers\PesananController;
use App\Http\Controllers\TransaksiController;
use App\Http\Controllers\AdminAuthController;
use App\Http\Controllers\PelangganAuthController;
use App\Http\Controllers\MenuPelangganController;
use App\Http\Controllers\KeranjangController;
use App\Http\Controllers\CheckoutController;
use App\Http\Controllers\StatusController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| ADMIN AUTH
|--------------------------------------------------------------------------
*/
Route::get('/admin/login', [AdminAuthController::class, 'loginPage'])->name('admin.login');
Route::post('/admin/login', [AdminAuthController::class, 'login'])->name('admin.login.process');
Route::get('/admin/register', [AdminAuthController::class, 'showRegister'])->name('admin.register');
Route::post('/admin/register', [AdminAuthController::class, 'register'])->name('admin.register.process');
Route::post('/admin/logout', [AdminAuthController::class, 'logout'])->name('admin.logout');

/*
|--------------------------------------------------------------------------
| ADMIN PAGE (DENGAN MIDDLEWARE)
|--------------------------------------------------------------------------
*/

// Dashboard
Route::middleware('admin')->group(function () {
    Route::get('/admin', function () {
        return view('admin.dashboard.index');
    })->name('admin.dashboard');

    Route::post('/admin/update-photo', [AdminAuthController::class, 'updatePhoto'])->name('admin.updatePhoto');

    // Resource Route
    Route::resource('admin/kategori', KategoriController::class);
    Route::resource('admin/menu', MenuController::class);
    Route::resource('admin/pesanan', PesananController::class);

    // Manual routes for transaksi to ensure proper naming
    Route::get('admin/transaksi', [TransaksiController::class, 'index'])->name('admin/transaksi.index');
    Route::get('admin/transaksi/{id}', [TransaksiController::class, 'show'])->name('admin/transaksi.show');
    Route::get('admin/transaksi/{id}/edit', [TransaksiController::class, 'edit'])->name('admin/transaksi.edit');
    Route::put('admin/transaksi/{id}', [TransaksiController::class, 'update'])->name('admin/transaksi.update');
    Route::delete('admin/transaksi/{id}', [TransaksiController::class, 'destroy'])->name('admin/transaksi.destroy');
    Route::post('admin/transaksi/{id}/confirm-cod', [TransaksiController::class, 'confirmCod'])->name('admin/transaksi.confirm-cod');
});

/*
|--------------------------------------------------------------------------
| PELANGGAN AUTH
|--------------------------------------------------------------------------
*/

// Default login route for auth middleware
Route::get('/login', function () {
    return redirect('/pelanggan/login');
})->name('login');

Route::get('/pelanggan/login', [PelangganAuthController::class, 'showLogin'])->name('pelanggan.login');
Route::post('/pelanggan/login', [PelangganAuthController::class, 'login'])->name('pelanggan.login.process');
Route::get('/pelanggan/register', [PelangganAuthController::class, 'showRegister'])->name('pelanggan.register');
Route::post('/pelanggan/register', [PelangganAuthController::class, 'register'])->name('pelanggan.register.process');
Route::post('/pelanggan/logout', [PelangganAuthController::class, 'logout'])->name('pelanggan.logout');

/*
|--------------------------------------------------------------------------
| PELANGGAN PAGE
|--------------------------------------------------------------------------
|
| Tambahan route untuk pelanggan sesuai controller & blade yang sudah dibuat
|
*/

// Halaman HOME pelanggan (landing page)
Route::get('/', function () {
    return view('pelanggan.home');
})->name('pelanggan.home');

// Halaman daftar menu pelanggan
Route::get('/menu', [MenuPelangganController::class, 'index'])->name('pelanggan.index');

// Menu per kategori
Route::get('/menu/kategori/{id}', [MenuPelangganController::class, 'kategori'])->name('pelanggan.menu.kategori');

// Keranjang
Route::middleware('auth:pelanggan')->group(function () {
    Route::get('/keranjang', [KeranjangController::class, 'index'])->name('keranjang.index');
    Route::post('/keranjang/add/{id}', [KeranjangController::class, 'add'])->name('keranjang.add');
    Route::put('/keranjang/update/{id}', [KeranjangController::class, 'update'])->name('keranjang.update');
    Route::delete('/keranjang/remove/{id}', [KeranjangController::class, 'destroy'])->name('keranjang.destroy');
});

// Checkout
Route::middleware('auth:pelanggan')->group(function () {
    Route::get('/checkout', [CheckoutController::class, 'index'])->name('checkout.index');
    Route::post('/checkout', [CheckoutController::class, 'store'])->name('checkout.store');
    Route::post('/upload-bukti/{checkout_id}', [CheckoutController::class, 'uploadBukti'])->name('checkout.uploadBukti');
    Route::get('/download-qr/{type}/{total}', [CheckoutController::class, 'downloadQR'])->name('checkout.downloadQR');
});

// Status Pesanan
Route::middleware('auth:pelanggan')->group(function () {
    Route::get('/status', [StatusController::class, 'index'])->name('status.index');
    Route::get('/status/{id}', [StatusController::class, 'show'])->name('status.show');
});


