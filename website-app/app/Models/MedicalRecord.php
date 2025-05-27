<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MedicalRecord extends Model
{
    use HasFactory;

    protected $table = 'medical_records';

    protected $fillable = [
        'pendaftaran_id',
        'pasien_id',
        'tanggal_kunjungan',
        'keluhan',
        'diagnosis',
        'pengobatan',
        'hasil_pemeriksaan',
        'tinggi_badan',
        'berat_badan',
        'tekanan_darah',
        'suhu_badan',
    ];

    protected $casts = [
        'tanggal_kunjungan' => 'date',
        'tinggi_badan' => 'float',
        'berat_badan' => 'float',
        'suhu_badan' => 'float',
    ];

    public function pendaftaran()
    {
        return $this->belongsTo(Pendaftaran::class);
    }

    public function pasien()
    {
        return $this->belongsTo(Pasien::class);
    }
}