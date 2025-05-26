<?php

namespace App\Http\Controllers;

use App\Models\Antrian;
use App\Models\Cluster;
use App\Models\Pendaftaran;
use App\Models\Pasien;
use App\Enums\StatusAntrian;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class PendaftaranController extends Controller
{
    public function index(Request $request)
    {
        $date = $request->query('date', now()->toDateString());

        try {
            $date = \Carbon\Carbon::parse($date)->toDateString();
        } catch (\Exception $e) {
            $date = now()->toDateString();
        }

        $pendaftarans = Pendaftaran::with(['pasien', 'cluster', 'antrian'])
            ->whereDate('tanggal_daftar', $date)
            ->orderBy('created_at', 'desc')
            ->paginate(10);

        return view('dashboard.pendaftaran.index', compact('pendaftarans', 'date'));
    }

    public function create()
    {
        $clusters = Cluster::all();
        return view('dashboard.pendaftaran.create', compact('clusters'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'nik' => 'required|string|size:16|regex:/^\d{16}$/',
            'nama' => 'required|string|max:255',
            'keluhan' => 'required|string',
            'cluster_id' => 'required|exists:clusters,id',
            'tanggal_daftar' => 'required|date',
            'jenis_pasien' => 'required|in:baru,lama',
            'jenis_pembayaran' => 'required|in:bpjs,umum',
            'app_user_id' => 'nullable|exists:app_users,id',
            'jenis_kelamin' => 'required|in:laki-laki,perempuan',
            'tanggal_lahir' => 'required|date|before:today',
            'tempat_lahir' => 'required|string|max:255',
            'alamat' => 'required|string|max:255',
            'no_hp' => 'required|string|max:20',
            'no_kk' => 'nullable|string|size:16|regex:/^\d{16}$/',
            'pekerjaan' => 'nullable|string|max:255',
            'no_bpjs' => [
                'nullable',
                'string',
                'size:13',
                'regex:/^\d{13}$/',
                function ($attribute, $value, $fail) use ($request) {
                    if ($request->jenis_pembayaran === 'bpjs' && !$value) {
                        $fail('No. BPJS wajib diisi untuk pembayaran BPJS.');
                    }
                    if ($value && Pasien::where('no_bpjs', $value)->where('nik', '!=', $request->nik)->exists()) {
                        $fail('No. BPJS sudah digunakan oleh pasien lain.');
                    }
                },
            ],
            'golongan_darah' => 'required|in:A,B,AB,O',
            'riwayat_alergi' => 'nullable|string',
            'riwayat_penyakit' => 'nullable|string',
        ], [
            'nik.required' => 'NIK wajib diisi.',
            'nik.size' => 'NIK harus 16 digit angka.',
            'nik.regex' => 'NIK harus berupa angka.',
            'nama.required' => 'Nama wajib diisi.',
            'keluhan.required' => 'Keluhan wajib diisi.',
            'cluster_id.required' => 'Cluster wajib dipilih.',
            'tanggal_daftar.required' => 'Tanggal daftar wajib diisi.',
            'jenis_pasien.required' => 'Jenis pasien wajib dipilih.',
            'jenis_pembayaran.required' => 'Jenis pembayaran wajib dipilih.',
            'jenis_kelamin.required' => 'Jenis kelamin wajib dipilih.',
            'tanggal_lahir.required' => 'Tanggal lahir wajib diisi.',
            'tanggal_lahir.before' => 'Tanggal lahir harus sebelum hari ini.',
            'tempat_lahir.required' => 'Tempat lahir wajib diisi.',
            'alamat.required' => 'Alamat wajib diisi.',
            'no_kk.size' => 'No. KK harus 16 digit angka.',
            'no_kk.regex' => 'No. KK harus berupa angka.',
            'no_bpjs.size' => 'No. BPJS harus 13 digit angka.',
            'no_bpjs.regex' => 'No. BPJS harus berupa angka.',
        ]);

        try {
            return DB::transaction(function () use ($request, $validated) {
                $pasien = Pasien::where('nik', $request->nik)->first();

                if ($request->jenis_pasien === 'lama') {
                    if (!$pasien) {
                        return redirect()->back()->withErrors(['nik' => 'Pasien dengan NIK ini tidak ditemukan.'])->withInput();
                    }
                    if (!$pasien->no_rm) {
                        return redirect()->back()->withErrors(['nik' => 'Pasien dengan NIK ini tidak memiliki nomor rekam medis.'])->withInput();
                    }
                    if ($request->no_bpjs && $pasien->no_bpjs && $request->no_bpjs !== $pasien->no_bpjs) {
                        return redirect()->back()->withErrors(['no_bpjs' => 'No. BPJS tidak sesuai dengan data pasien.'])->withInput();
                    }
                }

                if ($request->jenis_pasien === 'baru') {
                    if ($pasien) {
                        return redirect()->back()->withErrors(['nik' => 'NIK sudah terdaftar. Gunakan jenis pasien lama.'])->withInput();
                    }
                    $pasien = Pasien::create([
                        'nik' => $request->nik,
                        'no_kk' => $request->no_kk,
                        'nama' => $request->nama,
                        'app_user_id' => $request->app_user_id ?? null,
                        'jenis_kelamin' => $request->jenis_kelamin,
                        'tanggal_lahir' => $request->tanggal_lahir,
                        'tempat_lahir' => $request->tempat_lahir,
                        'alamat' => $request->alamat,
                        'no_hp' => $request->no_hp,
                        'pekerjaan' => $request->pekerjaan,
                        'no_bpjs' => $request->no_bpjs,
                        'golongan_darah' => $request->golongan_darah,
                        'riwayat_alergi' => $request->riwayat_alergi,
                        'riwayat_penyakit' => $request->riwayat_penyakit,
                    ]);
                } elseif ($request->no_bpjs && !$pasien->no_bpjs) {
                    $pasien->update(['no_bpjs' => $request->no_bpjs]);
                }

                $pendaftaran = Pendaftaran::create([
                    'pasien_id' => $pasien->id,
                    'jenis_pasien' => $request->jenis_pasien,
                    'jenis_pembayaran' => $request->jenis_pembayaran,
                    'keluhan' => $request->keluhan,
                    'cluster_id' => $request->cluster_id,
                    'tanggal_daftar' => $request->tanggal_daftar,
                    'status' => StatusAntrian::BELUM_DIPANGGIL,
                ]);

                $antrian = Antrian::create([
                    'pendaftaran_id' => $pendaftaran->id,
                    'cluster_id' => $pendaftaran->cluster_id,
                    'no_antrian' => Antrian::generateNoAntrian($pendaftaran->tanggal_daftar),
                    'tanggal' => $pendaftaran->tanggal_daftar,
                    'status' => StatusAntrian::BELUM_DIPANGGIL,
                ]);

                if (!$antrian) {
                    throw new \Exception('Gagal membuat antrian.');
                }

                return redirect()->route('pendaftaran.index')->with('success', 'Pendaftaran berhasil. Antrian nomor ' . $antrian->no_antrian . ' telah dibuat.');
            });
        } catch (\Exception $e) {
            Log::error('Pendaftaran creation failed: ' . $e->getMessage());
            return redirect()->back()->withErrors(['error' => 'Terjadi kesalahan saat menyimpan pendaftaran. Silakan coba lagi.'])->withInput();
        }
    }

    public function edit(Pendaftaran $pendaftaran)
    {
        if (!$pendaftaran->canBeEdited()) {
            return redirect()->route('pendaftaran.index')->withErrors(['error' => 'Pendaftaran tidak dapat diedit karena status antrian bukan "Belum Dipanggil".']);
        }

        $clusters = Cluster::all();
        $pendaftaran->load('pasien');
        return view('dashboard.pendaftaran.edit', compact('pendaftaran', 'clusters'));
    }

    public function update(Request $request, Pendaftaran $pendaftaran)
    {
        if (!$pendaftaran->canBeEdited()) {
            return redirect()->route('pendaftaran.index')->withErrors(['error' => 'Pendaftaran tidak dapat diedit karena status antrian bukan "Belum Dipanggil".']);
        }

        $validated = $request->validate([
            'keluhan' => 'required|string',
            'cluster_id' => 'required|exists:clusters,id',
            'tanggal_daftar' => 'required|date',
            'jenis_pembayaran' => 'required|in:bpjs,umum',
            'no_bpjs' => [
                'nullable',
                'string',
                'size:13',
                'regex:/^\d{13}$/',
                function ($attribute, $value, $fail) use ($request, $pendaftaran) {
                    if ($request->jenis_pembayaran === 'bpjs' && !$value) {
                        $fail('No. BPJS wajib diisi untuk pembayaran BPJS.');
                    }
                    if ($value && Pasien::where('no_bpjs', $value)
                        ->where('nik', '!=', $pendaftaran->pasien->nik)
                        ->exists()) {
                        $fail('No. BPJS sudah digunakan oleh pasien lain.');
                    }
                },
            ],
            'nama' => $pendaftaran->jenis_pasien === 'baru' ? 'required|string|max:255' : 'nullable',
            'jenis_kelamin' => $pendaftaran->jenis_pasien === 'baru' ? 'required|in:laki-laki,perempuan' : 'nullable',
            'tanggal_lahir' => $pendaftaran->jenis_pasien === 'baru' ? 'required|date|before:today' : 'nullable',
            'tempat_lahir' => $pendaftaran->jenis_pasien === 'baru' ? 'required|string|max:255' : 'nullable',
            'alamat' => $pendaftaran->jenis_pasien === 'baru' ? 'required|string|max:255' : 'nullable',
            'no_hp' => 'required|string|max:20',
            'no_kk' => 'nullable|string|size:16|regex:/^\d{16}$/',
            'pekerjaan' => 'nullable|string|max:255',
            'golongan_darah' => 'required|in:A,B,AB,O',
            'riwayat_alergi' => 'nullable|string',
            'riwayat_penyakit' => 'nullable|string',
        ], [
            'keluhan.required' => 'Keluhan wajib diisi.',
            'cluster_id.required' => 'Cluster wajib dipilih.',
            'tanggal_daftar.required' => 'Tanggal daftar wajib diisi.',
            'jenis_pembayaran.required' => 'Jenis pembayaran wajib dipilih.',
            'no_bpjs.size' => 'No. BPJS harus 13 digit angka.',
            'no_bpjs.regex' => 'No. BPJS harus berupa angka.',
            'nama.required' => 'Nama wajib diisi untuk pasien baru.',
            'jenis_kelamin.required' => 'Jenis kelamin wajib dipilih untuk pasien baru.',
            'tanggal_lahir.required' => 'Tanggal lahir wajib diisi untuk pasien baru.',
            'tanggal_lahir.before' => 'Tanggal lahir harus sebelum hari ini.',
            'tempat_lahir.required' => 'Tempat lahir wajib diisi untuk pasien baru.',
            'alamat.required' => 'Alamat wajib diisi untuk pasien baru.',
            'no_kk.size' => 'No. KK harus 16 digit angka.',
            'no_kk.regex' => 'No. KK harus berupa angka.',
        ]);

        try {
            return DB::transaction(function () use ($request, $pendaftaran, $validated) {
                $pasien = $pendaftaran->pasien;

                if ($pendaftaran->jenis_pasien === 'baru') {
                    $pasien->update([
                        'nama' => $request->nama,
                        'no_kk' => $request->no_kk,
                        'jenis_kelamin' => $request->jenis_kelamin,
                        'tanggal_lahir' => $request->tanggal_lahir,
                        'tempat_lahir' => $request->tempat_lahir,
                        'alamat' => $request->alamat,
                        'no_hp' => $request->no_hp,
                        'pekerjaan' => $request->pekerjaan,
                        'no_bpjs' => $request->no_bpjs,
                        'golongan_darah' => $request->golongan_darah,
                        'riwayat_alergi' => $request->riwayat_alergi,
                        'riwayat_penyakit' => $request->riwayat_penyakit,
                    ]);
                } elseif ($request->no_bpjs && $pasien->no_bpjs !== $request->no_bpjs) {
                    $pasien->update(['no_bpjs' => $request->no_bpjs]);
                }

                $pendaftaran->update([
                    'keluhan' => $request->keluhan,
                    'cluster_id' => $request->cluster_id,
                    'tanggal_daftar' => $request->tanggal_daftar,
                    'jenis_pembayaran' => $request->jenis_pembayaran,
                ]);

                $antrian = $pendaftaran->antrian;
                if ($antrian) {
                    $antrian->update([
                        'cluster_id' => $request->cluster_id,
                        'tanggal' => $request->tanggal_daftar,
                    ]);
                } else {
                    Log::warning('No antrian found for pendaftaran ID ' . $pendaftaran->id);
                    $antrian = Antrian::create([
                        'pendaftaran_id' => $pendaftaran->id,
                        'cluster_id' => $pendaftaran->cluster_id,
                        'no_antrian' => Antrian::generateNoAntrian($pendaftaran->tanggal_daftar),
                        'tanggal' => $pendaftaran->tanggal_daftar,
                        'status' => StatusAntrian::BELUM_DIPANGGIL,
                    ]);
                    $pendaftaran->update(['status' => StatusAntrian::BELUM_DIPANGGIL]);
                }

                return redirect()->route('pendaftaran.index')->with('success', 'Pendaftaran berhasil diperbarui. Antrian nomor ' . $antrian->no_antrian . ' tetap.');
            });
        } catch (\Exception $e) {
            Log::error('Pendaftaran update failed: ' . $e->getMessage());
            return redirect()->back()->withErrors(['error' => 'Terjadi kesalahan saat memperbarui pendaftaran. Silakan coba lagi.'])->withInput();
        }
    }
}