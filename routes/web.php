<?php

use Illuminate\Support\Facades\Route;

use App\Http\Controllers\AuthController;
use App\Http\Controllers\BarangController;
use App\Http\Controllers\MarginPenjualanController;
use App\Http\Controllers\RoleController;
use App\Http\Controllers\SatuanController;
use App\Http\Controllers\VendorController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\ManagerDashboardController;
use App\Http\Controllers\GudangDashboardController;
use App\Http\Controllers\KasirDashboardController;
use App\Http\Controllers\PembelianDashboardController;
use App\Http\Controllers\PenjualanController;
use App\Http\Controllers\PengadaanController;
use App\Http\Controllers\PenerimaanController;


// ========================================
// HALAMAN UTAMA
// ========================================

Route::get('/', function () {
    return redirect()->route('login');
});


// ========================================
// LOGIN DAN LOGOUT
// ========================================

Route::get('/login', [AuthController::class, 'showLogin'])
    ->name('login');

Route::post('/login', [AuthController::class, 'login'])
    ->name('login.process');

Route::post('/logout', [AuthController::class, 'logout'])
    ->name('logout');


// ========================================
// DASHBOARD ADMINISTRATOR
// ========================================

Route::get('/dashboard/administrator', function () {
    return view('dashboard.administrator');
})->middleware('role:Administrator')
  ->name('dashboard.administrator');


// ========================================
// DASHBOARD KASIR
// ========================================

Route::get('/dashboard/kasir', [KasirDashboardController::class, 'index'])
    ->middleware('role:Kasir')
    ->name('dashboard.kasir');


// ========================================
// DASHBOARD GUDANG
// ========================================

Route::get('/dashboard/gudang', [GudangDashboardController::class, 'index'])
    ->middleware('role:Gudang')
    ->name('dashboard.gudang');


// ========================================
// DASHBOARD MANAGER
// ========================================

Route::get('/dashboard/manager', [ManagerDashboardController::class, 'index'])
    ->middleware('role:Manager')
    ->name('dashboard.manager');


// ========================================
// DASHBOARD STAFF PEMBELIAN
// ========================================

Route::get('/dashboard/pembelian', [PembelianDashboardController::class, 'index'])
    ->middleware('role:Staff Pembelian')
    ->name('dashboard.pembelian');


// ========================================
// CRUD BARANG
// ========================================

// Semua role boleh melihat barang
Route::middleware(['role:Administrator,Kasir,Gudang,Manager,Staff Pembelian'])
    ->group(function () {

        Route::get('/barang', [BarangController::class, 'index'])
            ->name('barang.index');

        Route::get('/penjualan', [PenjualanController::class, 'index'])
            ->name('penjualan.index');

    });

// Administrator, Gudang, Staff Pembelian boleh mengelola
Route::middleware(['role:Administrator,Gudang,Staff Pembelian'])
    ->group(function () {

        Route::get('/barang/create', [BarangController::class, 'create'])
            ->name('barang.create');

        Route::post('/barang', [BarangController::class, 'store'])
            ->name('barang.store');

        Route::get('/barang/{id}/edit', [BarangController::class, 'edit'])
            ->name('barang.edit');

        Route::put('/barang/{id}', [BarangController::class, 'update'])
            ->name('barang.update');

        Route::delete('/barang/{id}', [BarangController::class, 'destroy'])
            ->name('barang.destroy');

    });


// ========================================
// CRUD MARGIN PENJUALAN
// ========================================

// Administrator dan Manager boleh melihat
Route::middleware(['role:Administrator,Manager'])
    ->group(function () {

        Route::get('/margin', [MarginPenjualanController::class, 'index'])
            ->name('margin.index');

    });

// Hanya Administrator boleh mengelola
Route::middleware(['role:Administrator'])
    ->group(function () {

        Route::get('/margin/create', [MarginPenjualanController::class, 'create'])
            ->name('margin.create');

        Route::post('/margin', [MarginPenjualanController::class, 'store'])
            ->name('margin.store');

        Route::get('/margin/{id}/edit', [MarginPenjualanController::class, 'edit'])
            ->name('margin.edit');

        Route::put('/margin/{id}', [MarginPenjualanController::class, 'update'])
            ->name('margin.update');

        Route::delete('/margin/{id}', [MarginPenjualanController::class, 'destroy'])
            ->name('margin.destroy');

    });


// ========================================
// CRUD ROLE
// ========================================

