<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Laravel\Sanctum\HasApiTokens;

class Cluster extends Model
{
    use HasFactory, HasApiTokens;

    // Nama tabel yang digunakan
    protected $table = 'clusters';

    // Kolom yang dapat diisi secara massal
    protected $fillable = [
        'nama',
        'description',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array
     */

    protected $casts = [
        'nama' => 'string', // Cast nama as string
    ];

    /**
     * Relasi ke tabel lain
     */

    // Relasi dengan tabel jadwal dokter (One-to-Many)
    public function schedules()
    {
        return $this->hasMany(DoctorSchedule::class, 'cluster_id');
    }
}