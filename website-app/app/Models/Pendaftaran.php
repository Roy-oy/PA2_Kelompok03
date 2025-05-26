<?php

namespace App\Models;

use App\Enums\StatusAntrian;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Laravel\Sanctum\HasApiTokens;

class Pendaftaran extends Model
{
    use HasFactory, HasApiTokens;

    protected $fillable = [
        'pasien_id',
        'jenis_pasien',
        'jenis_pembayaran',
        'keluhan',
        'cluster_id',
        'tanggal_daftar',
        'status',
    ];

    protected $casts = [
        'tanggal_daftar' => 'date',
        'status' => StatusAntrian::class,
    ];

    public function pasien()
    {
        return $this->belongsTo(Pasien::class);
    }

    public function cluster()
    {
        return $this->belongsTo(Cluster::class);
    }

    public function antrian()
    {
        return $this->hasOne(Antrian::class);
    }

    /**
     * Check if the pendaftaran can be edited based on antrian status.
     */
    public function canBeEdited(): bool
    {
        return $this->antrian && $this->antrian->status === StatusAntrian::BELUM_DIPANGGIL;
    }
}