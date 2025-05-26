<?php

use App\Http\Controllers\AntrianController;
use App\Http\Controllers\AppUserController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\BeritaController;
use App\Http\Controllers\ClusterController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\DoctorController;
use App\Http\Controllers\DoctorScheduleController;
use App\Http\Controllers\KategoriBeritaController;
use App\Http\Controllers\PasienController;
use App\Http\Controllers\PengumumanController;
use App\Http\Controllers\FaqController;
use App\Http\Controllers\PendaftaranController;

// Authentication Routes
Route::get('/', [AuthController::class, 'showLoginForm'])->name('login');
Route::post('/login', [AuthController::class, 'login'])->name('login.attempt');
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

// Dashboard Routes
Route::middleware(['auth'])->group(function () {
    // Dashboard Home
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
    Route::get('/profile', [DashboardController::class, 'profile'])->name('profile');
    
    // Pasien Routes (CRUD)
    Route::resource('pasien', PasienController::class);
    // Dokter Routes (CRUD)
    Route::resource('dokter', DoctorController::class);

    Route::resource('jadwal_dokter', DoctorScheduleController::class);

    Route::resource('klaster', ClusterController::class);
    
    Route::resource('pengumuman', PengumumanController::class);

    Route::resource('berita', BeritaController::class);

    Route::resource('kategori_berita', KategoriBeritaController::class);


    Route::resource('faq', FaqController::class);

    Route::resource('app-users', AppUserController::class)->only(['index', 'create', 'store', 'destroy']);

    Route::get('/pendaftaran', [PendaftaranController::class, 'index'])->name('pendaftaran.index');
    Route::post('/pendaftaran', [PendaftaranController::class, 'store'])->name('pendaftaran.store');
    Route::get('/pendaftaran/{pendaftaran}/edit', [PendaftaranController::class, 'edit'])->name('pendaftaran.edit');
    Route::put('/pendaftaran/{pendaftaran}', [PendaftaranController::class, 'update'])->name('pendaftaran.update');
    
    Route::get('/antrian', [AntrianController::class, 'index'])->name('antrian.index');
    Route::put('/antrian/{antrian}', [AntrianController::class, 'update'])->name('antrian.update');
    Route::delete('/antrian/{antrian}', [AntrianController::class, 'destroy'])->name('antrian.destroy');
    Route::get('/pendaftaran/create', [PendaftaranController::class, 'create'])->name('pendaftaran.create');

});
