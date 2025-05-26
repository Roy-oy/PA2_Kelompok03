<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Laravel\Sanctum\HasApiTokens;

class AppUser extends Model
{
    use HasApiTokens, HasFactory; 

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'app_users';

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'tanggal_lahir',
        'alamat',
        'no_hp',
        'jenis_kelamin',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
    ];
    

    /**
     * Get the patient record associated with the app user.
     */
    public function pasien()
    {
        return $this->hasOne(Pasien::class, 'app_user_id'); // Assuming pasiens table has app_user_id
    }

    /**
     * Check if the app user has a patient record.
     */
    public function hasPasien()
    {
        return $this->pasien()->exists();
    }
}