<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Enums\StatusAntrian;
use App\Models\MedicalRecord;
use App\Models\Antrian;
use App\Models\Pasien;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;

class MedicalRecordApiController extends Controller
{
    /**
     * Display a listing of the medical records.
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function index(Request $request)
    {
        try {
            $query = MedicalRecord::with(['pasien', 'pendaftaran.pasien', 'pendaftaran.antrian']);

            if ($search = $request->query('search')) {
                $query->whereHas('pasien', function ($q) use ($search) {
                    $q->where('nik', 'like', "%{$search}%")
                      ->orWhere('nama', 'like', "%{$search}%");
                });
            }

            $medicalRecords = $query->latest()->paginate(10);

            Log::info('Medical records retrieved successfully', [
                'total_records' => $medicalRecords->total(),
                'search_query' => $search ?? 'none',
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Medical records retrieved successfully.',
                'data' => $medicalRecords,
            ], 200);
        } catch (\Exception $e) {
            Log::error('Failed to retrieve medical records: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Failed to retrieve medical records.',
                'error' => $e->getMessage(),
            ], 500);
        }
    }

    /**
     * Store a newly created medical record in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'pendaftaran_id' => 'required|exists:pendaftarans,id',
            'pasien_id' => 'required|exists:pasiens,id',
            'tanggal_kunjungan' => 'required|date|date_format:Y-m-d',
            'keluhan' => 'required|string',
            'diagnosis' => 'required|string',
            'pengobatan' => 'required|string',
            'hasil_pemeriksaan' => 'nullable|string',
            'tinggi_badan' => 'nullable|numeric|min:0',
            'berat_badan' => 'nullable|numeric|min:0',
            'tekanan_darah' => 'nullable|string|max:20',
            'suhu_badan' => 'nullable|numeric|min:0',
        ], [
            'pendaftaran_id.required' => 'Pendaftaran wajib diisi.',
            'pasien_id.required' => 'Pasien wajib diisi.',
            'tanggal_kunjungan.required' => 'Tanggal kunjungan wajib diisi.',
            'tanggal_kunjungan.date_format' => 'Format tanggal kunjungan harus YYYY-MM-DD.',
            'keluhan.required' => 'Keluhan wajib diisi.',
            'diagnosis.required' => 'Diagnosis wajib diisi.',
            'pengobatan.required' => 'Pengobatan wajib diisi.',
            'tekanan_darah.max' => 'Tekanan darah tidak boleh lebih dari 20 karakter.',
            'tinggi_badan.numeric' => 'Tinggi badan harus berupa angka.',
            'berat_badan.numeric' => 'Berat badan harus berupa angka.',
            'suhu_badan.numeric' => 'Suhu badan harus berupa angka.',
        ]);

        if ($validator->fails()) {
            Log::warning('Validation failed during medical record creation', [
                'input' => $request->except(['password']),
                'errors' => $validator->errors()->toArray(),
            ]);
            return response()->json([
                'success' => false,
                'message' => 'Validasi gagal',
                'errors' => $validator->errors()->toArray(),
            ], 422);
        }

        try {
            $medicalRecord = DB::transaction(function () use ($request) {
                $medicalRecord = MedicalRecord::create([
                    'pendaftaran_id' => $request->pendaftaran_id,
                    'pasien_id' => $request->pasien_id,
                    'tanggal_kunjungan' => $request->tanggal_kunjungan,
                    'keluhan' => $request->keluhan,
                    'diagnosis' => $request->diagnosis,
                    'pengobatan' => $request->pengobatan,
                    'hasil_pemeriksaan' => $request->hasil_pemeriksaan,
                    'tinggi_badan' => $request->tinggi_badan,
                    'berat_badan' => $request->berat_badan,
                    'tekanan_darah' => $request->tekanan_darah,
                    'suhu_badan' => $request->suhu_badan,
                ]);

                $antrian = Antrian::where('pendaftaran_id', $request->pendaftaran_id)->first();
                if ($antrian) {
                    $antrian->update(['status' => StatusAntrian::SELESAI]);
                } else {
                    Log::warning('Antrian tidak ditemukan untuk pendaftaran ID ' . $request->pendaftaran_id);
                }

                return $medicalRecord->load(['pasien', 'pendaftaran']);
            });

            Log::info('Medical record created successfully', [
                'medical_record_id' => $medicalRecord->id,
                'pasien_id' => $medicalRecord->pasien_id,
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Rekam medis berhasil disimpan.',
                'data' => $medicalRecord,
            ], 201);
        } catch (\Exception $e) {
            Log::error('Medical record creation failed: ' . $e->getMessage(), [
                'input' => $request->except(['password']),
                'trace' => $e->getTraceAsString(),
            ]);
            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan saat menyimpan rekam medis.',
                'error' => $e->getMessage(),
            ], 500);
        }
    }

    /**
     * Display all medical records for the patient of the specified medical record.
     *
     * @param int $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function show($id)
    {
        try {
            $medicalRecord = MedicalRecord::with(['pasien', 'pendaftaran.pasien', 'pendaftaran.antrian', 'pendaftaran.cluster'])
                ->findOrFail($id);

            $medicalRecords = MedicalRecord::with(['pasien', 'pendaftaran.pasien', 'pendaftaran.antrian', 'pendaftaran.cluster'])
                ->where('pasien_id', $medicalRecord->pasien_id)
                ->orderBy('tanggal_kunjungan', 'asc')
                ->get();

            Log::info('Medical records for patient retrieved successfully', [
                'medical_record_id' => $id,
                'pasien_id' => $medicalRecord->pasien_id,
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Medical records retrieved successfully.',
                'data' => [
                    'pasien' => $medicalRecord->pasien,
                    'medical_records' => $medicalRecords,
                ],
            ], 200);
        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
            Log::warning('Medical record not found', ['medical_record_id' => $id]);
            return response()->json([
                'success' => false,
                'message' => 'Rekam medis tidak ditemukan.',
            ], 404);
        } catch (\Exception $e) {
            Log::error('Failed to retrieve medical record: ' . $e->getMessage(), ['medical_record_id' => $id]);
            return response()->json([
                'success' => false,
                'message' => 'Gagal memuat rekam medis.',
                'error' => $e->getMessage(),
            ], 500);
        }
    }

    /**
     * Update the specified medical record in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @param int $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function update(Request $request, $id)
    {
        try {
            $medicalRecord = MedicalRecord::findOrFail($id);

            $validator = Validator::make($request->all(), [
                'tanggal_kunjungan' => 'required|date|date_format:Y-m-d',
                'keluhan' => 'required|string',
                'diagnosis' => 'required|string',
                'pengobatan' => 'required|string',
                'hasil_pemeriksaan' => 'nullable|string',
                'tinggi_badan' => 'nullable|numeric|min:0',
                'berat_badan' => 'nullable|numeric|min:0',
                'tekanan_darah' => 'nullable|string|max:20',
                'suhu_badan' => 'nullable|numeric|min:0',
            ], [
                'tanggal_kunjungan.required' => 'Tanggal kunjungan wajib diisi.',
                'tanggal_kunjungan.date_format' => 'Format tanggal kunjungan harus YYYY-MM-DD.',
                'keluhan.required' => 'Keluhan wajib diisi.',
                'diagnosis.required' => 'Diagnosis wajib diisi.',
                'pengobatan.required' => 'Pengobatan wajib diisi.',
                'tekanan_darah.max' => 'Tekanan darah tidak boleh lebih dari 20 karakter.',
                'tinggi_badan.numeric' => 'Tinggi badan harus berupa angka.',
                'berat_badan.numeric' => 'Berat badan harus berupa angka.',
                'suhu_badan.numeric' => 'Suhu badan harus berupa angka.',
            ]);

            if ($validator->fails()) {
                Log::warning('Validation failed during medical record update', [
                    'medical_record_id' => $id,
                    'errors' => $validator->errors()->toArray(),
                ]);
                return response()->json([
                    'success' => false,
                    'message' => 'Validasi gagal',
                    'errors' => $validator->errors()->toArray(),
                ], 422);
            }

            $medicalRecord->update([
                'tanggal_kunjungan' => $request->tanggal_kunjungan,
                'keluhan' => $request->keluhan,
                'diagnosis' => $request->diagnosis,
                'pengobatan' => $request->pengobatan,
                'hasil_pemeriksaan' => $request->hasil_pemeriksaan,
                'tinggi_badan' => $request->tinggi_badan,
                'berat_badan' => $request->berat_badan,
                'tekanan_darah' => $request->tekanan_darah,
                'suhu_badan' => $request->suhu_badan,
            ]);

            Log::info('Medical record updated successfully', [
                'medical_record_id' => $medicalRecord->id,
                'pasien_id' => $medicalRecord->pasien_id,
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Rekam medis berhasil diperbarui.',
                'data' => $medicalRecord->load(['pasien', 'pendaftaran']),
            ], 200);
        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
            Log::warning('Medical record not found for update', ['medical_record_id' => $id]);
            return response()->json([
                'success' => false,
                'message' => 'Rekam medis tidak ditemukan.',
            ], 404);
        } catch (\Exception $e) {
            Log::error('Medical record update failed: ' . $e->getMessage(), ['medical_record_id' => $id]);
            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan saat memperbarui rekam medis.',
                'error' => $e->getMessage(),
            ], 500);
        }
    }

    /**
     * Remove the specified medical record from storage.
     *
     * @param int $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function destroy($id)
    {
        try {
            $medicalRecord = MedicalRecord::findOrFail($id);
            $medicalRecord->delete();

            Log::info('Medical record deleted successfully', ['medical_record_id' => $id]);

            return response()->json([
                'success' => true,
                'message' => 'Rekam medis berhasil dihapus.',
            ], 200);
        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
            Log::warning('Medical record not found for deletion', ['medical_record_id' => $id]);
            return response()->json([
                'success' => false,
                'message' => 'Rekam medis tidak ditemukan.',
            ], 404);
        } catch (\Exception $e) {
            Log::error('Medical record deletion failed: ' . $e->getMessage(), ['medical_record_id' => $id]);
            return response()->json([
                'success' => false,
                'message' => 'Gagal menghapus rekam medis.',
                'error' => $e->getMessage(),
            ], 500);
        }
    }

    /**
     * Generate and return a PDF for all medical records of the patient.
     *
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function pdf($id)
    {
        try {
            $medicalRecord = MedicalRecord::findOrFail($id);
            $medicalRecords = MedicalRecord::with(['pasien', 'pendaftaran.antrian', 'pendaftaran.cluster'])
                ->where('pasien_id', $medicalRecord->pasien_id)
                ->orderBy('tanggal_kunjungan', 'asc')
                ->get();
            $pasien = $medicalRecord->pasien;

            $pdf = Pdf::loadView('dashboard.medical_record.pdf', compact('medicalRecords', 'pasien'));
            $pdfContent = $pdf->output();

            Log::info('PDF generated successfully for medical records', [
                'medical_record_id' => $id,
                'pasien_id' => $medicalRecord->pasien_id,
            ]);

            return response($pdfContent, 200, [
                'Content-Type' => 'application/pdf',
                'Content-Disposition' => 'attachment; filename="rekam-medis-' . $medicalRecord->pasien->no_rm . '-' . now()->format('Ymd') . '.pdf"',
            ]);
        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
            Log::warning('Medical record not found for PDF generation', ['medical_record_id' => $id]);
            return response()->json([
                'success' => false,
                'message' => 'Rekam medis tidak ditemukan.',
            ], 404);
        } catch (\Exception $e) {
            Log::error('PDF generation failed: ' . $e->getMessage(), ['medical_record_id' => $id]);
            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan saat menghasilkan PDF.',
                'error' => $e->getMessage(),
            ], 500);
        }
    }

    /**
     * Get the current antrian that is being served for creating a new medical record.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function getCurrentAntrian()
    {
        try {
            $antrian = Antrian::with(['pendaftaran.pasien'])
                ->where('status', StatusAntrian::SEDANG_DILAYANI)
                ->first();

            if (!$antrian) {
                return response()->json([
                    'success' => false,
                    'message' => 'Tidak ada antrian yang sedang dilayani saat ini.',
                ], 404);
            }

            Log::info('Current antrian retrieved successfully', [
                'antrian_id' => $antrian->id,
                'pendaftaran_id' => $antrian->pendaftaran_id,
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Antrian retrieved successfully.',
                'data' => $antrian,
            ], 200);
        } catch (\Exception $e) {
            Log::error('Failed to retrieve current antrian: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Gagal memuat antrian.',
                'error' => $e->getMessage(),
            ], 500);
        }
    }
}