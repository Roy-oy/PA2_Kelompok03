<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Faq extends Model
{
    use HasFactory;

    /**
     * Atribut yang dapat diisi (fillable).
     *
     * @var array
     */
    protected $fillable = [
        'kategori',
        'question',
        'answer',
    ];

    /**
     * Atribut yang harus di-cast.
     *
     * @var array
     */
    protected $casts = [
        'kategori' => 'string',
    ];

    /**
     * Accessor untuk mendapatkan warna berdasarkan kategori.
     *
     * @return string
     */
    public function getKategoriColorAttribute()
    {
        return match ($this->kategori) {
            'umum' => 'bg-green-100 text-green-800',
            'pendaftaran' => 'bg-blue-100 text-blue-800',
            'layanan' => 'bg-yellow-100 text-yellow-800',
            'pembayaran' => 'bg-red-100 text-red-800',
            default => 'bg-gray-100 text-gray-800',
        };
    }
}