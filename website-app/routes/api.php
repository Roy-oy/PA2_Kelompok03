<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\PasienApiController;
use App\Http\Controllers\Api\AuthApiController;
use App\Http\Controllers\Api\BeritaApiController;
use App\Http\Controllers\Api\FaqApiController;
use App\Http\Controllers\Api\FeedbackApiController;
use App\Http\Controllers\Api\JadwalDokterApiController;
use App\Http\Controllers\Api\MedicalRecordApiController;
use App\Http\Controllers\Api\PengumumanApiController;
use App\Http\Controllers\Api\PendaftaranApiController;
use App\Models\Faq;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

// Auth routes for mobile app
Route::post('/register', [AuthApiController::class, 'register']);
Route::post('/login', [AuthApiController::class, 'login']);

// Protected routes that require authentication
Route::middleware('auth:sanctum')->group(function () {
    // User profile
    Route::get('/profile', [AuthApiController::class, 'getProfile']);

    // Register as patient (convert app user to patient)
    Route::post('/register-as-patient', [AuthApiController::class, 'registerAsPatient']);

        // Pendaftaran routes
    Route::get('/pendaftarans', [PendaftaranApiController::class, 'index']);
    Route::get('/clusters', [PendaftaranApiController::class, 'getClusters']);
    Route::post('/pendaftarans', [PendaftaranApiController::class, 'store']);
    Route::get('/pendaftarans/{id}', [PendaftaranApiController::class, 'show']);
    Route::get('/pendaftarans/by-nik/{nik}', [PendaftaranApiController::class, 'showByNik']);
    Route::put('/pendaftarans/{pendaftaran}', [PendaftaranApiController::class, 'update']);
    Route::delete('/pendaftarans/{id}', [PendaftaranApiController::class, 'destroy']);

    // Pasien routes
    Route::get('/pasien', [PasienApiController::class, 'index']);
    Route::get('/pasien/{pasien}', [PasienApiController::class, 'show']);
    Route::put('/pasien/{pasien}', [PasienApiController::class, 'update']);

    Route::prefix('medical-records')->group(function () {
    Route::get('/', [MedicalRecordApiController::class, 'index']);
    Route::post('/', [MedicalRecordApiController::class, 'store']);
    Route::get('/{id}', [MedicalRecordApiController::class, 'show']);
    Route::put('/{id}', [MedicalRecordApiController::class, 'update']);
    Route::delete('/{id}', [MedicalRecordApiController::class, 'destroy']);
    Route::get('/{id}/pdf', [MedicalRecordApiController::class, 'pdf']);
    Route::get('/current-antrian', [MedicalRecordApiController::class, 'getCurrentAntrian']);
    
});

Route::prefix('feedback')->group(function () {
        Route::get('/', [FeedbackApiController::class, 'index']);
        Route::post('/', [FeedbackApiController::class, 'store']);
        Route::get('/{id}', [FeedbackApiController::class, 'show']);
    });

});

// Doctor schedule routes
Route::get('/jadwal-dokter', [JadwalDokterApiController::class, 'index']);
Route::get('/jadwal-dokter/{schedule}', [JadwalDokterApiController::class, 'show']);

// Pengumuman routes
Route::get('/pengumuman', [PengumumanApiController::class, 'index']);
Route::get('/pengumuman/{pengumuman}', [PengumumanApiController::class, 'show']);

// FAQ routes
Route::get('/faq', [FaqApiController::class, 'index']);
Route::get('/faq/{faq}', [FaqApiController::class, 'show']);

// Berita routes (tanpa autentikasi)
Route::get('/berita', [BeritaApiController::class, 'index']);
Route::get('/berita/{id}', [BeritaApiController::class, 'show']);
Route::get('/berita/kategori/{kategoriId}', [BeritaApiController::class, 'getByKategori']);
Route::get('/berita/search', [BeritaApiController::class, 'search']);
Route::get('/kategori-berita', [BeritaApiController::class, 'getKategori']);