<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\KategoriController;
use App\Http\Controllers\BarangController;
use App\Http\Controllers\TransaksiController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\LaporanController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

// Public Routes
Route::get('/', function () {
    // Jika sudah login, redirect ke dashboard
    if (auth()->check()) {
        return redirect()->route('dashboard');
    }
    // Jika belum login, redirect ke login
    return redirect()->route('login');
});

// Routes untuk semua user yang sudah login (Admin & Staff)
Route::middleware(['auth', 'verified'])->group(function () {
    
    // Dashboard - accessible by both admin and staff
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    // Profile Routes
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile. update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    // CRUD Kategori - Admin & Staff
    Route::resource('kategoris', KategoriController::class);

    // CRUD Barang - Admin & Staff
    Route::resource('barangs', BarangController::class);

    // CRUD Transaksi - Admin & Staff
    Route::resource('transaksis', TransaksiController::class);
});

// Routes khusus Admin
Route::middleware(['auth', 'role:admin'])->group(function () {
    
    // CRUD User (hanya Admin)
    Route::resource('users', UserController::class);
    
    // Laporan (hanya Admin)
    Route::get('/laporan', [LaporanController::class, 'index'])->name('laporan.index');
});

require __DIR__.'/auth.php';