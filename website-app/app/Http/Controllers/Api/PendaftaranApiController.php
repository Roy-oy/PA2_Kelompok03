<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Antrian;
use App\Models\Pasien;
use App\Models\Pendaftaran;
use App\Models\Cluster;
use App\Enums\StatusAntrian;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;

class PendaftaranApiController extends Controller
{
    public function index(Request $request)
    {
        try {
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

            return response()->json([
                'success' => true,
                'message' => 'Registrations retrieved successfully.',
                'data' => $pendaftarans,
            ], 200);
        } catch (\Exception $e) {
            Log::error('Failed to retrieve registrations: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Failed to retrieve registrations.',
                'error' => $e->getMessage(),
            ], 500);
        }
    }

    public function getClusters()
    {
        try {
            $clusters = Cluster::all();

            return response()->json([
                'success' => true,
                'message' => 'Clusters retrieved successfully.',
                'data' => $clusters,
            ], 200);
        } catch (\Exception $e) {
            Log::error('Failed to retrieve clusters: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Failed to retrieve clusters.',
                'error' => $e->getMessage(),
            ], 500);
        }
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'nik' => 'required|string|size:16|regex:/^\d{16}$/',
            'nama' => 'required|string|max:255',
            'keluhan' => 'required|string',
            'cluster_id' => 'required|exists:clusters,id',
            'tanggal_daftar' => 'required|date|date_format:Y-m-d',
            'jenis_pasien' => 'required|in:baru,lama',
            'jenis_pembayaran' => 'required|in:bpjs,umum',
            'app_user_id' => 'nullable|exists:app_users,id',
            'jenis_kelamin' => 'required|in:laki-laki,perempuan',
            'tanggal_lahir' => 'required|date|date_format:Y-m-d|before:today',
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
            'tanggal_daftar.date_format' => 'Format tanggal daftar harus YYYY-MM-DD.',
            'jenis_pasien.required' => 'Jenis pasien wajib dipilih.',
            'jenis_pembayaran.required' => 'Jenis pembayaran wajib dipilih.',
            'jenis_kelamin.required' => 'Jenis kelamin wajib dipilih.',
            'tanggal_lahir.required' => 'Tanggal lahir wajib diisi.',
            'tanggal_lahir.date_format' => 'Format tanggal lahir harus YYYY-MM-DD.',
            'tanggal_lahir.before' => 'Tanggal lahir harus sebelum hari ini.',
            'tempat_lahir.required' => 'Tempat lahir wajib diisi.',
            'alamat.required' => 'Alamat wajib diisi.',
            'no_kk.size' => 'No. KK harus 16 digit angka.',
            'no_kk.regex' => 'No. KK harus berupa angka.',
            'no_bpjs.size' => 'No. BPJS harus 13 digit angka.',
            'no_bpjs.regex' => 'No. BPJS harus berupa angka.',
        ]);

        if ($validator->fails()) {
            Log::warning('Validation failed during registration creation', [
                'errors' => $validator->errors()->toArray(),
            ]);
            return response()->json([
                'success' => false,
                'message' => 'Validasi gagal',
                'errors' => $validator->errors()->toArray(),
            ], 422);
        }

        try {
            $pendaftaran = DB::transaction(function () use ($request) {
                $pasien = Pasien::where('nik', $request->nik)->first();

                if ($request->jenis_pasien === 'lama') {
                    if (!$pasien) {
                        throw new \Exception('Pasien dengan NIK ini tidak ditemukan.');
                    }
                    if (!$pasien->no_rm) {
                        throw new \Exception('Pasien dengan NIK ini tidak memiliki nomor rekam medis.');
                    }
                    if ($request->no_bpjs && $pasien->no_bpjs && $request->no_bpjs !== $pasien->no_bpjs) {
                        throw new \Exception('No. BPJS tidak sesuai dengan data pasien.');
                    }
                }

                if ($request->jenis_pasien === 'baru') {
                    if ($pasien) {
                        throw new \Exception('NIK sudah terdaftar. Gunakan jenis pasien lama.');
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
                    'no_antrian' => Antrian::generateNoAntrian($pendaftaran->tanggal_daftar, $pendaftaran->cluster_id),
                    'tanggal' => $pendaftaran->tanggal_daftar,
                    'status' => StatusAntrian::BELUM_DIPANGGIL,
                ]);

                if (!$antrian) {
                    throw new \Exception('Gagal membuat antrian.');
                }

                return $pendaftaran->load(['pasien', 'cluster', 'antrian']);
            });

            Log::info('Registration created successfully', [
                'pendaftaran_id' => $pendaftaran->id,
                'pasien_id' => $pendaftaran->pasien_id,
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Pendaftaran berhasil. Antrian nomor ' . $pendaftaran->antrian->no_antrian . ' telah dibuat.',
                'data' => [
                    'id' => $pendaftaran->id,
                    'pasien' => [
                        'nama' => $pendaftaran->pasien->nama,
                    ],
                    'cluster' => [
                        'nama' => $pendaftaran->cluster->nama,
                    ],
                    'keluhan' => $pendaftaran->keluhan,
                    'tanggal_daftar' => $pendaftaran->tanggal_daftar,
                    'no_antrian' => $pendaftaran->antrian->no_antrian,
                ],
            ], 201);
        } catch (\Exception $e) {
            Log::error('Registration creation failed: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Pendaftaran gagal: ' . $e->getMessage(),
                'error' => $e->getMessage(),
            ], 400);
        }
    }

    public function show($id)
    {
        try {
            $pendaftaran = Pendaftaran::with(['pasien', 'cluster', 'antrian'])->findOrFail($id);

            Log::info('Registration retrieved successfully', ['pendaftaran_id' => $id]);

            return response()->json([
                'success' => true,
                'message' => 'Registration retrieved successfully.',
                'data' => [
                    'id' => $pendaftaran->id,
                    'pasien' => [
                        'nama' => $pendaftaran->pasien->nama,
                    ],
                    'cluster' => [
                        'nama' => $pendaftaran->cluster->nama,
                    ],
                    'keluhan' => $pendaftaran->keluhan,
                    'tanggal_daftar' => $pendaftaran->tanggal_daftar,
                    'no_antrian' => $pendaftaran->antrian ? $pendaftaran->antrian->no_antrian : '-',
                ],
            ], 200);
        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
            Log::warning('Registration not found', ['pendaftaran_id' => $id]);
            return response()->json([
                'success' => false,
                'message' => 'Pendaftaran tidak ditemukan.',
            ], 404);
        } catch (\Exception $e) {
            Log::error('Failed to retrieve registration: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Gagal memuat pendaftaran.',
                'error' => $e->getMessage(),
            ], 500);
        }
    }

    public function update(Request $request, Pendaftaran $pendaftaran)
    {
        if (!$pendaftaran->canBeEdited()) {
            Log::warning('Registration cannot be edited', [
                'pendaftaran_id' => $pendaftaran->id,
                'status' => $pendaftaran->antrian ? $pendaftaran->antrian->status : 'no antrian',
            ]);
            return response()->json([
                'success' => false,
                'message' => 'Pendaftaran tidak dapat diedit karena status antrian bukan "Belum Dipanggil".',
            ], 403);
        }

        $validator = Validator::make($request->all(), [
            'keluhan' => 'required|string',
            'cluster_id' => 'required|exists:clusters,id',
            'tanggal_daftar' => 'required|date|date_format:Y-m-d',
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
            'tanggal_lahir' => $pendaftaran->jenis_pasien === 'baru' ? 'required|date|date_format:Y-m-d|before:today' : 'nullable',
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
            'tanggal_daftar.date_format' => 'Format tanggal daftar harus YYYY-MM-DD.',
            'jenis_pembayaran.required' => 'Jenis pembayaran wajib dipilih.',
            'no_bpjs.size' => 'No. BPJS harus 13 digit angka.',
            'no_bpjs.regex' => 'No. BPJS harus berupa angka.',
            'nama.required' => 'Nama wajib diisi untuk pasien baru.',
            'jenis_kelamin.required' => 'Jenis kelamin wajib dipilih untuk pasien baru.',
            'tanggal_lahir.required' => 'Tanggal lahir wajib diisi untuk pasien baru.',
            'tanggal_lahir.date_format' => 'Format tanggal lahir harus YYYY-MM-DD.',
            'tanggal_lahir.before' => 'Tanggal lahir harus sebelum hari ini.',
            'tempat_lahir.required' => 'Tempat lahir wajib diisi untuk pasien baru.',
            'alamat.required' => 'Alamat wajib diisi untuk pasien baru.',
            'no_kk.size' => 'No. KK harus 16 digit angka.',
            'no_kk.regex' => 'No. KK harus berupa angka.',
        ]);

        if ($validator->fails()) {
            Log::warning('Validation failed during registration update', [
                'pendaftaran_id' => $pendaftaran->id,
                'errors' => $validator->errors()->toArray(),
            ]);
            return response()->json([
                'success' => false,
                'message' => 'Validasi gagal',
                'errors' => $validator->errors()->toArray(),
            ], 422);
        }

        try {
            $pendaftaran = DB::transaction(function () use ($request, $pendaftaran) {
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
                        'no_antrian' => Antrian::generateNoAntrian($pendaftaran->tanggal_daftar, $pendaftaran->cluster_id),
                        'tanggal' => $pendaftaran->tanggal_daftar,
                        'status' => StatusAntrian::BELUM_DIPANGGIL,
                    ]);
                    $pendaftaran->update(['status' => StatusAntrian::BELUM_DIPANGGIL]);
                }

                return $pendaftaran->load(['pasien', 'cluster', 'antrian']);
            });

            Log::info('Registration updated successfully', [
                'pendaftaran_id' => $pendaftaran->id,
                'pasien_id' => $pendaftaran->pasien_id,
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Pendaftaran berhasil diperbarui. Antrian nomor ' . $pendaftaran->antrian->no_antrian . ' tetap.',
                'data' => [
                    'id' => $pendaftaran->id,
                    'pasien' => [
                        'nama' => $pendaftaran->pasien->nama,
                    ],
                    'cluster' => [
                        'nama' => $pendaftaran->cluster->nama,
                    ],
                    'keluhan' => $pendaftaran->keluhan,
                    'tanggal_daftar' => $pendaftaran->tanggal_daftar,
                    'no_antrian' => $pendaftaran->antrian->no_antrian,
                ],
            ], 200);
        } catch (\Exception $e) {
            Log::error('Registration update failed: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Pendaftaran gagal diperbarui.',
                'error' => $e->getMessage(),
            ], 500);
        }
    }

    public function showByNik($nik)
    {
        $pasien = Pasien::where('nik', $nik)->first();

        if (!$pasien) {
            return response()->json([
                'success' => false,
                'message' => 'Pasien dengan NIK ini tidak ditemukan.',
            ], 404);
        }

        $pendaftaranTerakhir = Pendaftaran::where('pasien_id', $pasien->id)
            ->with(['cluster', 'antrian'])
            ->latest()
            ->first();

        return response()->json([
            'success' => true,
            'data' => [
                'pasien' => [
                    'id' => $pasien->id,
                    'nik' => $pasien->nik,
                    'nama' => $pasien->nama,
                    'jenis_kelamin' => $pasien->jenis_kelamin,
                    'tanggal_lahir' => $pasien->tanggal_lahir,
                    'tempat_lahir' => $pasien->tempat_lahir,
                    'alamat' => $pasien->alamat,
                    'no_hp' => $pasien->no_hp,
                    'pekerjaan' => $pasien->pekerjaan,
                    'no_bpjs' => $pasien->no_bpjs,
                    'golongan_darah' => $pasien->golongan_darah,
                    'riwayat_alergi' => $pasien->riwayat_alergi,
                    'riwayat_penyakit' => $pasien->riwayat_penyakit,
                    'no_rm' => $pasien->no_rm,
                ],
                'pendaftaran' => $pendaftaranTerakhir ? [
                    'id' => $pendaftaranTerakhir->id,
                    'keluhan' => $pendaftaranTerakhir->keluhan,
                    'cluster' => $pendaftaranTerakhir->cluster,
                    'antrian' => $pendaftaranTerakhir->antrian,
                ] : null,
            ],
        ], 200);
    }

    public function destroy($id)
    {
        try {
            $pendaftaran = Pendaftaran::findOrFail($id);
            if (!$pendaftaran->canBeEdited()) {
                Log::warning('Registration cannot be deleted', [
                    'pendaftaran_id' => $pendaftaran->id,
                    'status' => $pendaftaran->antrian ? $pendaftaran->antrian->status : 'no antrian',
                ]);
                return response()->json([
                    'success' => false,
                    'message' => 'Pendaftaran tidak dapat dihapus karena status antrian bukan "Belum Dipanggil".',
                ], 403);
            }

            $pendaftaran->delete();

            Log::info('Registration deleted successfully', ['pendaftaran_id' => $id]);

            return response()->json([
                'success' => true,
                'message' => 'Pendaftaran berhasil dihapus.',
            ], 200);
        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
            Log::warning('Registration not found for deletion', ['pendaftaran_id' => $id]);
            return response()->json([
                'success' => false,
                'message' => 'Pendaftaran tidak ditemukan.',
            ], 404);
        } catch (\Exception $e) {
            Log::error('Failed to delete registration: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Gagal menghapus pendaftaran.',
                'error' => $e->getMessage(),
            ], 500);
        }
    }
}