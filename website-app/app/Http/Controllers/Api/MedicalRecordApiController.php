<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\MedicalRecord;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class MedicalRecordApiController extends Controller
{
    /**
     * Display a listing of the medical records for the authenticated user.
     */
    public function index()
    {
        // Ambil user yang sedang login
        $user = Auth::user();

        // Periksa apakah user memiliki data pasien
        if (!$user->pasien) {
            return response()->json([
                'success' => false,
                'message' => 'Data pasien tidak ditemukan',
                'data' => []
            ], 404);
        }

        // Ambil rekam medis untuk pasien yang login
        $medicalRecords = MedicalRecord::where('pasien_id', $user->pasien->id)
            ->with(['dokter:id,nama,spesialisasi', 'cluster:id,nama'])
            ->orderBy('tanggal_periksa', 'desc')
            ->get()
            ->map(function ($record) {
                return [
                    'id' => $record->id,
                    'tanggal' => $record->tanggal_periksa->format('l, d F Y'),
                    'waktu' => $record->jam_periksa->format('H.i') . ' WIB',
                    'diagnosis' => $record->diagnosis,
                    'terapi' => $record->terapi,
                    'dokter' => $record->dokter->nama,
                    'cluster' => $record->cluster->nama,
                ];
            });

        return response()->json([
            'success' => true,
            'message' => 'Daftar Rekam Medis',
            'data' => $medicalRecords
        ]);
    }

    /**
     * Display the specified medical record.
     */
    public function show($id)
    {
        // Ambil user yang sedang login
        $user = Auth::user();

        // Periksa apakah user memiliki data pasien
        if (!$user->pasien) {
            return response()->json([
                'success' => false,
                'message' => 'Data pasien tidak ditemukan',
                'data' => null
            ], 404);
        }

        // Ambil rekam medis berdasarkan ID dan pasien yang login
        $medicalRecord = MedicalRecord::where('id', $id)
            ->where('pasien_id', $user->pasien->id)
            ->with(['dokter:id,nama,spesialisasi', 'cluster:id,nama'])
            ->first();

        if (!$medicalRecord) {
            return response()->json([
                'success' => false,
                'message' => 'Rekam medis tidak ditemukan',
                'data' => null
            ], 404);
        }

        // Format data sesuai kebutuhan di frontend
        $recordDetail = [
            'id' => $medicalRecord->id,
            'tanggal' => $medicalRecord->tanggal_periksa->format('l, d F Y'),
            'waktu' => $medicalRecord->jam_periksa->format('H.i') . ' WIB',
            'diagnosis' => $medicalRecord->diagnosis,
            'terapi' => $medicalRecord->terapi,
            'dokter' => $medicalRecord->dokter->nama,
            'cluster' => $medicalRecord->cluster->nama,
            'keluhan' => $medicalRecord->keluhan,
            'lab_result' => $medicalRecord->hasil_lab,
            'advice' => $medicalRecord->saran_dokter,
            'tekanan_darah' => $medicalRecord->tekanan_darah,
            'berat_badan' => $medicalRecord->berat_badan,
            'tinggi_badan' => $medicalRecord->tinggi_badan,
        ];

        return response()->json([
            'success' => true,
            'message' => 'Detail Rekam Medis',
            'data' => $recordDetail
        ]);
    }
}
