<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Feedback;
use App\Models\MedicalRecord;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;

class FeedbackApiController extends Controller
{
    /**
     * Display a listing of the feedback.
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function index(Request $request)
    {
        try {
            $query = Feedback::with(['pasien', 'medicalRecord']);

            // Filter berdasarkan pasien
            if ($pasienId = $request->query('pasien_id')) {
                $query->where('pasien_id', $pasienId);
            }

            // Filter berdasarkan rekam medis
            if ($medicalRecordId = $request->query('id_medical_record')) {
                $query->where('id_medical_record', $medicalRecordId);
            }

            $feedbacks = $query->latest()->paginate(10);

            return response()->json([
                'success' => true,
                'message' => 'Feedback retrieved successfully.',
                'data' => $feedbacks,
            ], 200);
        } catch (\Exception $e) {
            Log::error('Failed to retrieve feedback: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Failed to retrieve feedback.',
                'error' => $e->getMessage(),
            ], 500);
        }
    }

    /**
     * Store a newly created feedback in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'rating' => 'required|integer|min:1|max:5',
            'comment' => 'nullable|string',
            'id_medical_record' => 'nullable|exists:medical_records,id',
            'pasien_id' => 'required|exists:pasiens,id',
        ], [
            'rating.required' => 'Rating wajib diisi.',
            'rating.integer' => 'Rating harus berupa angka.',
            'rating.min' => 'Rating minimal 1.',
            'rating.max' => 'Rating maksimal 5.',
            'pasien_id.required' => 'Pasien wajib diisi.',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validasi gagal',
                'errors' => $validator->errors()->toArray(),
            ], 422);
        }

        try {
            $feedback = Feedback::create($request->all());

            return response()->json([
                'success' => true,
                'message' => 'Feedback berhasil disimpan.',
                'data' => $feedback->load(['pasien', 'medicalRecord']),
            ], 201);
        } catch (\Exception $e) {
            Log::error('Feedback creation failed: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan saat menyimpan feedback.',
                'error' => $e->getMessage(),
            ], 500);
        }
    }

    /**
     * Display the specified feedback.
     *
     * @param int $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function show($id)
    {
        try {
            $feedback = Feedback::with(['pasien', 'medicalRecord'])->findOrFail($id);

            return response()->json([
                'success' => true,
                'message' => 'Feedback retrieved successfully.',
                'data' => $feedback,
            ], 200);
        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
            return response()->json([
                'success' => false,
                'message' => 'Feedback tidak ditemukan.',
            ], 404);
        } catch (\Exception $e) {
            Log::error('Failed to retrieve feedback: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Gagal memuat feedback.',
                'error' => $e->getMessage(),
            ], 500);
        }
    }

    /**
     * Update the specified feedback in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @param int $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function update(Request $request, $id)
    {
        try {
            $feedback = Feedback::findOrFail($id);

            $validator = Validator::make($request->all(), [
                'rating' => 'required|integer|min:1|max:5',
                'comment' => 'nullable|string',
                'id_medical_record' => 'nullable|exists:medical_records,id',
                'pasien_id' => 'required|exists:pasiens,id',
            ]);

            if ($validator->fails()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Validasi gagal',
                    'errors' => $validator->errors()->toArray(),
                ], 422);
            }

            $feedback->update($request->all());

            return response()->json([
                'success' => true,
                'message' => 'Feedback berhasil diperbarui.',
                'data' => $feedback->load(['pasien', 'medicalRecord']),
            ], 200);
        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
            return response()->json([
                'success' => false,
                'message' => 'Feedback tidak ditemukan.',
            ], 404);
        } catch (\Exception $e) {
            Log::error('Feedback update failed: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan saat memperbarui feedback.',
                'error' => $e->getMessage(),
            ], 500);
        }
    }

    /**
     * Remove the specified feedback from storage.
     *
     * @param int $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function destroy($id)
    {
        try {
            $feedback = Feedback::findOrFail($id);
            $feedback->delete();

            return response()->json([
                'success' => true,
                'message' => 'Feedback berhasil dihapus.',
            ], 200);
        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
            return response()->json([
                'success' => false,
                'message' => 'Feedback tidak ditemukan.',
            ], 404);
        } catch (\Exception $e) {
            Log::error('Feedback deletion failed: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Gagal menghapus feedback.',
                'error' => $e->getMessage(),
            ], 500);
        }
    }
}
