<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasApiTokens, HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'user_type',
        'phone',
        'nik',
        'date_of_birth',
        'gender',
        'position',
        'is_active',
        'last_login_at',
        'last_login_ip',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
            'last_login_at' => 'datetime',
            'date_of_birth' => 'date',
            'is_active' => 'boolean',
        ];
    }

    /**
     * Get the admin record associated with the user.
     */
    public function admin()
    {
        return $this->hasOne(Admin::class);
    }

    /**
     * Get the app user record associated with the user.
     */
    public function appUser()
    {
        return $this->hasOne(AppUser::class);
    }

    /**
     * Get the patient record associated with the user.
     */
    public function pasien()
    {
        return $this->hasOne(Pasien::class);
    }

    /**
     * Check if user is an admin
     * 
     * @return bool
     */
    public function isAdmin()
    {
        return $this->user_type === 'admin';
    }

    /**
     * Check if user is an app user
     * 
     * @return bool
     */
    public function isAppUser()
    {
        return $this->user_type === 'app_user';
    }

    /**
     * Check if user is a doctor
     * 
     * @return bool
     */
    public function isDoctor()
    {
        return $this->user_type === 'doctor';
    }

    /**
     * Check if user is a patient
     * 
     * @return bool
     */
    public function isPatient()
    {
        return $this->pasien()->exists();
    }

    /**
     * Check if user is a nurse
     * 
     * @return bool
     */
    public function isNurse()
    {
        return $this->user_type === 'nurse';
    }

    /**
     * Check if user is a staff
     * 
     * @return bool
     */
    public function isStaff()
    {
        return $this->user_type === 'staff';
    }
}
