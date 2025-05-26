<?php

namespace App\Http\Controllers;

use App\Models\Doctor;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Storage;

class DoctorController extends Controller
{
    
    public function index()
    {
        $doctors = Doctor::latest()->paginate(10);
        return view('dashboard.dokter.index', compact('doctors'));
    }

    /**
     * Menampilkan form untuk menambahkan dokter baru.
     */
    public function create()
    {
        return view('dashboard.dokter.create');
    }

    /**
     * Menyimpan dokter baru ke database.
     */
    public function store(Request $request)
    {
        // Validasi input
        $validator = Validator::make($request->all(), [
            'nama' => 'required|string|max:255',
            'spesialisasi' => 'required|in:umum,gigi',
            'email' => 'required|email|unique:doctors,email',
            'no_hp' => 'required|string|max:15|unique:doctors,no_hp',
            'no_str' => 'required|string|size:12|unique:doctors,no_str',
            'jenis_kelamin' => 'required|in:laki-laki,perempuan',
            'tanggal_lahir' => 'required|date|before:today',
            'alamat' => 'required|string',
            'foto_profil' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'status' => 'required|in:active,inactive',
        ]);

        if ($validator->fails()) {
            return redirect()->route('dokter.create')
                ->withErrors($validator)
                ->withInput();
        }

        // Simpan data dokter
        $doctor = new Doctor();
        $doctor->nama = $request->nama;
        $doctor->spesialisasi = $request->spesialisasi;
        $doctor->email = $request->email;
        $doctor->no_hp = $request->no_hp;
        $doctor->no_str = $request->no_str;
        $doctor->jenis_kelamin = $request->jenis_kelamin;
        $doctor->tanggal_lahir = $request->tanggal_lahir;
        $doctor->alamat = $request->alamat;
        $doctor->status = $request->status;

        // Simpan foto profil jika ada
        if ($request->hasFile('foto_profil')) {
            $filename = time() . '_' . $request->file('foto_profil')->getClientOriginalName();
            $path = $request->file('foto_profil')->storeAs('doctors', $filename, 'public');
            $doctor->foto_profil = $path;
        }

        $doctor->save();

        return redirect()->route('dokter.index')
            ->with('success', 'Data dokter berhasil ditambahkan.');
    }

    /**
     * Menampilkan detail dokter berdasarkan ID.
     */
    public function show(Doctor $dokter)
    {
        return view('dashboard.dokter.show', compact('dokter'));
    }

    /**
     * Menampilkan form edit dokter.
     */
    public function edit(Doctor $dokter)
    {
        return view('dashboard.dokter.edit', compact('dokter'));
    }

    /**
     * Memperbarui data dokter.
     */
    public function update(Request $request, Doctor $dokter)
    {
        // Validasi input
        $validator = Validator::make($request->all(), [
            'nama' => 'required|string|max:255',
            'spesialisasi' => 'required|in:umum,gigi',
            'email' => 'required|email|unique:doctors,email,' . $dokter->id,
            'no_hp' => 'required|string|max:15|unique:doctors,no_hp,' . $dokter->id,
            'no_str' => 'required|string|size:12|unique:doctors,no_str,' . $dokter->id,
            'jenis_kelamin' => 'required|in:laki-laki,perempuan',
            'tanggal_lahir' => 'required|date|before:today',
            'alamat' => 'required|string',
            'foto_profil' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'status' => 'required|in:active,inactive',
        ]);

        if ($validator->fails()) {
            return redirect()->route('dokter.edit', $dokter->id)
                ->withErrors($validator)
                ->withInput();
        }

        // Perbarui data dokter
        $dokter->nama = $request->nama;
        $dokter->spesialisasi = $request->spesialisasi;
        $dokter->email = $request->email;
        $dokter->no_hp = $request->no_hp;
        $dokter->no_str = $request->no_str;
        $dokter->jenis_kelamin = $request->jenis_kelamin;
        $dokter->tanggal_lahir = $request->tanggal_lahir;
        $dokter->alamat = $request->alamat;
        $dokter->status = $request->status;

        // Update foto profil jika ada
        if ($request->hasFile('foto_profil')) {
            // Hapus foto lama jika ada
            if ($dokter->foto_profil) {
                Storage::disk('public')->delete($dokter->foto_profil);
            }
            
            $filename = time() . '_' . $request->file('foto_profil')->getClientOriginalName();
            $path = $request->file('foto_profil')->storeAs('doctors', $filename, 'public');
            $dokter->foto_profil = $path;
        }

        $dokter->save();

        return redirect()->route('dokter.index')
            ->with('success', 'Data dokter berhasil diperbarui.');
    }

    /**
     * Menghapus dokter dari database.
     */
    public function destroy(Doctor $dokter)
    {
        // Hapus foto profil jika ada
        if ($dokter->foto_profil) {
            Storage::disk('public')->delete($dokter->foto_profil);
        }

        $dokter->delete();

        return redirect()->route('dokter.index')
            ->with('success', 'Data dokter berhasil dihapus.');
    }
}