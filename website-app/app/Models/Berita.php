<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Berita extends Model
{
    use HasFactory;

    // Specify the table name
    protected $table = 'berita';

    // Define which fields can be mass assigned
    protected $fillable = [
        'judul',
        'isi_berita',
        'tanggal_upload',
        'photo',
        'total_visitors',
        'kategori_berita_id'
    ];

    // Define date fields
    protected $casts = [
        'tanggal_upload' => 'date',
    ];

    // Define the relationship with KategoriBerita
    public function kategoriBerita()
    {
        return $this->belongsTo(KategoriBerita::class, 'kategori_berita_id');
    }

    // Increment visitor count
    public function incrementVisitor()
    {
        $this->increment('total_visitors');
    }
}