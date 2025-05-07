<?php

namespace App\Http\Controllers;

use App\Models\Antrian;
use App\Models\Pasien; // Changed from Pasiens to Pasien
use App\Models\Doctor;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Carbon\Carbon;

class AntrianController extends Controller
{
    public function index()
    {
        $antrians = Antrian::with(['pasiens', 'doctors'])
            ->orderBy('created_at', 'desc')
            ->paginate(10);
        
        return view('dashboard.antrian.index', compact('antrians'));
    }

    public function create()
    {
        $pasiens = Pasien::all();   
        $doctors = Doctor::all(); 
        return view('dashboard.antrian.create', compact('pasiens', 'doctors'));
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'pasiens_id' => 'required|exists:pasiens,id',
            'doctors_id' => 'required|exists:doctors,id',
            'pembayaran' => 'required|in:bpjs,umum',
            'cluster' => 'required|in:cluster_1,cluster_2,cluster_3,cluster_4,cluster_5',
            'complaint' => 'nullable|string'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => 'error',
                'errors' => $validator->errors()
            ], 422);
        }

        // Generate nomor antrian
        $today = Carbon::now()->toDateString();
        $lastAntrian = Antrian::whereDate('tanggal_daftar', $today)->latest()->first();
        $noAntrian = $lastAntrian ? 
            sprintf('%03d', intval(substr($lastAntrian->no_antrian, -3)) + 1) : 
            '001';
        
        $antrian = Antrian::create([
            'no_antrian' => date('Ymd') . $noAntrian,
            'pasiens_id' => $request->pasiens_id,
            'doctors_id' => $request->doctors_id,
            'tanggal_daftar' => $today,
            'pembayaran' => $request->pembayaran,
            'cluster' => $request->cluster,
            'complaint' => $request->complaint,
            'status' => 'menunggu'
        ]);

        return response()->json([
            'status' => 'success',
            'message' => 'Antrian berhasil dibuat',
            'data' => $antrian
        ], 201);
    }

    public function show($id)
    {
        $antrian = Antrian::with(['pasiens', 'doctors'])->find($id);
        
        if (!$antrian) {
            return response()->json([
                'status' => 'error',
                'message' => 'Antrian tidak ditemukan'
            ], 404);
        }

        return response()->json([
            'status' => 'success',
            'data' => $antrian
        ]);
    }

    public function edit($id)
    {
        $antrian = Antrian::findOrFail($id);
        $pasiens = Pasien::all(); // Changed from Pasiens to Pasien
        $doctors = Doctor::all();
        return view('dashboard.antrian.edit', compact('antrian', 'pasiens', 'doctors'));
    }

    public function update(Request $request, $id)
    {
        $antrian = Antrian::find($id);
        
        if (!$antrian) {
            return response()->json([
                'status' => 'error',
                'message' => 'Antrian tidak ditemukan'
            ], 404);
        }

        $validator = Validator::make($request->all(), [
            'status' => 'required|in:menunggu,dipanggil,selesai',
            'complaint' => 'nullable|string'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => 'error',
                'errors' => $validator->errors()
            ], 422);
        }

        $antrian->update($request->only(['status', 'complaint']));

        return response()->json([
            'status' => 'success',
            'message' => 'Antrian berhasil diperbarui',
            'data' => $antrian
        ]);
    }

    public function destroy($id)
    {
        $antrian = Antrian::find($id);
        
        if (!$antrian) {
            return response()->json([
                'status' => 'error',
                'message' => 'Antrian tidak ditemukan'
            ], 404);
        }

        $antrian->delete();

        return response()->json([
            'status' => 'success',
            'message' => 'Antrian berhasil dihapus'
        ]);
    }

    public function getAntrianByDate(Request $request)
    {
        $date = $request->input('date', Carbon::now()->toDateString());
        
        $antrians = Antrian::with(['pasiens', 'doctors'])
            ->whereDate('tanggal_daftar', $date)
            ->orderBy('created_at', 'asc')
            ->paginate(10);

        if($request->ajax()) {
            return response()->json([
                'status' => 'success',
                'data' => $antrians
            ]);
        }

        return view('dashboard.antrian.index', compact('antrians'));
    }

    public function updateStatus($id, Request $request)
    {
        $antrian = Antrian::find($id);
        
        if (!$antrian) {
            return response()->json([
                'status' => 'error',
                'message' => 'Antrian tidak ditemukan'
            ], 404);
        }

        $validator = Validator::make($request->all(), [
            'status' => 'required|in:menunggu,dipanggil,selesai'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => 'error',
                'errors' => $validator->errors()
            ], 422);
        }

        $antrian->status = $request->status;
        $antrian->save();

        return response()->json([
            'status' => 'success',
            'message' => 'Status antrian berhasil diperbarui',
            'data' => $antrian
        ]);
    }
}
