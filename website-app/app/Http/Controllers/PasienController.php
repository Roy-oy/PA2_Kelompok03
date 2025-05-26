<?php

namespace App\Http\Controllers;

use App\Models\Pasien;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class PasienController extends Controller
{
    /**
     * Display a listing of the patients.
     *
     * @return \Illuminate\View\View
     */
    public function index()
    {
        $pasiens = Pasien::latest()->paginate(10);
        return view('dashboard.pasien.index', compact('pasiens'));
    }

    /**
     * Show the form for creating a new patient.
     *
     * @return \Illuminate\View\View
     */
    public function create()
    {
        return view('dashboard.pasien.create');
    }

    /**
     * Store a newly created patient in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'nik' => 'required|string|size:16|unique:pasiens,nik',
            'no_kk' => 'nullable|string|max:16',
            'nama' => 'required|string|max:255',
            'jenis_kelamin' => 'required|in:Laki-laki,Perempuan',
            'tanggal_lahir' => 'required|date',
            'tempat_lahir' => 'required|string|max:255',
            'alamat' => 'required|string',
            'no_hp' => 'nullable|string|max:15|unique:pasiens,no_hp',
            'pekerjaan' => 'nullable|string|max:255|unique:pasiens,pekerjaan',
            'no_bpjs' => 'nullable|string|max:20',
            'golongan_darah' => 'required|in:A,B,AB,O',
            'riwayat_alergi' => 'nullable|string',
            'riwayat_penyakit' => 'nullable|string',
        ]);

        if ($validator->fails()) {
            return redirect()->route('pasien.create')
                ->withErrors($validator)
                ->withInput();
        }

        Pasien::create($request->only([
            'nik',
            'no_kk',
            'nama',
            'jenis_kelamin',
            'tanggal_lahir',
            'tempat_lahir',
            'alamat',
            'no_hp',
            'pekerjaan',
            'no_bpjs',
            'golongan_darah',
            'riwayat_alergi',
            'riwayat_penyakit',
        ]));

        return redirect()->route('pasien.index')
            ->with('success', 'Data pasien berhasil ditambahkan.');
    }

    /**
     * Display the specified patient.
     *
     * @param  \App\Models\Pasien  $pasien
     * @return \Illuminate\View\View
     */
    public function show(Pasien $pasien)
    {
        return view('dashboard.pasien.show', compact('pasien'));
    }

    /**
     * Show the form for editing the specified patient.
     *
     * @param  \App\Models\Pasien  $pasien
     * @return \Illuminate\View\View
     */
    public function edit(Pasien $pasien)
    {
        return view('dashboard.pasien.edit', compact('pasien'));
    }

    /**
     * Update the specified patient in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Pasien  $pasien
     * @return \Illuminate\Http\RedirectResponse
     */
    public function update(Request $request, Pasien $pasien)
    {
        $validator = Validator::make($request->all(), [
            'nik' => 'required|string|size:16|unique:pasiens,nik,' . $pasien->id,
            'no_kk' => 'nullable|string|max:16',
            'nama' => 'required|string|max:255',
            'jenis_kelamin' => 'required|in:Laki-laki,Perempuan',
            'tanggal_lahir' => 'required|date',
            'tempat_lahir' => 'required|string|max:255',
            'alamat' => 'required|string',
            'no_hp' => 'nullable|string|max:15|unique:pasiens,no_hp,' . $pasien->id,
            'pekerjaan' => 'nullable|string|max:255|unique:pasiens,pekerjaan,' . $pasien->id,
            'no_bpjs' => 'nullable|string|max:20',
            'golongan_darah' => 'required|in:A,B,AB,O',
            'riwayat_alergi' => 'nullable|string',
            'riwayat_penyakit' => 'nullable|string',
        ]);

        if ($validator->fails()) {
            return redirect()->route('pasien.edit', $pasien->id)
                ->withErrors($validator)
                ->withInput();
        }

        $pasien->update($request->only([
            'nik',
            'no_kk',
            'nama',
            'jenis_kelamin',
            'tanggal_lahir',
            'tempat_lahir',
            'alamat',
            'no_hp',
            'pekerjaan',
            'no_bpjs',
            'golongan_darah',
            'riwayat_alergi',
            'riwayat_penyakit',
        ]));

        return redirect()->route('pasien.index')
            ->with('success', 'Data pasien berhasil diperbarui.');
    }

    /**
     * Remove the specified patient from storage.
     *
     * @param  \App\Models\Pasien  $pasien
     * @return \Illuminate\Http\RedirectResponse
     */
    public function destroy(Pasien $pasien)
    {
        $pasien->delete();

        return redirect()->route('pasien.index')
            ->with('success', 'Data pasien berhasil dihapus.');
    }
}