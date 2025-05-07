<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Laravel\Sanctum\HasApiTokens;

class Pasien extends Authenticatable
{
    use HasFactory, SoftDeletes, HasApiTokens;

    protected $table = 'pasiens';

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'user_id',
        'no_rm',
        'nama',
        'jenis_kelamin',
        'tanggal_lahir',
        'tempat_lahir',
        'alamat',
        'no_telepon',
        'pekerjaan',
        'no_bpjs',
        'golongan_darah',
        'rhesus',
        'status_perkawinan',
        'agama',
        'pendidikan',
        'tinggi_badan',
        'berat_badan',
        'imt',
        'tekanan_darah',
        'status_bpjs',
        'kelas_rawat',
        'masa_berlaku_bpjs',
        'riwayat_alergi',
        'riwayat_penyakit',
        'nik',
        'email',
        'password',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'tanggal_lahir' => 'date',
        'email_verified_at' => 'datetime',
        'password' => 'hashed',
    ];

    /**
     * Get the formatted date of birth.
     */
    public function getUmurAttribute()
    {
        return $this->tanggal_lahir->age . ' tahun';
    }
    
    /**
     * Get the user associated with the patient.
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Boot the model.
     */
    protected static function boot()
    {
        parent::boot();
        
        static::creating(function ($pasien) {
            $latest = static::latest()->first();
            
            if (!$latest) {
                // Jika belum ada data, mulai dari RM00001
                $pasien->no_rm = 'RM0001';
            } else {
                // Ambil angka dari no_rm terakhir dan tambahkan 1
                $number = intval(substr($latest->no_rm, 2)) + 1;
                $pasien->no_rm = 'RM' . str_pad($number, 4, '0', STR_PAD_LEFT);
            }
        });
    }
}