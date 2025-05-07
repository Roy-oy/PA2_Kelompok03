<?php

namespace App\Http\Controllers;

use App\Models\Pasien;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class PasienController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $pasiens = Pasien::latest()->paginate(10);
        return view('dashboard.pasien.index', compact('pasiens'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('dashboard.pasien.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            // Hapus validasi no_rm karena akan digenerate otomatis
            'nama' => 'required|string|max:255',
            'jenis_kelamin' => 'required|in:Laki-laki,Perempuan',
            'tanggal_lahir' => 'required|date',
            'tempat_lahir' => 'required|string|max:255',
            'alamat' => 'required|string',
            'no_telepon' => 'nullable|string|max:15',
            'pekerjaan' => 'nullable|string|max:255',
            'no_bpjs' => 'nullable|string|max:20',
            'golongan_darah' => 'nullable|in:A,B,AB,O,Tidak Diketahui',
            'riwayat_alergi' => 'nullable|string',
            'riwayat_penyakit' => 'nullable|string',
            'keluhan_sakit' => 'required|string', // Add this line
        ]);

        if ($validator->fails()) {
            return redirect()->route('pasien.create')
                ->withErrors($validator)
                ->withInput();
        }

        Pasien::create($request->all());

        return redirect()->route('pasien.index')
            ->with('success', 'Data pasien berhasil ditambahkan.');
    }

    /**
     * Display the specified resource.
     */
    public function show(Pasien $pasien)
    {
        return view('dashboard.pasien.show', compact('pasien'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Pasien $pasien)
    {
        return view('dashboard.pasien.edit', compact('pasien'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Pasien $pasien)
    {
        $validator = Validator::make($request->all(), [
            // Hapus validasi no_rm karena tidak bisa diupdate
            'nama' => 'required|string|max:255',
            'jenis_kelamin' => 'required|in:Laki-laki,Perempuan',
            'tanggal_lahir' => 'required|date',
            'tempat_lahir' => 'required|string|max:255',
            'alamat' => 'required|string',
            'no_telepon' => 'nullable|string|max:15',
            'pekerjaan' => 'nullable|string|max:255',
            'no_bpjs' => 'nullable|string|max:20',
            'golongan_darah' => 'nullable|in:A,B,AB,O,Tidak Diketahui',
            'riwayat_alergi' => 'nullable|string',
            'riwayat_penyakit' => 'nullable|string',
            'keluhan_sakit' => 'required|string', 
        ]);

        if ($validator->fails()) {
            return redirect()->route('pasien.edit', $pasien->id)
                ->withErrors($validator)
                ->withInput();
        }

        // Remove no_rm from request to prevent updates
        $input = $request->except('no_rm');
        $pasien->update($input);

        return redirect()->route('pasien.index')
            ->with('success', 'Data pasien berhasil diperbarui.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Pasien $pasien)
    {
        $pasien->delete();

        return redirect()->route('pasien.index')
            ->with('success', 'Data pasien berhasil dihapus.');
    }
}