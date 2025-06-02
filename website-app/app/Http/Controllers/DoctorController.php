<?php

namespace App\Http\Controllers;

use App\Models\Doctor;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Log;

class DoctorController extends Controller
{
    public function index()
    {
        $doctors = Doctor::latest()->paginate(10);
        return view('dashboard.dokter.index', compact('doctors'));
    }

    public function create()
    {
        return view('dashboard.dokter.create');
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'nama' => 'required|string|max:255',
            'spesialisasi' => 'required|in:umum,gigi',
            'email' => 'required|email|unique:doctors,email',
            'no_hp' => 'required|string|max:15|unique:doctors,no_hp|regex:/^([0-9\s\-\+\(\)]*)$/',
            'no_str' => 'required|string|size:12|unique:doctors,no_str|regex:/^[0-9]+$/',
            'jenis_kelamin' => 'required|in:laki-laki,perempuan',
            'tanggal_lahir' => 'required|date|before:today',
            'alamat' => 'required|string|max:500',
            'foto_profil' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'status' => 'required|in:active,inactive',
        ], [
            'no_hp.regex' => 'Nomor HP hanya boleh berisi angka, spasi, tanda minus, plus, atau tanda kurung.',
            'no_str.regex' => 'Nomor STR harus berupa 12 digit angka.',
            'no_str.size' => 'Nomor STR harus tepat 12 digit.',
        ]);

        if ($validator->fails()) {
            return redirect()->route('dokter.create')
                ->withErrors($validator)
                ->withInput();
        }

        try {
            $doctor = new Doctor();
            $doctor->nama = trim($request->nama);
            $doctor->spesialisasi = $request->spesialisasi;
            $doctor->email = strtolower(trim($request->email));
            $doctor->no_hp = trim($request->no_hp);
            $doctor->no_str = trim($request->no_str);
            $doctor->jenis_kelamin = $request->jenis_kelamin;
            $doctor->tanggal_lahir = $request->tanggal_lahir;
            $doctor->alamat = trim($request->alamat);
            $doctor->status = $request->status;

            if ($request->hasFile('foto_profil')) {
                $filename = time() . '_' . str_replace(' ', '_', $request->file('foto_profil')->getClientOriginalName());
                $path = $request->file('foto_profil')->storeAs('doctors', $filename, 'public');
                $doctor->foto_profil = $path;
                Log::info('Profile photo uploaded', ['path' => $path, 'doctor' => $doctor->nama]);
            }

            $doctor->save();

            return redirect()->route('dokter.index')
                ->with('success', 'Data dokter berhasil ditambahkan.');
        } catch (\Exception $e) {
            Log::error('Failed to save doctor', ['error' => $e->getMessage()]);
            return redirect()->route('dokter.create')
                ->with('error', 'Gagal menambahkan data dokter. Silakan coba lagi.')
                ->withInput();
        }
    }

    public function show(Doctor $dokter)
    {
        return view('dashboard.dokter.show', compact('dokter'));
    }

    public function edit(Doctor $dokter)
    {
        return view('dashboard.dokter.edit', compact('dokter'));
    }

    public function update(Request $request, Doctor $dokter)
    {
        $validator = Validator::make($request->all(), [
            'nama' => 'required|string|max:255',
            'spesialisasi' => 'required|in:umum,gigi',
            'email' => 'required|email|unique:doctors,email,' . $dokter->id,
            'no_hp' => 'required|string|max:15|unique:doctors,no_hp,' . $dokter->id . '|regex:/^([0-9\s\-\+\(\)]*)$/',
            'no_str' => 'required|string|size:12|unique:doctors,no_str,' . $dokter->id . '|regex:/^[0-9]+$/',
            'jenis_kelamin' => 'required|in:laki-laki,perempuan',
            'tanggal_lahir' => 'required|date|before:today',
            'alamat' => 'required|string|max:500',
            'foto_profil' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'status' => 'required|in:active,inactive',
        ], [
            'no_hp.regex' => 'Nomor HP hanya boleh berisi angka, spasi, tanda minus, plus, atau tanda kurung.',
            'no_str.regex' => 'Nomor STR harus berupa 12 digit angka.',
            'no_str.size' => 'Nomor STR harus tepat 12 digit.',
        ]);

        if ($validator->fails()) {
            return redirect()->route('dokter.edit', $dokter->id)
                ->withErrors($validator)
                ->withInput();
        }

        try {
            $dokter->nama = trim($request->nama);
            $dokter->spesialisasi = $request->spesialisasi;
            $dokter->email = strtolower(trim($request->email));
            $dokter->no_hp = trim($request->no_hp);
            $dokter->no_str = trim($request->no_str);
            $dokter->jenis_kelamin = $request->jenis_kelamin;
            $dokter->tanggal_lahir = $request->tanggal_lahir;
            $dokter->alamat = trim($request->alamat);
            $dokter->status = $request->status;

            if ($request->hasFile('foto_profil')) {
                if ($dokter->foto_profil) {
                    Storage::disk('public')->delete($dokter->foto_profil);
                    Log::info('Old profile photo deleted', ['path' => $dokter->foto_profil]);
                }
                $filename = time() . '_' . str_replace(' ', '_', $request->file('foto_profil')->getClientOriginalName());
                $path = $request->file('foto_profil')->storeAs('doctors', $filename, 'public');
                $dokter->foto_profil = $path;
                Log::info('New profile photo uploaded', ['path' => $path, 'doctor' => $dokter->nama]);
            }

            $dokter->save();

            return redirect()->route('dokter.index')
                ->with('success', 'Data dokter berhasil diperbarui.');
        } catch (\Exception $e) {
            Log::error('Failed to update doctor', ['error' => $e->getMessage()]);
            return redirect()->route('dokter.edit', $dokter->id)
                ->with('error', 'Gagal memperbarui data dokter. Silakan coba lagi.')
                ->withInput();
        }
    }

    public function destroy(Doctor $dokter)
    {
        try {
            if ($dokter->foto_profil) {
                Storage::disk('public')->delete($dokter->foto_profil);
                Log::info('Profile photo deleted on doctor deletion', ['path' => $dokter->foto_profil]);
            }
            $dokter->delete();
            return redirect()->route('dokter.index')
                ->with('success', 'Data dokter berhasil dihapus.');
        } catch (\Exception $e) {
            Log::error('Failed to delete doctor', ['error' => $e->getMessage()]);
            return redirect()->route('dokter.index')
                ->with('error', 'Gagal menghapus data dokter. Silakan coba lagi.');
        }
    }
}