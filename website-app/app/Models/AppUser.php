<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AppUser extends Model
{
    use HasFactory;

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
        'user_id',
        // Add any app_user specific attributes here
    ];

    /**
     * Get the user that owns the app user.
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Get the patient record associated with the app user.
     */
    public function pasien()
    {
        return $this->user->pasien();
    }

    /**
     * Check if the app user has a patient record.
     */
    public function hasPasien()
    {
        return $this->user->pasien()->exists();
    }
} 