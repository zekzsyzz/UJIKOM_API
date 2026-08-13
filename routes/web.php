<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\PetugasController;
use App\Http\Controllers\PeminjamController;
use App\Http\Controllers\AdminController;

Route::get('/', function () {
    return view('welcome');
});

Route::middleware(['auth', 'role:admin'])->prefix('admin')->name('admin.')->group(function(){
    Route::get('/dashboard', [App\Http\Controllers\AdminController::class, 'index'])->name('dashboard');

    Route::get('/alat', [App\Http\Controllers\AdminController::class, 'indexalat'])->name('alat.index');
    Route::post('/alat', [App\Http\Controllers\AdminController::class, 'storealat'])->name('alat.store');

    Route::get('/user', [App\Http\Controllers\AdminController::class, 'indexuser'])->name('user.index');

    Route::get('/user/create', [App\Http\Controllers\AdminController::class, 'createuser'])->name('user.create');
    Route::post('/user', [App\Http\Controllers\AdminController::class, 'storeuser'])->name('user.store');

    Route::get('/user/{id}/edit', [App\Http\Controllers\AdminController::class, 'edituser'])->name('user.edit');
    Route::put('/user/{id}', [App\Http\Controllers\AdminController::class, 'updateuser'])->name('user.update');
    Route::delete('/user/{id}', [App\Http\Controllers\AdminController::class, 'destroyuser'])->name('user.destroy');

    Route::get('/kategori', [App\Http\Controllers\AdminController::class, 'indexkategori'])->name('kategori.index');
    Route::get('/kategori/create', [App\Http\Controllers\AdminController::class, 'createkategori'])->name('kategori.create');
    Route::post('/kategori', [App\Http\Controllers\AdminController::class, 'storekategori'])->name('kategori.store');
    Route::get('/kategori/{id}/edit', [App\Http\Controllers\AdminController::class, 'editkategori'])->name('kategori.edit');
    Route::put('/kategori/{id}', [App\Http\Controllers\AdminController::class, 'updatekategori'])->name('kategori.update');
    Route::delete('/kategori/{id}', [App\Http\Controllers\AdminController::class, 'destroykategori'])->name('kategori.destroy');

    Route::get('/alat', [App\Http\Controllers\AdminController::class, 'indexalat'])->name('alat.index');
    Route::get('/alat/create', [App\Http\Controllers\AdminController::class, 'createalat'])->name('alat.create');
    Route::post('/alat', [App\Http\Controllers\AdminController::class, 'storealat'])->name('alat.store');
    Route::get('/alat/{id}/edit', [App\Http\Controllers\AdminController::class, 'editalat'])->name('alat.edit');
    Route::put('/alat/{id}', [App\Http\Controllers\AdminController::class, 'updatealat'])->name('alat.update');
    Route::delete('/alat/{id}', [App\Http\Controllers\AdminController::class, 'destroyalat'])->name('alat.destroy');

    Route::get('/peminjaman', [App\Http\Controllers\AdminController::class, 'indexpeminjaman'])->name('peminjaman.index');
    Route::get('/peminjaman/create', [App\Http\Controllers\AdminController::class, 'createpeminjaman'])->name('peminjaman.create');
    Route::post('/peminjaman', [App\Http\Controllers\AdminController::class, 'storepeminjaman'])->name('peminjaman.store');
    Route::put('/peminjaman/{id}/status', [App\Http\Controllers\AdminController::class, 'updatepeminjaman'])->name('peminjaman.update');
    Route::delete('/peminjaman/{id}', [App\Http\Controllers\AdminController::class, 'destroypeminjaman'])->name('peminjaman.destroy');
});

Route::middleware(['auth', 'role:petugas'])->prefix('petugas')->name('petugas.')->group(function(){
    Route::get('/peminjaman', [App\Http\Controllers\PetugasController::class, 'indexpeminjaman'])->name('peminjaman.index');
    Route::post('/peminjaman/{id}/setuju', [App\Http\Controllers\PetugasController::class, 'setujuipeminjaman'])->name('peminjaman.setujui');

    Route::post('/pengembalian/{id}', [App\Http\Controllers\PetugasController::class, 'prosespengembalian'])->name('pengembalian.proses');
});

Route::middleware(['auth', 'role:peminjam'])->prefix('peminjam')->name('peminjam.')->group(function(){
    Route::get('/katalog', [App\Http\Controllers\PeminjamController::class, 'katalogalat'])->name('katalog');
    Route::post('/peminjaman/ajukan', [App\Http\Controllers\PeminjamController::class, 'ajukanpeminjaman'])->name('peminjaman.ajukan');
    Route::get('/riwayat', [App\Http\Controllers\PeminjamController::class, 'riwayatpeminjaman'])->name('riwayat');
});

Route::middleware('guest')->group(function() {
    Route::get('/login', [App\Http\Controllers\AuthController::class, 'showLoginForm'])->name('login');
    Route::post('/login', [App\Http\Controllers\AuthController::class, 'login']);
});

Route::post('/logout', [App\Http\Controllers\AuthController::class, 'logout'])->name('logout')->middleware('auth');
