<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

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
        'status',
    ];

    // Format data otomatis
    protected $casts = [
        'tanggal_upload' => 'date',
        'status' => 'string',
    ];

    /**
     * Accessor to dynamically determine status based on tanggal_upload
     */
    public function getDynamicStatusAttribute()
    {
        $today = Carbon::today();
        if ($this->tanggal_upload->isBefore($today)) {
            return 'publish';
        }
        return $this->tanggal_upload->isSameDay($today) ? 'publish' : 'pending';
    }
}