Route::middleware(['role:Administrator'])
    ->group(function () {

        Route::get('/role', [RoleController::class, 'index'])
            ->name('role.index');

        Route::get('/role/create', [RoleController::class, 'create'])
            ->name('role.create');

        Route::post('/role', [RoleController::class, 'store'])
            ->name('role.store');

        Route::get('/role/{id}/edit', [RoleController::class, 'edit'])
            ->name('role.edit');

        Route::put('/role/{id}', [RoleController::class, 'update'])
            ->name('role.update');

        Route::delete('/role/{id}', [RoleController::class, 'destroy'])
            ->name('role.destroy');

    });


// ========================================
// CRUD SATUAN
// ========================================

// Administrator, Gudang, Manager boleh melihat
Route::middleware(['role:Administrator,Gudang,Manager'])
    ->group(function () {

        Route::get('/satuan', [SatuanController::class, 'index'])
            ->name('satuan.index');

    });

// Administrator dan Gudang boleh mengelola
Route::middleware(['role:Administrator,Gudang'])
    ->group(function () {

        Route::get('/satuan/create', [SatuanController::class, 'create'])
            ->name('satuan.create');

        Route::post('/satuan', [SatuanController::class, 'store'])
            ->name('satuan.store');

        Route::get('/satuan/{id}/edit', [SatuanController::class, 'edit'])
            ->name('satuan.edit');

        Route::put('/satuan/{id}', [SatuanController::class, 'update'])
            ->name('satuan.update');

        Route::delete('/satuan/{id}', [SatuanController::class, 'destroy'])
            ->name('satuan.destroy');

    });


// ========================================
// CRUD VENDOR
// ========================================

// Administrator, Gudang, Manager, Staff Pembelian boleh melihat
Route::middleware(['role:Administrator,Gudang,Manager,Staff Pembelian'])
    ->group(function () {

        Route::get('/vendor', [VendorController::class, 'index'])
            ->name('vendor.index');

    });

// Administrator, Gudang, Staff Pembelian boleh mengelola
Route::middleware(['role:Administrator,Gudang,Staff Pembelian'])
    ->group(function () {

        Route::get('/vendor/create', [VendorController::class, 'create'])
            ->name('vendor.create');

        Route::post('/vendor', [VendorController::class, 'store'])
            ->name('vendor.store');

        Route::get('/vendor/{id}/edit', [VendorController::class, 'edit'])
            ->name('vendor.edit');

        Route::put('/vendor/{id}', [VendorController::class, 'update'])
            ->name('vendor.update');

        Route::delete('/vendor/{id}', [VendorController::class, 'destroy'])
            ->name('vendor.destroy');

    });


// ========================================
// CRUD USER
// ========================================

Route::middleware(['role:Administrator'])
    ->group(function () {

        Route::get('/user', [UserController::class, 'index'])
            ->name('user.index');

        Route::get('/user/create', [UserController::class, 'create'])
            ->name('user.create');

        Route::post('/user', [UserController::class, 'store'])
            ->name('user.store');

        Route::get('/user/{id}/edit', [UserController::class, 'edit'])
            ->name('user.edit');

        Route::put('/user/{id}', [UserController::class, 'update'])
            ->name('user.update');

        Route::delete('/user/{id}', [UserController::class, 'destroy'])
            ->name('user.destroy');

    });


// ========================================
// CRUD PENGADAAN
// ========================================

// Staff Pembelian boleh melihat dan menambahkan pengadaan
Route::middleware(['role:Staff Pembelian'])
    ->group(function () {

        // Menampilkan daftar pengadaan
        Route::get('/pengadaan', [PengadaanController::class, 'index'])
            ->name('pengadaan.index');

        // Menampilkan form tambah pengadaan
        Route::get('/pengadaan/tambah', [PengadaanController::class, 'create'])
            ->name('pengadaan.create');

        Route::post('/pengadaan', [PengadaanController::class, 'store'])
            ->name('pengadaan.store');

    });

// MODUL PENERIMAAN
Route::middleware(['role:Gudang'])->group(function () {

    Route::get('/penerimaan', [PenerimaanController::class, 'index'])
        ->name('penerimaan.index');

    Route::get('/penerimaan/tambah', [PenerimaanController::class, 'create'])
        ->name('penerimaan.create');

    Route::get('/penerimaan/detail-pengadaan/{id}', [PenerimaanController::class, 'detailPengadaan'])
        ->name('penerimaan.detail');

    Route::post('/penerimaan', [PenerimaanController::class, 'store'])
        ->name('penerimaan.store');

});
