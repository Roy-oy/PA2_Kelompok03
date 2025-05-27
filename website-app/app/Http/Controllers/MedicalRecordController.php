<?php

namespace App\Http\Controllers;

use App\Enums\StatusAntrian;
use App\Models\MedicalRecord;
use App\Models\Antrian;
use App\Models\Pendaftaran;
use App\Models\Pasien;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class MedicalRecordController extends Controller
{
    /**
     * Display a listing of the medical records.
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\View\View
     */
    public function index(Request $request)
    {
        $query = MedicalRecord::with(['pasien', 'pendaftaran.pasien', 'pendaftaran.antrian']);

        if ($search = $request->query('search')) {
            $query->whereHas('pasien', function ($q) use ($search) {
                $q->where('nik', 'like', "%{$search}%")
                  ->orWhere('nama', 'like', "%{$search}%");
            });
        }

        $medicalRecords = $query->latest()->paginate(10);

        return view('dashboard.medical_record.index', compact('medicalRecords'));
    }

    /**
     * Show the form for creating a new medical record.
     *
     * @return \Illuminate\View\View
     */
    public function create()
    {
        $antrian = Antrian::with(['pendaftaran.pasien'])
            ->where('status', StatusAntrian::SEDANG_DILAYANI)
            ->first();

        return view('dashboard.medical_record.create', compact('antrian'));
    }

    /**
     * Store a newly created medical record in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'pendaftaran_id' => 'required|exists:pendaftarans,id',
            'pasien_id' => 'required|exists:pasiens,id',
            'tanggal_kunjungan' => 'required|date',
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
            'keluhan.required' => 'Keluhan wajib diisi.',
            'diagnosis.required' => 'Diagnosis wajib diisi.',
            'pengobatan.required' => 'Pengobatan wajib diisi.',
            'tekanan_darah.max' => 'Tekanan darah tidak boleh lebih dari 20 karakter.',
            'tinggi_badan.numeric' => 'Tinggi badan harus berupa angka.',
            'berat_badan.numeric' => 'Berat badan harus berupa angka.',
            'suhu_badan.numeric' => 'Suhu badan harus berupa angka.',
        ]);

        try {
            DB::transaction(function () use ($request, $validated) {
                MedicalRecord::create($validated);

                $antrian = Antrian::where('pendaftaran_id', $request->pendaftaran_id)->first();
                if ($antrian) {
                    $antrian->update(['status' => StatusAntrian::SELESAI]);
                } else {
                    Log::warning('Antrian tidak ditemukan untuk pendaftaran ID ' . $request->pendaftaran_id);
                }
            });

            return redirect()->route('medical_record.index')
                ->with('success', 'Rekam medis berhasil disimpan.');
        } catch (\Exception $e) {
            Log::error('Medical record creation failed: ' . $e->getMessage());
            return redirect()->back()
                ->withErrors(['error' => 'Terjadi kesalahan saat menyimpan rekam medis. Silakan coba lagi.'])
                ->withInput();
        }
    }

    /**
     * Display all medical records for the patient of the specified medical record.
     *
     * @param \App\Models\MedicalRecord $medicalRecord
     * @return \Illuminate\View\View
     */
    public function show(MedicalRecord $medicalRecord)
    {
        $medicalRecords = MedicalRecord::with(['pasien', 'pendaftaran.pasien', 'pendaftaran.antrian', 'pendaftaran.cluster'])
            ->where('pasien_id', $medicalRecord->pasien_id)
            ->orderBy('tanggal_kunjungan', 'asc')
            ->get();

        $pasien = $medicalRecord->pasien;

        return view('dashboard.medical_record.show', compact('medicalRecords', 'pasien'));
    }

    /**
     * Show the form for editing the specified medical record.
     *
     * @param \App\Models\MedicalRecord $medicalRecord
     * @return \Illuminate\View\View
     */
    public function edit(MedicalRecord $medicalRecord)
    {
        $medicalRecord->load(['pasien', 'pendaftaran']);
        return view('dashboard.medical_record.edit', compact('medicalRecord'));
    }

    /**
     * Update the specified medical record in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @param \App\Models\MedicalRecord $medicalRecord
     * @return \Illuminate\Http\RedirectResponse
     */
    public function update(Request $request, MedicalRecord $medicalRecord)
    {
        $validated = $request->validate([
            'tanggal_kunjungan' => 'required|date',
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
            'keluhan.required' => 'Keluhan wajib diisi.',
            'diagnosis.required' => 'Diagnosis wajib diisi.',
            'pengobatan.required' => 'Pengobatan wajib diisi.',
            'tekanan_darah.max' => 'Tekanan darah tidak boleh lebih dari 20 karakter.',
            'tinggi_badan.numeric' => 'Tinggi badan harus berupa angka.',
            'berat_badan.numeric' => 'Berat badan harus berupa angka.',
            'suhu_badan.numeric' => 'Suhu badan harus berupa angka.',
        ]);

        try {
            $medicalRecord->update($validated);

            return redirect()->route('medical_record.index')
                ->with('success', 'Rekam medis berhasil diperbarui.');
        } catch (\Exception $e) {
            Log::error('Medical record update failed: ' . $e->getMessage());
            return redirect()->back()
                ->withErrors(['error' => 'Terjadi kesalahan saat memperbarui rekam medis. Silakan coba lagi.'])
                ->withInput();
        }
    }

    /**
     * Remove the specified medical record from storage.
     *
     * @param \App\Models\MedicalRecord $medicalRecord
     * @return \Illuminate\Http\RedirectResponse
     */
    public function destroy(MedicalRecord $medicalRecord)
    {
        try {
            $medicalRecord->delete();
            return redirect()->route('medical_record.index')
                ->with('success', 'Rekam medis berhasil dihapus.');
        } catch (\Exception $e) {
            Log::error('Medical record deletion failed: ' . $e->getMessage());
            return redirect()->back()
                ->withErrors(['error' => 'Gagal menghapus rekam medis.']);
        }
    }

    /**
     * Generate and download a PDF for all medical records of the patient.
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
            return $pdf->download('rekam-medis-' . $medicalRecord->pasien->no_rm . '-' . now()->format('Ymd') . '.pdf');
        } catch (\Exception $e) {
            Log::error('PDF generation failed: ' . $e->getMessage());
            return redirect()->back()
                ->withErrors(['error' => 'Terjadi kesalahan saat menghasilkan PDF. Silakan coba lagi.']);
        }
    }
}