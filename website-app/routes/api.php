<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\PasienApiController;
use App\Http\Controllers\Api\AuthApiController;
use App\Http\Controllers\Api\FaqApiController;
use App\Http\Controllers\Api\JadwalDokterApiController;
use App\Http\Controllers\Api\MedicalRecordApiController;
use App\Http\Controllers\Api\PengumumanApiController;
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
Route::post('/login-with-nik', [AuthApiController::class, 'loginWithNik']);

// Protected routes that require authentication
Route::middleware('auth:sanctum')->group(function () {
    // User profile
    Route::get('/profile', [AuthApiController::class, 'getProfile']);

    // Register as patient (convert app user to patient)
    Route::post('/register-as-patient', [AuthApiController::class, 'registerAsPatient']);

    // Pasien routes
    Route::get('/pasien', [PasienApiController::class, 'index']);
    Route::get('/pasien/{pasien}', [PasienApiController::class, 'show']);
    Route::put('/pasien/{pasien}', [PasienApiController::class, 'update']);


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
Route::get('/faqs', [FaqController::class, 'getAllFaqs']);


// Rute rekam medis - memerlukan autentikasi
Route::middleware('auth:sanctum')->group(function () {
    Route::get('/pasien/medical-records', [MedicalRecordApiController::class, 'index']);
    Route::get('/pasien/medical-records/{id}', [MedicalRecordApiController::class, 'show']);
});
//faq


