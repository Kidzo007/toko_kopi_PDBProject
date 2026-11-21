<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\BarangController;
use App\Http\Controllers\MarginPenjualanController;
use App\Http\Controllers\RoleController;
use App\Http\Controllers\SatuanController;
use App\Http\Controllers\VendorController;

// CRUD BARANG
Route::get('/barang', [BarangController::class, 'index'])->name('barang.index');

Route::get('/barang/create', [BarangController::class, 'create'])->name('barang.create');

Route::post('/barang', [BarangController::class, 'store'])->name('barang.store');

Route::get('/barang/{id}/edit', [BarangController::class, 'edit'])->name('barang.edit');

Route::put('/barang/{id}', [BarangController::class, 'update'])->name('barang.update');

Route::delete('/barang/{id}', [BarangController::class, 'destroy'])->name('barang.destroy');

// CRUD MARGIN PENJUALAN
Route::get('/margin', [MarginPenjualanController::class, 'index'])->name('margin.index');

Route::get('/margin/create', [MarginPenjualanController::class, 'create'])->name('margin.create');

Route::post('/margin', [MarginPenjualanController::class, 'store'])->name('margin.store');

Route::get('/margin/{id}/edit', [MarginPenjualanController::class, 'edit'])->name('margin.edit');

Route::put('/margin/{id}', [MarginPenjualanController::class, 'update'])->name('margin.update');

Route::delete('/margin/{id}', [MarginPenjualanController::class, 'destroy'])->name('margin.destroy');

// CRUD ROLE
Route::get('/role', [RoleController::class, 'index'])->name('role.index');

Route::get('/role/create', [RoleController::class, 'create'])->name('role.create');

Route::post('/role', [RoleController::class, 'store'])->name('role.store');

Route::get('/role/{id}/edit', [RoleController::class, 'edit'])->name('role.edit');

Route::put('/role/{id}', [RoleController::class, 'update'])->name('role.update');

Route::delete('/role/{id}', [RoleController::class, 'destroy'])->name('role.destroy');

// CRUD SATUAN
Route::get('/satuan', [SatuanController::class, 'index'])->name('satuan.index');

Route::get('/satuan/create', [SatuanController::class, 'create'])->name('satuan.create');

Route::post('/satuan', [SatuanController::class, 'store'])->name('satuan.store');

Route::get('/satuan/{id}/edit', [SatuanController::class, 'edit'])->name('satuan.edit');

Route::put('/satuan/{id}', [SatuanController::class, 'update'])->name('satuan.update');

Route::delete('/satuan/{id}', [SatuanController::class, 'destroy'])->name('satuan.destroy');

// CRUD VENDOR
Route::get('/vendor', [VendorController::class, 'index'])->name('vendor.index');

Route::get('/vendor/create', [VendorController::class, 'create'])->name('vendor.create');

Route::post('/vendor', [VendorController::class, 'store'])->name('vendor.store');

Route::get('/vendor/{id}/edit', [VendorController::class, 'edit'])->name('vendor.edit');

Route::put('/vendor/{id}', [VendorController::class, 'update'])->name('vendor.update');

Route::delete('/vendor/{id}', [VendorController::class, 'destroy'])->name('vendor.destroy');

Route::get('/', function () {
    return view('welcome');
});
