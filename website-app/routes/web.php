<?php

use App\Http\Controllers\AntrianController;
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

// Authentication Routes
Route::get('/', [AuthController::class, 'showLoginForm'])->name('login');
Route::get('/login', function () {
    return redirect()->route('login');
});
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

    Route::resource('antrian', AntrianController::class);

    // App Users Management
    Route::get('/app-users', [App\Http\Controllers\AppUserController::class, 'index'])->name('app-users.index');
    Route::get('/app-users/create', [App\Http\Controllers\AppUserController::class, 'create'])->name('app-users.create');
    Route::post('/app-users', [App\Http\Controllers\AppUserController::class, 'store'])->name('app-users.store');
    Route::get('/app-users/{id}', [App\Http\Controllers\AppUserController::class, 'show'])->name('app-users.show');
    Route::get('/app-users/{id}/edit', [App\Http\Controllers\AppUserController::class, 'edit'])->name('app-users.edit');
    Route::put('/app-users/{id}', [App\Http\Controllers\AppUserController::class, 'update'])->name('app-users.update');
    Route::delete('/app-users/{id}', [App\Http\Controllers\AppUserController::class, 'destroy'])->name('app-users.destroy');
    Route::patch('/app-users/{id}/toggle-active', [App\Http\Controllers\AppUserController::class, 'toggleActive'])->name('app-users.toggle-active');
});
