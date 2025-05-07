<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Laravel\Sanctum\HasApiTokens;

class Antrian extends Model
{
    use HasFactory, HasApiTokens;

    protected $table = 'antrians';
    protected $fillable = [
        'no_antrian',
        'pasiens_id',
        'doctors_id',
        'tanggal_daftar',
        'pembayaran',
        'cluster',
        'complaint',
        'status'
    ];

    // Define date fields
    protected $casts = [
        'tanggal_daftar' => 'date',
    ];

    public function pasiens()
    {
        return $this->belongsTo(Pasien::class, 'pasiens_id');
    }

    public function doctors()
    {
        return $this->belongsTo(Doctor::class, 'doctors_id');
    }
}
