<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Carbon\Carbon;
use Laravel\Sanctum\HasApiTokens;

class Pasien extends Model
{
    use HasFactory, SoftDeletes, HasApiTokens;

    protected $table = 'pasiens';

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'app_user_id',
        'no_rm',
        'nik',
        'no_kk',
        'nama',
        'jenis_kelamin',
        'tanggal_lahir',
        'tempat_lahir',
        'alamat',
        'no_hp',
        'pekerjaan',
        'no_bpjs',
        'golongan_darah',
        'riwayat_alergi',
        'riwayat_penyakit',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'jenis_kelamin' => 'string',
        'tanggal_lahir' => 'date',
        'golongan_darah' => 'string',
    ];

    /**
     * Get the formatted age of the patient.
     *
     * @return string
     */
    public function getUmurAttribute()
    {
        return Carbon::parse($this->tanggal_lahir)->age . ' tahun';
    }

    public function feedback()
    {
        return $this->hasMany(Feedback::class, 'pasien_id');
    }

    public function medicalRecords()
    {
        return $this->hasMany(MedicalRecord::class, 'pasien_id');
    }

    /**
     * Get the app user associated with the patient.
     */
    public function appUser()
    {
        return $this->belongsTo(AppUser::class, 'app_user_id');
    }

    /**
     * Boot the model.
     */
    protected static function boot()
    {
        parent::boot();

        static::creating(function ($pasien) {
            if (!$pasien->no_rm) {
                $latest = static::withTrashed()->latest()->first();
                $number = $latest ? (int) substr($latest->no_rm, 2) + 1 : 1;
                $pasien->no_rm = 'RM' . str_pad($number, 4, '0', STR_PAD_LEFT);
            }
        });
    }
}