<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Pengumuman extends Model
{
    use HasFactory;

    // Nama tabel yang digunakan
    protected $table = 'pengumuman';

    // Kolom yang dapat diisi secara massal
    protected $fillable = [
        'judul',
        'isi_pengumuman',
        'tanggal_upload',
        'file_surat',
    ];

    // Format data otomatis
    protected $casts = [
        'tanggal' => 'date',
    ];
}
