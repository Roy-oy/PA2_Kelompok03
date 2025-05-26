<?php

namespace App\Observers;

use App\Models\Pendaftaran;
use App\Models\Pasien;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class PendaftaranObserver
{
    public function created(Pendaftaran $pendaftaran)
    {
        if ($pendaftaran->jenis_pasien !== 'baru') {
            return;
        }

        $pasien = $pendaftaran->pasien;
        if (!$pasien) {
            Log::error('PendaftaranObserver: Pasien not found for pendaftaran ID ' . $pendaftaran->id);
            return;
        }

        try {
            DB::transaction(function () use ($pasien) {
                $date = now()->format('Ymd');
                $prefix = "RM-{$date}-";
                $lastNoRm = Pasien::where('no_rm', 'like', $prefix . '%')
                    ->max('no_rm');

                $nextNumber = 1;
                if ($lastNoRm) {
                    $lastNumber = (int) substr($lastNoRm, strlen($prefix));
                    $nextNumber = $lastNumber + 1;
                }

                $no_rm = $prefix . str_pad($nextNumber, 4, '0', STR_PAD_LEFT);

                $pasien->update(['no_rm' => $no_rm]);
                Log::info('Assigned no_rm ' . $no_rm . ' to pasien ID ' . $pasien->id);
            });
        } catch (\Exception $e) {
            Log::error('PendaftaranObserver: Failed to assign no_rm for pasien ID ' . $pasien->id . ': ' . $e->getMessage());
        }
    }
}