<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\KaryawanController;
use App\Http\Controllers\AbsenController;
use App\Http\Controllers\LaporanController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\Auth\OwnerLoginController;
use App\Http\Controllers\Auth\RegisteredUserController;
use App\Http\Controllers\OwnerDashboardController;
use App\Http\Controllers\LaporanControllerOwner;

Route::get('/', function () {
    return view('welcome');
})->name('welcome');

// Dasboard
Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

// Data Karyawan
Route::get('/data-karyawan', [KaryawanController::class, 'index'])->name('data-karyawan'); // Rute untuk menampilkan data karyawan
Route::post('/simpan-karyawan', [KaryawanController::class, 'simpanKaryawan'])->name('simpan-karyawan'); // Rute untuk menyimpan data karyawan baru
// Route untuk menghapus karyawan
Route::delete('/karyawan/{id}', [KaryawanController::class, 'hapus'])->name('hapus-karyawan');

// Absensi
// Menampilkan formulir absensi
Route::get('/absen', [AbsenController::class, 'index'])->name('absen');
// Menyimpan data absensi baru
Route::post('/simpan-absensi', [AbsenController::class, 'simpanAbsensi'])->name('simpan-absensi');

// Laporan
Route::get('/laporan', [LaporanController::class, 'index'])->name('laporan.index');
Route::post('/laporan/filter', [LaporanController::class, 'filter'])->name('laporan.filter');

// Profile
Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

// Auth Routes
require __DIR__.'/auth.php';

// Rute untuk registrasi
Route::get('/register', [RegisteredUserController::class, 'create'])->name('register');
Route::post('/register', [RegisteredUserController::class, 'store'])->name('register.store');

// New owner login route
Route::get('/owner/login', [OwnerLoginController::class, 'showLoginForm'])->name('owner.login');
Route::post('/owner/login', [OwnerLoginController::class, 'login'])->name('owner.login.post');

// Rute untuk dashboard owner dengan middleware auth
Route::middleware('auth')->group(function () {
    Route::get('/owner/dashboard', [OwnerDashboardController::class, 'index'])->name('owner.dashboard');
    Route::get('/owner/laporan', [LaporanControllerOwner::class, 'index'])->name('owner.laporan.index');
});


