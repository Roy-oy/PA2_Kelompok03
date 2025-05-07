<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class KategoriBerita extends Model
{
    use HasFactory;

    protected $table = 'kategori_berita';
    
    protected $fillable = [
        'nama_kategori',
        'deskripsi'
    ];
}
